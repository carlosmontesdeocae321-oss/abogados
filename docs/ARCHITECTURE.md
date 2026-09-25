# Arquitectura — lawfirm (Estudio Jiménez & Asociados)

Este documento describe **dónde estamos**, **a dónde vamos** y **cómo migramos sin romper el sitio**.

> **Regla principal: todo código nuevo debe escribirse en la nueva arquitectura, salvo que exista
> una razón explícita (documentada en el propio cambio) para tocar el legacy.**

---

## 1. Arquitectura actual (LEGACY)

PHP procedural, un archivo por página o endpoint, servido desde la raíz del proyecto (que hoy es el
DocumentRoot).

| Zona | Archivos | Notas |
|---|---|---|
| Páginas públicas | `index.php`, `abogado.php`, `about.php`→`about.html`, `practice.php`, `contact.php`, `consultar-caso.php`, `*.html` | HTML mezclado con PHP y SQL (`index.php`, `abogado.php`) |
| Panel admin | `admin/*.php` | SQL, HTML y JS en el mismo archivo |
| Endpoints | `backend/*.php`, `api/*.php` | Lógica de negocio, validación, subidas y DDL dentro del endpoint |
| Autenticación | `backend/auth.php` (`adminLogin`, `requireAdmin`, `adminLogout`) | `inc/require_admin.php` existe pero está inactivo |
| Conexión BD | `db.php` (global `$pdo`, lo usa `api/`) y `backend/db.php` (`getPDO()`) | Dos conexiones duplicadas; imprimen y hacen `exit` si fallan |
| Esquema | `db/*.sql` + `CREATE/ALTER TABLE` en endpoints | Sin esquema único |
| Logs | `php_server_log.txt` en la raíz pública | Debe desaparecer (ver auditoría) |

El legacy **sigue siendo el que atiende todas las URLs** hasta que se migre cada módulo.

## 2. Arquitectura objetivo (NUEVA)

```
lawfirm/
├── app/                      Código PHP con namespace App\ (PSR-4)
│   ├── Bootstrap/            Application (kernel), ErrorHandler
│   ├── Config/               Env (lectura del entorno), Repository, Config (fachada oficial)
│   ├── Database/             Connection (único PDO), ConnectionException
│   ├── Http/                 Request, Response (+ Router y middleware en fases siguientes)
│   ├── Logging/              Logger (storage/logs)
│   ├── Security/             Session, Csrf, RateLimiter, SecurityHeaders, Validator (Fase 2)
│   ├── Auth/                 AuthService, AdminGuard (Fase 2)
│   └── Modules/              Un directorio por dominio:
│       ├── Lawyers/          Controller → Service → Repository
│       ├── Publications/
│       ├── Faqs/
│       ├── Indicators/
│       ├── Consultations/
│       ├── Appointments/
│       └── AdminUsers/
├── bootstrap/app.php         Punto de entrada común de la nueva arquitectura
├── config/                   app.php, database.php, security.php (sin secretos)
├── database/migrations|seeds Esquema versionado (Fase 5)
├── public/                   Futuro DocumentRoot (ver §6)
├── routes/                   web.php, api.php, admin.php (inactivos hasta que exista el Router)
├── templates/                public/, admin/, components/ (vistas sin SQL)
├── storage/logs|temp         Logs y temporales (nunca accesibles por HTTP)
├── tests/                    Smoke tests; suite formal en Fase 7
└── vendor/                   Composer (no versionado)
```

### Capas y dependencias permitidas

```
HTTP (entrypoint / ruta)
  ↓
Controller      lee Request, llama al Service, devuelve Response. Sin SQL.
  ↓
Service         reglas de negocio, validación, transacciones. Sin HTML ni superglobales.
  ↓
Repository      SQL parametrizado vía PDO. Sin HTML, sin sesión, sin $_POST.
  ↓
Database        App\Database\Connection (una sola instancia por request)
```

Prohibido: SQL en vistas o templates, HTML en repositorios, autenticación duplicada, varias
conexiones PDO, lógica de negocio en archivos de `api/`, creación de tablas desde HTTP,
configuración o credenciales hardcodeadas.

### Estructura de un módulo migrado (ejemplo: Faqs)

```
app/Modules/Faqs/
├── FaqController.php         (Http)
├── FaqAdminController.php    (Http, protegido por AdminGuard + Csrf)
├── FaqService.php            (aplicación / negocio)
├── FaqRepository.php         (SQL)
└── Faq.php                   (entidad o DTO, opcional)
templates/admin/faqs/*.php    (vistas)
```

## 3. Piezas creadas en la Fase 1 (Foundation)

