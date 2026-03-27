<?php
// backend/citas.php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$name = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$tipo = trim($_POST['tipo_consulta'] ?? ($_POST['tipo'] ?? 'General'));
$fecha_pref = trim($_POST['fecha_preferida'] ?? '');
$hora_pref = trim($_POST['hora_preferida'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? $_POST['descripcion'] ?? '');

$errors = [];
if (!$name) $errors[] = 'El nombre es requerido.';
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido.';

$acceptsJson = (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) ||
               (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

if ($errors){
    http_response_code(422);
    echo json_encode(['errors' => $errors]);
    exit;
}

$pdo = getPDO();
// Ensure table exists
$create = "CREATE TABLE IF NOT EXISTS citas_consulta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    telefono VARCHAR(120) DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    tipo_consulta VARCHAR(120) DEFAULT NULL,
    fecha_preferida DATE DEFAULT NULL,
    hora_preferida VARCHAR(30) DEFAULT NULL,
    mensaje TEXT DEFAULT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
try{ $pdo->exec($create); } catch(Exception $e){ @file_put_contents(__DIR__ . '/../php_server_log.txt', date('[Y-m-d H:i:s] ') . "create citas table failed: " . $e->getMessage() . "\n", FILE_APPEND); }

try{
    $stmt = $pdo->prepare('INSERT INTO citas_consulta (nombre, telefono, email, tipo_consulta, fecha_preferida, hora_preferida, mensaje, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
    $stmt->execute([$name, $telefono, $email, $tipo, $fecha_pref ?: null, $hora_pref ?: null, $mensaje]);
    $id = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e){
    @file_put_contents(__DIR__ . '/../php_server_log.txt', date('[Y-m-d H:i:s] ') . "citas insert error: " . $e->getMessage() . " -- POST:" . json_encode($_POST) . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo guardar la cita.']);
}

?>
