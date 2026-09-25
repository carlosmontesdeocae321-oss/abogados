<?php

declare(strict_types=1);

namespace App\Config;

use LogicException;

/**
 * The single official entry point to read configuration in new code:
 *
 *     Config::get('database.host');
 *     Config::bool('app.debug');
 *
 * The underlying Repository is installed once by App\Bootstrap\Application::boot().
 * Tests can build a Repository directly and install it with Config::setRepository().
 */
final class Config
{
    private static ?Repository $repository = null;

    private function __construct()
    {
    }

    public static function setRepository(Repository $repository): void
    {
        self::$repository = $repository;
    }

    public static function isLoaded(): bool
    {
        return self::$repository !== null;
    }

    public static function repository(): Repository
    {
        return self::$repository
            ?? throw new LogicException('Configuration not loaded. Include bootstrap/app.php first.');
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::repository()->get($key, $default);
    }

    public static function has(string $key): bool
    {
        return self::repository()->has($key);
    }

    public static function string(string $key, string $default = ''): string
    {
        return self::repository()->string($key, $default);
    }

    public static function int(string $key, int $default = 0): int
    {
        return self::repository()->int($key, $default);
    }

    public static function bool(string $key, bool $default = false): bool
    {
        return self::repository()->bool($key, $default);
    }

    /** @return array<mixed> */
    public static function array(string $key): array
    {
        return self::repository()->array($key);
    }
}
