<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Read-only access to environment variables.
 *
 * Resolution order for a key:
 *   1. Real process environment (getenv / $_ENV / $_SERVER), e.g. set by the hosting panel.
 *   2. Values parsed from the .env file (local development).
 *
 * Unlike the legacy loaders (db.php, backend/db.php) this class never calls putenv():
 * parsed values stay inside the instance and do not leak into the process environment.
 */
final class Env
{
    /** @param array<string, string> $fileValues */
    private function __construct(private readonly array $fileValues)
    {
    }

    public static function fromFile(string $path): self
    {
        if (!is_file($path) || !is_readable($path)) {
            return new self([]);
        }

        $contents = file_get_contents($path);

        return new self($contents === false ? [] : self::parse($contents));
    }

    /** @param array<string, string> $values */
    public static function fromArray(array $values): self
    {
        return new self($values);
    }

    /**
     * Minimal dotenv parser: KEY=VALUE, optional "export " prefix, # comments,
     * single or double quoted values. No variable interpolation.
     *
     * @return array<string, string>
     */
    public static function parse(string $contents): array
    {
        $values = [];
        $contents = preg_replace('/^\xEF\xBB\xBF/', '', $contents) ?? $contents;

        foreach (preg_split('/\R/', $contents) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (str_starts_with($line, 'export ')) {
                $line = ltrim(substr($line, 7));
            }

            $separator = strpos($line, '=');
            if ($separator === false) {
                continue;
            }

            $key = trim(substr($line, 0, $separator));
            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key)) {
                continue;
            }

            $values[$key] = self::parseValue(trim(substr($line, $separator + 1)));
        }

        return $values;
    }

    private static function parseValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        $quote = $value[0];
        if (($quote === '"' || $quote === "'") && strlen($value) >= 2 && str_ends_with($value, $quote)) {
            return substr($value, 1, -1);
        }

        // Unquoted: strip trailing inline comment ("value # comment").
        $commentPos = strpos($value, ' #');

        return $commentPos === false ? $value : rtrim(substr($value, 0, $commentPos));
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }
        if (isset($_ENV[$key]) && is_string($_ENV[$key])) {
            return $_ENV[$key];
        }
        if (isset($_SERVER[$key]) && is_string($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        return $this->fileValues[$key] ?? $default;
    }

    /** Returns the first defined key. Used to accept legacy variable names during the migration. */
    public function first(array $keys, ?string $default = null): ?string
    {
        foreach ($keys as $key) {
            $value = $this->get($key);
            if ($value !== null) {
                return $value;
            }
        }

        return $default;
    }

    public function string(string $key, string $default = ''): string
    {
        return $this->get($key) ?? $default;
    }

    public function int(string $key, int $default = 0): int
    {
        $value = $this->get($key);

        return $value !== null && preg_match('/^-?\d+$/', trim($value)) ? (int) trim($value) : $default;
    }

    public function bool(string $key, bool $default = false): bool
    {
        $value = $this->get($key);
        if ($value === null) {
            return $default;
        }

        return match (strtolower(trim($value))) {
            '1', 'true', 'on', 'yes' => true,
            '0', 'false', 'off', 'no', '' => false,
            default => $default,
        };
    }
}
