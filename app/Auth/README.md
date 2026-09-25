# App\Auth

Autenticación de administradores. **Se implementa en la Fase 2.**

Reemplazará a `backend/auth.php` (legacy), que hoy sigue siendo la única implementación activa.

Contenido previsto:

- `AuthService`: login (`password_verify` + `password_needs_rehash`), logout, usuario actual.
  Comprueba en cada request que el administrador siga existiendo y activo, para que borrarlo
  cierre también su sesión.
- `AdminGuard`: equivalente a `requireAdmin()`. Responde con redirección en HTML y con 401 en JSON.
- Integración con `App\Security\RateLimiter` y `App\Security\Session`.

Regla: la autenticación vive **solo** aquí. No se duplica en páginas ni endpoints.
