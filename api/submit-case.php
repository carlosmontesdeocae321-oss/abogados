<?php
require_once __DIR__ . '/../db.php';

// Handle form POST from consultar-caso.html and insert into messages
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$ciudad = isset($_POST['ciudad']) ? trim($_POST['ciudad']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$service = isset($_POST['service']) ? trim($_POST['service']) : '';

$message = "Solicitud de asesoría\nNombre: {$nombre} {$apellido}\nTeléfono: {$telefono}\nEmail: {$email}\nCiudad: {$ciudad}\nServicio: {$service}\n\nDescripción:\n{$descripcion}";

try {
    $stmt = $pdo->prepare('INSERT INTO messages (page, message, email, telefono) VALUES (?, ?, ?, ?)');
    $stmt->execute(['case', $message, $email, $telefono]);
    // Redirect to a thank-you page
    header('Location: /won.php');
    exit;
} catch (Exception $e) {
    // fallback: show error
    http_response_code(500);
    echo 'Error al enviar la solicitud. Por favor intente más tarde.';
    exit;
}
