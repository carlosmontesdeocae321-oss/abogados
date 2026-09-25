<?php

declare(strict_types=1);

namespace App\Database;

use App\Config\Repository;
use PDO;
use PDOException;

/**
 * Official (and only) way for new code to obtain a PDO instance.
 *
 * - Lazy: the connection is opened on the first call to pdo(), never in the constructor.
 * - One instance per Connection object; the Application keeps a single Connection.
 * - Never prints, never exits: failures surface as ConnectionException to upper layers.
 *
 * Legacy code keeps using db.php / backend/db.php until each module is migrated.
 */
final class Connection
{
    private ?PDO $pdo = null;

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly string $database,
        private readonly string $username,
        #[\SensitiveParameter] private readonly string $password,
        private readonly string $charset = 'utf8mb4',
        private readonly int $timeout = 5,
    ) {
    }

    public static function fromConfig(Repository $config): self
    {
        return new self(
            host: $config->string('database.host', '127.0.0.1'),
            port: $config->int('database.port', 3306),
            database: $config->string('database.database'),
            username: $config->string('database.username'),
            password: $config->string('database.password'),
            charset: $config->string('database.charset', 'utf8mb4'),
            timeout: $config->int('database.timeout', 5),
        );
    }

    public function pdo(): PDO
    {
        return $this->pdo ??= $this->connect();
    }

    public function isConnected(): bool
    {
        return $this->pdo !== null;
    }

    /** Closes the connection; the next pdo() call reconnects. */
    public function disconnect(): void
    {
        $this->pdo = null;
    }

    private function connect(): PDO
    {
        if ($this->database === '' || $this->username === '') {
            throw ConnectionException::missingConfiguration();
        }

        try {
            return new PDO($this->dsn(), $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_TIMEOUT => $this->timeout,
            ]);
        } catch (PDOException $e) {
            $info = $e->errorInfo;
            $sqlState = is_array($info) && isset($info[0]) ? (string) $info[0] : (string) $e->getCode();
            $driverCode = is_array($info) && isset($info[1]) ? (int) $info[1] : 0;

            throw ConnectionException::fromDriver($sqlState, $driverCode);
        }
    }

    private function dsn(): string
    {
        return sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $this->host, $this->port, $this->database, $this->charset);
    }

    /** Keep credentials out of var_dump()/print_r() output. */
    public function __debugInfo(): array
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'connected' => $this->isConnected(),
        ];
    }
}