| Pieza | Uso |
|---|---|
| `bootstrap/app.php` | `$app = require __DIR__.'/../bootstrap/app.php';`: carga Composer, `.env` y `config/`, registra el ErrorHandler y devuelve la misma `Application` aunque se incluya varias veces. |
| `App\Config\Config` | **Única forma oficial** de leer configuración: `Config::get('database.host')`, `Config::bool('app.debug')`. |
| `App\Config\Env` | Lee variables del entorno real y, si no existen, del `.env`. No usa `putenv()`. Solo lo usan los archivos `config/*.php`. |
| `App\Database\Connection` | `$app->connection()->pdo()`: PDO perezoso con `ERRMODE_EXCEPTION`, `FETCH_ASSOC`, `EMULATE_PREPARES=false`, `utf8mb4`. Nunca imprime ni hace `exit`; lanza `ConnectionException` sin DSN, usuario ni contraseña. |
| `App\Http\Request` / `Response` | Lectura de GET, POST, JSON, cabeceras y método; respuestas HTML, JSON, texto y redirect interno seguro (rechaza `//host`, `/\host` y URLs absolutas). |
| `App\Bootstrap\ErrorHandler` | Estrategia de errores (ver §5). |
| `App\Logging\Logger` | Líneas JSON en `storage/logs/app-YYYY-MM-DD.log`, con claves sensibles redactadas. |
| `tests/foundation_smoke.php` | `composer smoke`: verificación sin base de datos. |

### Variables de entorno

| Variable | Default seguro | Notas |
|---|---|---|
| `APP_ENV` | `production` | `local`, `staging` o `production` |
| `APP_DEBUG` | `false` | Solo tiene efecto si `APP_ENV` es `local`, `development` o `testing`. En cualquier otro valor (incluidas erratas como `prod`) el debug queda desactivado |
| `APP_URL`, `APP_TIMEZONE` | vacío / `America/Guayaquil` | |
| `DB_HOST`, `DB_PORT` | `127.0.0.1` / `3306` | |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | vacío | Sin fallback. Si faltan, `ConnectionException` |
| `MYSQL_*` | — | **Transitorio**: nombres legacy aceptados como alternativa de `DB_*` |
| `SESSION_*`, `LOGIN_*`, `SECURITY_HSTS` | ver `config/security.php` | Se usan desde la Fase 2 |

## 4. LEGACY vs NUEVA: reglas de convivencia

1. **El legacy no se mezcla con lo nuevo.** El código nuevo vive solo en `app/`, `config/`,
   `bootstrap/`, `routes/`, `templates/` y `database/`. No se escribe lógica nueva en `admin/`,
   `backend/`, `api/` ni en los `.php` de la raíz.
2. **El legacy puede llamar a lo nuevo; lo nuevo nunca incluye archivos legacy.** Por ejemplo,
   un endpoint legacy puede pasar a hacer `require bootstrap/app.php` y delegar en un Service.
   `app/` no hace `require` de `db.php`, `backend/auth.php`, etc.
3. **Una sola conexión por request en el código nuevo:** `Application::connection()`.
   `db.php` y `backend/db.php` se mantienen solo mientras quede código legacy que los use.
4. **Las URLs públicas no cambian.** Al migrar un módulo, el archivo legacy de esa URL pasa a ser
   un *entrypoint delgado* (bootstrap + controller) o se reemplaza por una ruta del Router que
   responda en la misma URL.
5. **Se migra módulo por módulo**, y cada migración se valida con pruebas de regresión antes de
   pasar al siguiente. Cuando un archivo legacy deja de usarse, se elimina en su propio cambio.
6. **Código nuevo:** `declare(strict_types=1)`, namespace `App\...` (PSR-4), clases en PascalCase,
   métodos en camelCase, sin globals ni funciones globales nuevas, rutas con `__DIR__` o
   `Application::basePath()` en lugar de includes relativos frágiles.

### Receta para migrar un módulo

1. Crear `Repository` copiando el SQL legacy tal cual (ya parametrizado) → tests.
2. Crear `Service` con la validación y las reglas; mover el DDL de runtime a una migración.
3. Crear `Controller` que devuelva exactamente el mismo JSON o HTML que el legacy (mismo contrato).
4. Hacer que el archivo legacy delegue en el Controller (misma URL) → regresión.
5. Borrar el código legacy muerto.

## 5. Estrategia de errores

| | Desarrollo (`APP_DEBUG=true` y `APP_ENV` ∈ local/development/testing) | Producción (y cualquier otro caso) |
|---|---|---|
| `display_errors` | on | **off** |
| Warnings / notices | se registran y PHP también los muestra | solo se registran |
| Excepción no capturada | se registra; se muestra clase, mensaje y `archivo:línea` (escapado) | se registra; el usuario ve un mensaje genérico (HTML o JSON según `Accept`) |
| Error fatal | vía shutdown handler, igual que arriba | igual que arriba |
| Argumentos en trazas | incluidos | `zend.exception_ignore_args=1` |

Nunca se muestran SQL, credenciales, rutas internas ni trazas en producción. Todo el detalle va a
`storage/logs/`. Los warnings **no** se convierten en excepciones, para que las páginas legacy que
adopten el bootstrap no se rompan por deprecaciones existentes.

Los errores de conexión se modelan como `ConnectionException`, sin encadenar la `PDOException`
original: su traza incluye los argumentos del constructor de PDO (DSN, usuario y contraseña).

