<?php

declare(strict_types=1);

/*
 * Common entry point of the NEW architecture.
 *
 *     $app = require __DIR__ . '/../bootstrap/app.php';
 *     $pdo = $app->connection()->pdo();
 *
 * Legacy pages do NOT include this file yet; each one will be switched over when its module
 * is migrated (see docs/ARCHITECTURE.md). Including it more than once returns the same instance.
 */

use App\Bootstrap\Application;

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (!is_file($autoload)) {
    throw new RuntimeException('Composer autoload not found. Run "composer install" (or "composer dump-autoload").');
}
require_once $autoload;

return Application::shared(dirname(__DIR__));
