<?php
// backend/db.php
// Database connection using PDO. Configure via environment variables or a .env file in project root.

// Load .env file if present (simple parser)
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($k, $v) = explode('=', $line, 2);
        $k = trim($k); $v = trim($v);
        if ($k !== '') putenv($k . '=' . $v);
    }
}

$DB_HOST = getenv('MYSQL_HOST') !== false ? getenv('MYSQL_HOST') : 'localhost';
$DB_NAME = getenv('MYSQL_DATABASE') !== false ? getenv('MYSQL_DATABASE') : 'estudioj_lawfirm';
$DB_USER = getenv('MYSQL_USER') !== false ? getenv('MYSQL_USER') : 'estudioj_firmauser';
$DB_PASS = getenv('MYSQL_PASSWORD') !== false ? getenv('MYSQL_PASSWORD') : '1236780Ivar.@';

define('UPLOADS_PATH', __DIR__ . '/../uploads');

function getPDO(){
    static $pdo = null;
    if ($pdo) return $pdo;
    global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try {
        $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $opts);
        return $pdo;
    } catch (PDOException $e) {
        // Log detailed error for debugging
        $msg = date('[Y-m-d H:i:s] ') . "DB connection failed: " . $e->getMessage() . "\n";
        @file_put_contents(dirname(__DIR__) . '/php_server_log.txt', $msg, FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'DB connection failed']);
        exit;
    }
}

?>