## 6. Propuesta de migración a `public/` como DocumentRoot (NO aplicada)

Objetivo: que solo lo que debe verse por HTTP quede bajo `public/`, y que el código, la
configuración, `.env`, los SQL, los logs y `vendor/` queden fuera del alcance web.

| Hoy (raíz) | Futuro | Cuándo |
|---|---|---|
| `index.php`, `abogado.php`, `about.php`, `contact.php`, `practice.php`, `consultar-caso.php` | `public/*.php` como entrypoints delgados (bootstrap + controller), mismas URLs | Fase 4 |
| `*.html` estáticos | `templates/public/` renderizados por controller, o `public/` si siguen estáticos | Fase 4 |
| `admin/*.php` | `public/admin/*.php` (entrypoints delgados) o rutas de `routes/admin.php` | Fase 3 |
| `backend/*.php`, `api/*.php` | `public/api/` / `public/backend/` delgados, o rutas de `routes/api.php`; se conservan las URLs actuales | Fases 3 y 4 |
| `css/`, `js/`, `fonts/`, `images/` | `public/assets/…`, con alias o reescritura para conservar las rutas `/css/…`, `/js/…` que usa el HTML | Fase 6 |
| `uploads/` | `public/uploads/`, sin ejecución de PHP (`.htaccess` / `php_flag engine off`) | Fase 4 |
| `inc/navbar.html`, `inc/footer.html` (se cargan por fetch) | `templates/components/` + endpoint o render en servidor | Fase 6 |
| `db/`, `scripts/`, `*.sql`, `README*`, `.env`, `node_modules/`, `php_server_log.txt`, `diagnose.php`, `php_test_simple.php`, `api/db_test.php` | Fuera de `public/` o eliminados | Fase 0 / 8 |

Cambio de DocumentRoot (Fase 8): apuntar el vhost o subdominio a `public/`. En cPanel sin control
del DocumentRoot, alternativa: dejar en `public_html` solo el contenido de `public/` y ubicar el
resto del proyecto un nivel arriba (`/home/<cuenta>/lawfirm/`).

Mientras tanto, cada carpeta nueva que no es pública (`app/`, `bootstrap/`, `config/`,
`database/`, `routes/`, `storage/`, `templates/`, `docs/`, `tests/`) incluye un `.htaccess` que
deniega el acceso. `vendor/` no lo incluye (lo gestiona Composer). Hasta que exista `public/`,
conviene denegar `vendor/` desde el `.htaccess` raíz o desde el panel del hosting.

## 7. Plan de fases

| Fase | Alcance |
|---|---|
| 1. Foundation | ✅ Composer/PSR-4, Config, Connection, Bootstrap, Http, Logger, estructura y documentación |
| 2. Auth + seguridad | Session, Csrf, RateLimiter, SecurityHeaders, Validator, AuthService/AdminGuard |
| 3. Módulos admin | Migrar endpoints y páginas `admin/` + `backend/` (con CSRF) |
| 4. Módulos públicos | `index`, `abogado`, formularios, chatbot, `api/` |
| 5. Base de datos | Migraciones versionadas, baseline, eliminar el DDL de runtime, usuario con privilegios mínimos |
| 6. Frontend | jQuery/Bootstrap actualizados, SRI, scripts inline → archivos (para CSP) |
| 7. Tests | Suite automatizada (unitarios de Service/Repository + HTTP de humo) |
| 8. Deploy y limpieza | DocumentRoot `public/`, eliminar legacy muerto, `node_modules`, dumps y scripts |

## 8. Deuda crítica y decisiones pendientes

- **CREDENCIAL COMPROMETIDA: HAY QUE ROTARLA.** `.env` estuvo versionado desde el primer commit
  (`3a45597`) hasta `ff2db07`, y `backend/db.php` tenía la misma contraseña como fallback. Desde el
  commit Foundation, `.env` ya no se versiona y los fallbacks del legacy se eliminaron, pero los
  valores **siguen en el historial** del remoto. Orden obligatorio:
  1. Cambiar la contraseña del usuario MySQL en el hosting.
  2. Actualizar `.env` o las variables de entorno del servidor.
  3. Solo después, evaluar la limpieza del historial (`git filter-repo`), que exige coordinar un
     force push. No se hizo en la Fase 1.
- **Aviso de despliegue.** Si el servidor se actualiza con `git pull`, este commit **borra `.env`
  del directorio de trabajo del servidor** (el archivo deja de estar versionado). Antes del pull
  hay que guardar una copia de `.env` fuera del repositorio o definir las variables `MYSQL_*` en el
  panel del hosting, y restaurarla después. Además, el legacy ya no tiene credenciales por
  defecto: sin `.env` ni variables de entorno, el sitio responde "DB connection failed".
- El servidor necesita `composer install` (o `composer dump-autoload`) para generar `vendor/`, que
  no se versiona. Hoy ninguna página legacy depende de él.
- `.env` usa todavía nombres `MYSQL_*`. Migrarlo a `DB_*` cuando el legacy deje de leerlos.
