<?php

declare(strict_types=1);

namespace App\Config;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Immutable configuration store addressed with dot notation ("database.host").
 *
 * Each file in config/ returns a closure `fn (Env $env): array`; the file name
 * becomes the first segment of the key (config/database.php -> "database.*").
 */
final class Repository
{
    /** @param array<string, mixed> $items */
    public function __construct(private readonly array $items)
    {
    }

    public static function fromDirectory(string $directory, Env $env): self
    {
        $items = [];

        foreach (glob(rtrim($directory, '/\\') . '/*.php') ?: [] as $file) {
            $definition = require $file;
            if (!$definition instanceof \Closure) {
                throw new UnexpectedValueException(sprintf('Config file "%s" must return a closure.', basename($file)));
            }

            $values = $definition($env);
            if (!is_array($values)) {
                throw new UnexpectedValueException(sprintf('Config file "%s" must produce an array.', basename($file)));
            }

            $items[basename($file, '.php')] = $values;
        }

        return new self($items);
    }

    public function has(string $key): bool
    {
        $this->lookup($key, $found);

        return $found;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->lookup($key, $found);

        return $found ? $value : $default;
    }

    public function string(string $key, string $default = ''): string
    {
        $value = $this->get($key, $default);

        return is_scalar($value) ? (string) $value : $default;
    }

    public function int(string $key, int $default = 0): int
    {
        $value = $this->get($key, $default);

        return is_int($value) ? $value : (is_numeric($value) ? (int) $value : $default);
    }

    public function bool(string $key, bool $default = false): bool
    {
        $value = $this->get($key, $default);

        return is_bool($value) ? $value : $default;
    }

    /** @return array<mixed> */
    public function array(string $key): array
    {
        $value = $this->get($key, []);

        return is_array($value) ? $value : [];
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->items;
    }

    private function lookup(string $key, ?bool &$found): mixed
    {
        if ($key === '') {
            throw new InvalidArgumentException('Config key cannot be empty.');
        }

        $found = false;
        $value = $this->items;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }
        $found = true;

        return $value;
    }
}
