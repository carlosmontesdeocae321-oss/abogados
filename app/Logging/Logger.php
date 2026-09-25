<?php

declare(strict_types=1);

namespace App\Logging;

use Throwable;

/**
 * Minimal file logger writing one JSON line per entry to storage/logs/app-YYYY-MM-DD.log.
 *
 * - Context keys that look like secrets are redacted.
 * - Request bodies must never be passed as context (legacy code logged $_POST with personal data).
 * - Falls back to PHP's error_log() if the directory is not writable.
 */
final class Logger
{
    private const SENSITIVE_KEY = '/pass|pwd|secret|token|cookie|session|authorization|dsn/i';

    public function __construct(private readonly string $directory)
    {
    }

    /** @param array<string, mixed> $context */
    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    /** @param array<string, mixed> $context */
    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    /** @param array<string, mixed> $context */
    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    /** @param array<string, mixed> $context */
    public function exception(Throwable $e, array $context = []): void
    {
        $this->error($e->getMessage(), $context + [
            'exception' => $e::class,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    /** @param array<string, mixed> $context */
    public function log(string $level, string $message, array $context = []): void
    {
        $line = json_encode([
            'time' => date('c'),
            'level' => $level,
            'message' => $message,
            'context' => $this->redact($context),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR);

        $file = rtrim($this->directory, '/\\') . '/app-' . date('Y-m-d') . '.log';
        if (!is_dir($this->directory) || @file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX) === false) {
            error_log('[lawfirm] ' . $line);
        }
    }

    /**
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    private function redact(array $context): array
    {
        foreach ($context as $key => $value) {
            if (is_string($key) && preg_match(self::SENSITIVE_KEY, $key)) {
                $context[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $context[$key] = $this->redact($value);
            } elseif (is_object($value)) {
                $context[$key] = $value::class;
            }
        }

        return $context;
    }
}
