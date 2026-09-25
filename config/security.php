<?php

declare(strict_types=1);

use App\Config\Env;

// Security settings consumed by App\Security\* and App\Auth\* (implemented in Phase 2).
// Declared now so that every future component reads its knobs from one place.
return static function (Env $env): array {
    $isProduction = strtolower($env->string('APP_ENV', 'production')) === 'production';

    return [
        'session' => [
            'name' => $env->string('SESSION_NAME', 'lawfirm_session'),
            // Secure cookies by default in production; can be disabled locally over plain http.
            'secure' => $env->bool('SESSION_SECURE_COOKIE', $isProduction),
            'same_site' => $env->string('SESSION_SAME_SITE', 'Lax'),
            'idle_timeout' => $env->int('SESSION_IDLE_TIMEOUT', 1800),        // seconds
            'absolute_timeout' => $env->int('SESSION_ABSOLUTE_TIMEOUT', 28800), // seconds
        ],
        'csrf' => [
            'field' => '_token',
            'header' => 'X-CSRF-Token',
        ],
        'login_throttle' => [
            'max_attempts' => $env->int('LOGIN_MAX_ATTEMPTS', 5),
            'decay_seconds' => $env->int('LOGIN_DECAY_SECONDS', 900),
        ],
        'hsts' => $env->bool('SECURITY_HSTS', false), // enable only once HTTPS is confirmed
    ];
};
