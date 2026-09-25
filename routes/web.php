<?php

declare(strict_types=1);

/*
 * Public web routes (HTML pages).
 *
 * NOT ACTIVE YET: there is no Router in Phase 1. Every URL below is still served by its legacy
 * file and MUST keep working with the same address when it is migrated (no public URL changes).
 *
 * Legacy map (URL -> current file) to migrate in Phase 4:
 *   /, /index.php            -> index.php                  (Lawyers, Indicators)
 *   /abogado.php?id={id}     -> abogado.php                (Lawyers)
 *   /about.php               -> about.php -> about.html    (Lawyers)
 *   /practice.php            -> practice.php + practice.html
 *   /contact.php             -> contact.php + contact.html (Consultations)
 *   /consultar-caso.php      -> consultar-caso.php + .html (Consultations)
 *   /won.html, /educacion-continua.html, /servicios-judiciales.html (static)
 *
 * Format that the future Router will consume (kept intentionally simple):
 *   ['GET', '/path', [ControllerClass::class, 'method']]
 */

return [];
