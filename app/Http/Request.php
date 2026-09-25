<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Immutable snapshot of the incoming HTTP request.
 *
 * Built from superglobals in production (Request::fromGlobals()) or from plain arrays in tests.
 * New code should read input through this object instead of touching $_GET/$_POST directly.
 */
final class Request
{
    private const MAX_JSON_BYTES = 1_048_576; // 1 MiB

    /** @var array<mixed>|null */
    private ?array $json = null;
    private bool $jsonParsed = false;

    /**
     * @param array<string, mixed> $query   $_GET
     * @param array<string, mixed> $post    $_POST
     * @param array<string, mixed> $server  $_SERVER
     * @param array<string, mixed> $files   $_FILES
     * @param array<string, mixed> $cookies $_COOKIE
     */
    public function __construct(
        private readonly array $query = [],
        private readonly array $post = [],
        private readonly array $server = [],
        private readonly array $files = [],
        private readonly array $cookies = [],
        private readonly ?string $rawBody = null,
    ) {
    }

    public static function fromGlobals(): self
    {
        $isJson = str_contains(strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? '')), 'application/json');
        $body = $isJson ? file_get_contents('php://input', false, null, 0, self::MAX_JSON_BYTES + 1) : null;

        return new self($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE, $body === false ? null : $body);
    }

    public function method(): string
    {
        return strtoupper((string) ($this->server['REQUEST_METHOD'] ?? 'GET'));
    }

    public function isMethod(string $method): bool
    {
        return $this->method() === strtoupper($method);
    }

    /** Path without query string, e.g. "/admin/login.php". */
    public function path(): string
    {
        $path = parse_url((string) ($this->server['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

        return is_string($path) && $path !== '' ? $path : '/';
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /** Form field (application/x-www-form-urlencoded or multipart). */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /** Trimmed string input; arrays and missing keys become the default. */
    public function string(string $key, string $default = ''): string
    {
        $value = $this->post[$key] ?? $this->query[$key] ?? null;

        return is_scalar($value) ? trim((string) $value) : $default;
    }

    /** @return array<string, mixed> */
    public function allQuery(): array
    {
        return $this->query;
    }

    /** @return array<string, mixed> */
    public function allInput(): array
    {
        return $this->post;
    }

    /**
     * Decoded JSON body, or null when the body is absent, too large or not a JSON object/array.
     *
     * @return array<mixed>|null
     */
    public function json(): ?array
    {
        if (!$this->jsonParsed) {
            $this->jsonParsed = true;
            $body = $this->rawBody;
            if ($body !== null && $body !== '' && strlen($body) <= self::MAX_JSON_BYTES) {
                $decoded = json_decode($body, true, 64);
                $this->json = is_array($decoded) ? $decoded : null;
            }
        }

        return $this->json;
    }

    /** @return array<string, mixed>|null */
    public function file(string $key): ?array
    {
        $file = $this->files[$key] ?? null;

        return is_array($file) ? $file : null;
    }

    public function cookie(string $key): ?string
    {
        $value = $this->cookies[$key] ?? null;

        return is_string($value) ? $value : null;
    }

    public function header(string $name): ?string
    {
        $normalized = strtoupper(str_replace('-', '_', $name));
        $value = $this->server['HTTP_' . $normalized]
            ?? (in_array($normalized, ['CONTENT_TYPE', 'CONTENT_LENGTH'], true) ? ($this->server[$normalized] ?? null) : null);

        return is_string($value) ? $value : null;
    }

    public function isAjax(): bool
    {
        return strtolower((string) $this->header('X-Requested-With')) === 'xmlhttprequest';
    }

    public function expectsJson(): bool
    {
        return $this->isAjax()
            || str_contains(strtolower((string) $this->header('Accept')), 'application/json')
            || str_contains(strtolower((string) $this->header('Content-Type')), 'application/json');
    }

    public function isSecure(): bool
    {
        $https = strtolower((string) ($this->server['HTTPS'] ?? ''));

        return ($https !== '' && $https !== 'off') || (int) ($this->server['SERVER_PORT'] ?? 0) === 443;
    }

    /** Direct client address. Proxy headers are intentionally ignored (spoofable). */
    public function ip(): string
    {
        return (string) ($this->server['REMOTE_ADDR'] ?? '');
    }
}
