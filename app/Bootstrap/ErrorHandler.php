<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Http\Request;
use App\Http\Response;
use App\Logging\Logger;
use ErrorException;
use Throwable;

/**
 * Central error strategy for code that runs through bootstrap/app.php.
 *
 * DEVELOPMENT (debug = true): details are logged AND shown (escaped) to ease debugging.
 * PRODUCTION  (debug = false): details only go to storage/logs; the user gets a generic message.
 *              No SQL, credentials, internal paths or stack traces are ever rendered.
 *
 * Warnings/notices are logged but NOT converted into exceptions, so legacy pages that start
 * using the bootstrap do not break on existing deprecations (e.g. htmlspecialchars(null)).
 */
final class ErrorHandler
{
    private const FATAL_ERRORS = E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR;

    private bool $registered = false;

    public function __construct(
        private readonly Logger $logger,
        private readonly bool $debug,
    ) {
    }

    public function register(): void
    {
        if ($this->registered) {
            return;
        }
        $this->registered = true;

        error_reporting(E_ALL);
        ini_set('display_errors', $this->debug ? '1' : '0');
        ini_set('display_startup_errors', $this->debug ? '1' : '0');
        ini_set('log_errors', '1');
        ini_set('zend.exception_ignore_args', $this->debug ? '0' : '1');

        set_error_handler($this->handleError(...));
        set_exception_handler($this->handleException(...));
        register_shutdown_function($this->handleShutdown(...));
    }

    public function handleError(int $level, string $message, string $file = '', int $line = 0): bool
    {
        // Respect error_reporting() and the @ operator.
        if (!(error_reporting() & $level)) {
            return false;
        }

        $this->logger->warning($message, ['severity' => $level, 'file' => $file, 'line' => $line]);

        // In debug let PHP also display it; in production swallow the output.
        return !$this->debug;
    }

    public function handleException(Throwable $e): void
    {
        $this->logger->exception($e);
        $this->render($e);
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error === null || !($error['type'] & self::FATAL_ERRORS)) {
            return;
        }

        $e = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
        $this->logger->exception($e, ['fatal' => true]);
        $this->render($e);
    }

    /** Builds the response a user sees for an unhandled error (public for testing). */
    public function responseFor(Throwable $e, bool $wantsJson): Response
    {
        $detail = $this->debug
            ? sprintf('%s: %s in %s:%d', $e::class, $e->getMessage(), $e->getFile(), $e->getLine())
            : null;

        if ($wantsJson) {
            return Response::json(
                $detail === null ? ['error' => 'Error interno del servidor.'] : ['error' => 'Error interno del servidor.', 'debug' => $detail],
                500
            );
        }

        $html = '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Error</title></head><body>'
            . '<h1>Ocurrió un error inesperado</h1><p>Por favor intente nuevamente más tarde.</p>'
            . ($detail === null ? '' : '<pre>' . htmlspecialchars($detail, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</pre>')
            . '</body></html>';

        return Response::html($html, 500);
    }

    private function render(Throwable $e): void
    {
        if (PHP_SAPI === 'cli') {
            fwrite(STDERR, $this->debug ? (string) $e . PHP_EOL : 'Unhandled error (see storage/logs).' . PHP_EOL);

            return;
        }

        $wantsJson = Request::fromGlobals()->expectsJson();
        $this->responseFor($e, $wantsJson)->send();
    }
}
