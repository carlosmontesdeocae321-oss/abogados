<?php

declare(strict_types=1);

/*
 * JSON/AJAX routes (public and admin data endpoints).
 *
 * NOT ACTIVE YET. Legacy endpoints stay authoritative until each module is migrated.
 * Their URLs must keep working (the frontend JS calls them directly).
 *
 * Legacy map (method URL -> current file):
 *   GET  /backend/abogados.php?action=list|get|home_cards        (Lawyers, public)
 *   POST /backend/abogados.php?action=save|save-socio|delete     (Lawyers, admin)
 *   GET  /backend/publicaciones.php?action=list|get              (Publications, public)
 *   POST /backend/publicaciones.php?action=create|update|delete  (Publications, admin)
 *   GET  /backend/faqs.php?action=list|get                       (Faqs, public)
 *   POST /backend/faqs.php?action=create|update|delete           (Faqs, admin)
 *   GET  /backend/indicators.php?action=list                     (Indicators, public)
 *   POST /backend/indicators.php?action=create|update|delete     (Indicators, admin)
 *   GET  /backend/icons.php?action=list / POST ?action=upload    (Indicators)
 *   POST /backend/citas.php                                      (Appointments, public)
 *   POST /backend/consultas.php                                  (Consultations, public)
 *   POST /backend/delete_cita.php, /backend/delete_consulta.php  (admin)
 *   GET  /api/faqs.php, POST /api/chatbot.php                    (Faqs, public)
 *   POST /api/messages.php, /api/submit-case.php                 (orphan, candidates for removal)
 *
 * Format: ['METHOD', '/path', [ControllerClass::class, 'method']]
 */

return [];
