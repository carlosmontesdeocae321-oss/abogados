<?php

declare(strict_types=1);

use App\Config\Env;

// Database settings. Credentials come ONLY from the environment (hosting panel or local .env).
// There is intentionally no fallback password or username.
//
// Transitional: the legacy code (db.php, backend/db.php) reads MYSQL_* variables. Until the
// .env files are migrated, the DB_* names are preferred and MYSQL_* are accepted as fallback.
return static function (Env $env): array {
    return [
        'driver' => 'mysql',
        'host' => $env->first(['DB_HOST', 'MYSQL_HOST'], '127.0.0.1'),
        'port' => (int) $env->first(['DB_PORT', 'MYSQL_PORT'], '3306'),
        'database' => $env->first(['DB_DATABASE', 'MYSQL_DATABASE'], ''),
        'username' => $env->first(['DB_USERNAME', 'MYSQL_USER'], ''),
        'password' => $env->first(['DB_PASSWORD', 'MYSQL_PASSWORD'], ''),
        'charset' => 'utf8mb4',
        'collation' => $env->string('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'timeout' => $env->int('DB_TIMEOUT', 5),
    ];
};
