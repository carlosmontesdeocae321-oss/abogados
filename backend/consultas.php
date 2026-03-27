<?php
// backend/consultas.php
require_once __DIR__ . '/db.php';

// Accept POST submissions from forms and save to DB
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Normalize inputs - accept both English and Spanish field names
$name = '';
if (!empty($_POST['name'])) $name = trim($_POST['name']);
if (!empty($_POST['nombre'])) $name = trim($_POST['nombre']);
$apellido = !empty($_POST['apellido']) ? trim($_POST['apellido']) : '';
if ($apellido) $name = trim($name . ' ' . $apellido);

$email = '';
if (!empty($_POST['email'])) $email = trim($_POST['email']);

$phone = '';
if (!empty($_POST['phone'])) $phone = trim($_POST['phone']);
if (!empty($_POST['telefono'])) $phone = trim($_POST['telefono']);

$service = '';
if (!empty($_POST['service'])) $service = trim($_POST['service']);
if (!empty($_POST['serviceInput'])) $service = trim($_POST['serviceInput']);

$message = '';
if (!empty($_POST['message'])) $message = trim($_POST['message']);
if (!empty($_POST['descripcion'])) $message = trim($_POST['descripcion']);

$ciudad = !empty($_POST['ciudad']) ? trim($_POST['ciudad']) : null;

$errors = [];
if (!$name) $errors[] = 'El nombre es requerido.';
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido.';
if (!$message) $errors[] = 'El mensaje es requerido.';

// If the request expects JSON (AJAX) return JSON errors
$acceptsJson = (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) ||
               (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

if (!empty($errors)){
    if ($acceptsJson){
        http_response_code(422);
        echo json_encode(['errors' => $errors]);
    } else {
        // Redirect back with error flag (simple)
        $loc = '/consultar-caso.php?error=1';
        header('Location: ' . $loc);
    }
    exit;
}

$pdo = getPDO();
// Ensure table exists (best-effort migration for hosts without manual import)
$createSql = "CREATE TABLE IF NOT EXISTS consultas_clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefono VARCHAR(80) DEFAULT NULL,
    tipo_servicio VARCHAR(120) DEFAULT NULL,
    mensaje TEXT NOT NULL,
    ciudad VARCHAR(120) DEFAULT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (email), INDEX (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
try{
        $pdo->exec($createSql);
} catch (Exception $e){
        // log but continue; insert may still fail and will be logged
        @file_put_contents(__DIR__ . '/../php_server_log.txt', date('[Y-m-d H:i:s] ') . "create table check failed: " . $e->getMessage() . "\n", FILE_APPEND);
}

// Check whether 'ciudad' column exists to avoid prepare-time SQL errors
$hasCiudad = false;
try{
    $colRes = $pdo->query("SHOW COLUMNS FROM consultas_clientes LIKE 'ciudad'");
    if ($colRes && $colRes->rowCount() > 0) $hasCiudad = true;
} catch (Exception $e){
    // ignore, we'll handle at insert time
}

if ($hasCiudad) {
    $stmt = $pdo->prepare('INSERT INTO consultas_clientes (nombre, email, telefono, tipo_servicio, mensaje, ciudad, fecha) VALUES (?, ?, ?, ?, ?, ?, NOW())');
    $params = [$name, $email, $phone, $service, $message, $ciudad];
} else {
    $stmt = $pdo->prepare('INSERT INTO consultas_clientes (nombre, email, telefono, tipo_servicio, mensaje, fecha) VALUES (?, ?, ?, ?, ?, NOW())');
    $params = [$name, $email, $phone, $service, $message];
}
try{
    $stmt->execute($params);
    $id = $pdo->lastInsertId();
    if ($acceptsJson){
        echo json_encode(['success' => true, 'id' => $id]);
    } else {
        // Redirect back to the form with a success flag
        header('Location: /consultar-caso.php?sent=1');
    }
} catch (Exception $e){
    $errMsg = $e->getMessage();
    // If error mentions missing column 'ciudad', attempt to add it and retry with ciudad
    if (stripos($errMsg, 'unknown column') !== false && stripos($errMsg, 'ciudad') !== false){
        try{
            $pdo->exec("ALTER TABLE consultas_clientes ADD COLUMN ciudad VARCHAR(120) DEFAULT NULL");
            // prepare and retry with ciudad
            $stmt = $pdo->prepare('INSERT INTO consultas_clientes (nombre, email, telefono, tipo_servicio, mensaje, ciudad, fecha) VALUES (?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute([$name, $email, $phone, $service, $message, $ciudad]);
            $id = $pdo->lastInsertId();
            if ($acceptsJson){
                echo json_encode(['success' => true, 'id' => $id]);
            } else {
                header('Location: /consultar-caso.php?sent=1');
            }
            exit;
        } catch (Exception $e2){
            $log = date('[Y-m-d H:i:s] ') . "consultas insert error after alter: " . $e2->getMessage() . " -- POST:" . json_encode($_POST) . "\n";
            @file_put_contents(__DIR__ . '/../php_server_log.txt', $log, FILE_APPEND);
        }
    }
    // Log exception for debugging
    $log = date('[Y-m-d H:i:s] ') . "consultas insert error: " . $errMsg . " -- POST:" . json_encode($_POST) . "\n";
    @file_put_contents(__DIR__ . '/../php_server_log.txt', $log, FILE_APPEND);
    if ($acceptsJson){
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo guardar la consulta.']);
        } else {
        header('Location: /consultar-caso.php?error=1');
    }
}

?>