<?php

declare(strict_types=1);

namespace App\Database;

use RuntimeException;

/**
 * Raised when the PDO connection cannot be established.
 *
 * The message is generic and safe to log. The original PDOException is deliberately NOT
 * chained as "previous": its stack trace contains the PDO constructor arguments (DSN, user,
 * password). Only the SQLSTATE/driver code is kept, for diagnostics.
 */
final class ConnectionException extends RuntimeException
{
    private function __construct(string $message, private readonly string $sqlState = '', int $driverCode = 0)
    {
        parent::__construct($message, $driverCode);
    }

    public static function fromDriver(string $sqlState, int $driverCode): self
    {
        return new self(
            sprintf('Database connection failed (SQLSTATE %s, driver code %d).', $sqlState, $driverCode),
            $sqlState,
            $driverCode
        );
    }

    public static function missingConfiguration(): self
    {
        return new self('Database connection is not configured (DB_DATABASE / DB_USERNAME missing).');
    }

    public function sqlState(): string
    {
        return $this->sqlState;
    }
}
