<?php

declare(strict_types=1);

namespace App\Http;

use InvalidArgumentException;

/**
 * Value object describing an HTTP response. Nothing is sent until send() is called,
 * which keeps controllers testable (assert on status/headers/body instead of output).
 */
final class Response
{
    /** @param array<string, string> $headers */
    public function __construct(
        private readonly string $body = '',
        private readonly int $status = 200,
        private readonly array $headers = [],
    ) {
        if ($status < 100 || $status > 599) {
            throw new InvalidArgumentException("Invalid HTTP status code: {$status}");
        }
    }

    public static function html(string $html, int $status = 200): self
    {
        return new self($html, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function json(mixed $data, int $status = 200): self
    {
        $body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        return new self($body, $status, ['Content-Type' => 'application/json; charset=utf-8']);
    }

    public static function text(string $text, int $status = 200): self
    {
        return new self($text, $status, ['Content-Type' => 'text/plain; charset=utf-8']);
    }

    /**
     * Redirect to an INTERNAL path only ("/admin/dashboard.php").
     * Rejects absolute URLs, protocol-relative ("//host") and backslash tricks ("/\host"),
     * which are the open-redirect vectors found in the legacy login.
     */
    public static function redirect(string $path, int $status = 302): self
    {
        if (!self::isSafeInternalPath($path)) {
            throw new InvalidArgumentException('Redirect target must be an internal path.');
        }
        if ($status < 300 || $status > 399) {
            throw new InvalidArgumentException("Invalid redirect status code: {$status}");
        }

        return new self('', $status, ['Location' => $path]);
    }

    public static function isSafeInternalPath(string $path): bool
    {
        return $path !== ''
            && $path[0] === '/'
            && !str_starts_with($path, '//')
            && !str_starts_with($path, '/\\')
            && !preg_match('/[\x00-\x1F\x7F\\\\]/', $path);
    }

    public function withHeader(string $name, string $value): self
    {
        if (preg_match('/[\r\n]/', $name . $value)) {
            throw new InvalidArgumentException('Header name/value cannot contain line breaks.');
        }

        return new self($this->body, $this->status, [$name => $value] + $this->headers);
    }

    public function status(): int
    {
        return $this->status;
    }

    public function body(): string
    {
        return $this->body;
    }

    /** @return array<string, string> */
    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (strcasecmp($key, $name) === 0) {
                return $value;
            }
        }

        return null;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $name => $value) {
                header($name . ': ' . $value, true);
            }
        }

        echo $this->body;
    }
}
