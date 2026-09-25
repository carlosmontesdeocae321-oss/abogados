<?php

declare(strict_types=1);

use App\Config\Env;

// General application settings. No secrets here: every value comes from the environment.
// Defaults are the SAFE ones (production, debug off).
return static function (Env $env): array {
    $environment = strtolower($env->string('APP_ENV', 'production'));

    return [
        'name' => $env->string('APP_NAME', 'Estudio Jimenez & Asociados'),
        'env' => $environment,
        // Debug is only possible in an explicit allowlist of non-production environments, so a
        // typo ("prod", "produccion") or any unknown value can never enable it by accident.
        'debug' => in_array($environment, ['local', 'development', 'testing'], true) && $env->bool('APP_DEBUG', false),
        'url' => rtrim($env->string('APP_URL', ''), '/'),
        'timezone' => $env->string('APP_TIMEZONE', 'America/Guayaquil'),
        'log_path' => $env->string('APP_LOG_PATH', ''), // empty = storage/logs
    ];
};
