<?php
// backend/delete_cita.php
require_once __DIR__ . '/auth.php';
requireAdmin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0){
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

try{
    $pdo = getPDO();
    $stmt = $pdo->prepare('DELETE FROM citas_consulta WHERE id = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0){
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'No encontrado']);
    }
} catch (Exception $e){
    @file_put_contents(__DIR__ . '/../php_server_log.txt', date('[Y-m-d H:i:s] ') . "delete_cita error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'Error del servidor']);
}

?>
