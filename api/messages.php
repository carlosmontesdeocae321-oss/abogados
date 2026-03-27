<?php
require_once __DIR__ . '/../db.php';

// accept JSON or form
$data = json_decode(file_get_contents('php://input'), true);
$page = $data['page'] ?? $_POST['page'] ?? 'site';
$message = $data['message'] ?? $_POST['message'] ?? '';
$email = $data['email'] ?? $_POST['email'] ?? null;
$telefono = $data['telefono'] ?? $_POST['telefono'] ?? null;

if (!$message) {
    http_response_code(400);
    json_out(['error' => 'Missing message']);
}

try {
    $stmt = $pdo->prepare('INSERT INTO messages (page, message, email, telefono) VALUES (?, ?, ?, ?)');
    $stmt->execute([$page, $message, $email, $telefono]);
    $id = $pdo->lastInsertId();
    json_out(['id' => (int)$id, 'page' => $page]);
} catch (Exception $e) {
    http_response_code(500);
    json_out(['error' => 'DB error']);
}
