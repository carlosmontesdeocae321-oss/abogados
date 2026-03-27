<?php
// Load .env file if present so this root db.php uses the same credentials as backend/db.php
$envFile = __DIR__ . '/.env';
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

// Database connection using PDO. Configure via environment variables or .env.
$DB_HOST = getenv('MYSQL_HOST') !== false ? getenv('MYSQL_HOST') : 'localhost';
$DB_NAME = getenv('MYSQL_DATABASE') !== false ? getenv('MYSQL_DATABASE') : 'estudioj_lawfirm';
$DB_USER = getenv('MYSQL_USER') !== false ? getenv('MYSQL_USER') : 'estudioj_firmauser';
$DB_PASS = getenv('MYSQL_PASSWORD') !== false ? getenv('MYSQL_PASSWORD') : '';

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (Exception $e) {
    // Log the error for host-side debugging
    $msg = date('[Y-m-d H:i:s] ') . "root db.php connection error: " . $e->getMessage() . "\n";
    @file_put_contents(__DIR__ . '/php_server_log.txt', $msg, FILE_APPEND);
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'DB connection error']);
    exit;
}

// helper to output JSON
function json_out($data) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

// NOTE: Database credentials should be provided via environment variables
// (MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE) or a `.env`
// file kept outside the webroot. Do NOT include plaintext credentials
// inside this file. No closing PHP tag to avoid accidental output.
