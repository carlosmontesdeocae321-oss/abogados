# Alfonso Jimenez & Asociados - Sitio Web Legal

Sitio web corporativo para firma legal, con frontend publico, chatbot, formularios de contacto/consulta, y panel administrativo para gestionar contenido.

## Stack del proyecto

- Frontend: HTML5, CSS3, Bootstrap, jQuery
- Backend principal: PHP
- Base de datos: MySQL (via PDO)
- API/servicios: endpoints PHP en carpeta `api/` y `backend/`
- Recursos adicionales: scripts Node.js para soporte de esquema/seed

## Estructura principal

- `index.php` pagina principal
- `about.php`, `contact.php`, `practice.php` paginas principales del sitio
- `educacion-continua.html`, `servicios-judiciales.html`, `consultar-caso.php` secciones especiales
- `admin/` panel administrativo
- `api/` endpoints de API (chatbot, mensajes, FAQs, casos)
- `backend/` endpoints CRUD y logica de administracion
- `db/` scripts SQL (schema, alters, seeds, dumps)
- `css/`, `js/`, `images/`, `fonts/` recursos estaticos
- `uploads/` archivos subidos

## Requisitos

- PHP 8.0+
- MySQL 5.7+ o MariaDB equivalente
- Node.js 18+ (opcional, para scripts auxiliares)

## Configuracion local

1. Clonar el repositorio.
2. Crear base de datos en MySQL.
3. Importar esquema SQL principal:

```bash
mysql -u root -p < db/schema.sql
```

4. (Opcional) Cargar datos de ejemplo:

```bash
mysql -u root -p < db/seed_abogados_full.sql
```

5. Configurar credenciales de base de datos en:

- `db.php`
- `backend/db.php`

6. Levantar servidor PHP en la raiz del proyecto:

```bash
php -S localhost:8000
```

7. Abrir en navegador:

- `http://localhost:8000/index.php`

## Endpoints principales (referencia)

- `GET /api/faqs.php` lista FAQs
- `POST /api/chatbot.php` respuestas del chatbot
- `POST /api/messages.php` guarda mensajes
- `POST /api/submit-case.php` envio de casos
- `GET /backend/abogados.php?action=list` lista abogados

## Funcionalidades

- Sitio institucional responsive
- Seccion de equipo legal con tarjetas dinamicas
- Chatbot integrado con FAQs
- Formularios de contacto y consulta legal
- Panel admin para gestion de:
  - abogados
  - FAQs
  - publicaciones
  - usuarios
  - indicadores

## Notas

- El proyecto actualmente incluye `node_modules/` versionado en el repositorio.
- Para produccion, se recomienda servir con Apache/Nginx + PHP-FPM y configurar variables sensibles fuera del codigo.

## Licencia

Uso interno / privado de la firma, salvo indicacion distinta del propietario del repositorio.
