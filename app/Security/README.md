# App\Security

Componentes transversales de seguridad. **Se implementan en la Fase 2.** En la Fase 1 no se
crearon clases vacías a propósito: aquí solo queda definido qué va en esta carpeta y qué contrato
tendrá cada pieza.

| Clase prevista | Responsabilidad | Configuración |
|---|---|---|
| `Session` | Iniciar la sesión con cookie `HttpOnly`, `Secure`, `SameSite`, `use_strict_mode`; timeout de inactividad y absoluto; regenerar el ID. | `security.session.*` |
| `Csrf` | Generar y verificar el token por sesión (campo `_token` o cabecera `X-CSRF-Token`) con `hash_equals`. | `security.csrf.*` |
| `RateLimiter` | Limitar intentos por clave (IP + email en el login, IP en formularios públicos). | `security.login_throttle.*` |
| `SecurityHeaders` | CSP (primero en Report-Only), `nosniff`, `Referrer-Policy`, `frame-ancestors`, HSTS opcional. | `security.hsts` |
| `Validator` | Validar entrada (obligatorio, longitud máxima, email, URL `https?://`, entero, fecha). | — |

La redirección segura a rutas internas ya existe en `App\Http\Response::redirect()`.
