<?php

declare(strict_types=1);

/*
 * Administrative panel routes (HTML pages behind authentication).
 *
 * NOT ACTIVE YET. When active, every route in this file will be wrapped by the Phase 2
 * authentication + CSRF middleware, so no admin page can forget its guard.
 *
 * Legacy map (URL -> current file) to migrate in Phase 3:
 *   /admin/login.php           (Auth)
 *   /backend/logout.php        (Auth)
 *   /admin/dashboard.php       (Consultations, Appointments, Faqs, Lawyers)
 *   /admin/abogados.php        (Lawyers)
 *   /admin/publicaciones.php   (Publications)
 *   /admin/faqs.php            (Faqs)
 *   /admin/indicators.php      (Indicators)
 *   /admin/usuarios.php        (AdminUsers)
 *
 * Format: ['METHOD', '/path', [ControllerClass::class, 'method']]
 */

return [];
