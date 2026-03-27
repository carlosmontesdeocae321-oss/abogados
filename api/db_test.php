<?php
// DB test script — uses shared connection from ../db.php
require_once __DIR__ . '/../db.php';

try {
    // $pdo is created in db.php
    $stmt = $pdo->query('SELECT 1');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => true, 'msg' => 'Connected to DB']);
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
