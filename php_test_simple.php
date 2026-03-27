<?php
// php_test_simple.php — minimal test. Safe to leave but remove after debugging.
header('Content-Type: text/plain; charset=utf-8');
try {
    echo "OK\n";
    echo "PHP version: " . phpversion() . "\n";
    echo "SAPI: " . php_sapi_name() . "\n";
    // session test
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    echo "session_status=" . session_status() . "\n";
} catch (Throwable $t) {
    echo "Throwable: " . $t->getMessage() . "\n";
}
