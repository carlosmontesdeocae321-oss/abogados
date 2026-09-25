<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Config\Config;
use App\Config\Env;
use App\Config\Repository;
use App\Database\Connection;
use App\Logging\Logger;
use LogicException;

/**
 * Application kernel for the new architecture.
 *
 * Phase 1 responsibilities:  load environment + configuration, set timezone, register the
 *                            error handler, and own the single shared database Connection.
 * Planned (later phases):    session start, security headers, CSRF, router dispatch.
 *
 * There is intentionally no dependency-injection container yet.
 */
final class Application
{
    private static ?self $shared = null;

    private bool $booted = false;
    private ?Repository $config = null;
    private ?Logger $logger = null;
    private ?Connection $connection = null;

    public function __construct(private readonly string $basePath)
    {
    }

    /** Booted per-request instance used by bootstrap/app.php (idempotent across includes). */
    public static function shared(string $basePath): self
    {
        return self::$shared ??= (new self($basePath))->boot();
    }

    /**
     * @param bool $registerErrorHandler false only for isolated tooling/tests that must not
     *                                   replace the global PHP handlers.
     */
    public function boot(bool $registerErrorHandler = true): self
    {
        if ($this->booted) {
            return $this;
        }

        $env = Env::fromFile($this->basePath('.env'));
        $this->config = Repository::fromDirectory($this->basePath('config'), $env);
        Config::setRepository($this->config);

        date_default_timezone_set($this->config->string('app.timezone', 'UTC'));

        if ($registerErrorHandler) {
            (new ErrorHandler($this->logger(), $this->isDebug()))->register();
        }

        $this->booted = true;

        return $this;
    }

    public function basePath(string $path = ''): string
    {
        $base = rtrim($this->basePath, '/\\');

        return $path === '' ? $base : $base . '/' . ltrim($path, '/\\');
    }

    public function storagePath(string $path = ''): string
    {
        return $this->basePath('storage' . ($path === '' ? '' : '/' . ltrim($path, '/\\')));
    }

    public function config(): Repository
    {
        return $this->config ?? throw new LogicException('Application not booted.');
    }

    public function environment(): string
    {
        return $this->config()->string('app.env', 'production');
    }

    public function isDebug(): bool
    {
        return $this->config()->bool('app.debug', false);
    }

    public function logger(): Logger
    {
        if ($this->logger === null) {
            $custom = $this->config()->string('app.log_path');
            $this->logger = new Logger($custom !== '' ? $custom : $this->storagePath('logs'));
        }

        return $this->logger;
    }

    /** The single shared Connection. Opening the PDO link is deferred to Connection::pdo(). */
    public function connection(): Connection
    {
        return $this->connection ??= Connection::fromConfig($this->config());
    }
}
