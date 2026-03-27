<?php
// diagnose.php — temporary debugging helper. Remove after use.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

echo "PHP environment\n--------------\n";
if (function_exists('php_sapi_name')) echo "SAPI: " . php_sapi_name() . "\n";
if (function_exists('phpversion')) echo "PHP version: " . phpversion() . "\n";

echo "\nConfiguration\n--------------\n";
echo "display_errors=" . ini_get('display_errors') . "\n";
echo "error_log=" . ini_get('error_log') . "\n";

echo "\nFile checks\n-----------\n";
$files = [
    'inc/require_admin.php',
    'backend/auth.php',
    'admin/login.php',
    'index.php',
    'index.html'
];
foreach ($files as $f) {
    echo str_pad($f, 30) . ': ' . (file_exists(__DIR__ . '/' . $f) ? 'FOUND' : 'MISSING') . "\n";
}

echo "\nSession test\n------------\n";
try {
    session_start();
    echo "session_status=" . session_status() . "\n"; // 0 = none, 1 = active, 2 = disabled
    echo "session_id=" . session_id() . "\n";
} catch (Exception $e) {
    echo "session_start error: " . $e->getMessage() . "\n";
}

echo "\nInclude test (require backend/auth.php)\n-------------------------------------\n";
try {
    $p = __DIR__ . '/backend/auth.php';
    if (file_exists($p)) {
        // attempt to include inside a try to catch fatal errors
        include_once $p;
        echo "included backend/auth.php OK\n";
    } else {
        echo "backend/auth.php not found at $p\n";
    }
} catch (Throwable $t) {
    echo "include error: " . $t->getMessage() . "\n";
}

echo "\nEnd of diagnose\n";
