<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

// Simple JSON API to list and update institutional indicators.
// Routes: ?action=list, POST ?action=update (id, value)

header('Content-Type: application/json; charset=utf-8');
try {
    $pdo = getPDO();
    // ensure table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS indicators (
        id INT AUTO_INCREMENT PRIMARY KEY,
        label VARCHAR(255) NOT NULL,
        value INT NOT NULL DEFAULT 0,
        icon VARCHAR(128) DEFAULT NULL,
        ord INT DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB error: ' . $e->getMessage()]);
    exit;
}

$action = trim($_REQUEST['action'] ?? 'list');
if ($action === 'list') {
    $stmt = $pdo->query('SELECT id, label, value, icon, ord FROM indicators ORDER BY ord, id');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // if empty, insert and return default indicators so they become manageable from admin
    if (!$rows) {
        $defaults = [
            ['label' => 'Abogados calificados', 'value' => 25, 'icon' => 'fa-user-tie', 'ord' => 1],
            ['label' => 'Clientes confiables', 'value' => 340, 'icon' => 'fa-handshake-angle', 'ord' => 2],
            ['label' => 'Casos exitosos', 'value' => 800, 'icon' => 'fa-scale-balanced', 'ord' => 3],
            ['label' => 'Años de experiencia', 'value' => 220, 'icon' => 'fa-award', 'ord' => 4],
        ];
        $ins = $pdo->prepare('INSERT INTO indicators (label, value, icon, ord) VALUES (?, ?, ?, ?)');
        foreach ($defaults as $d) {
            try { $ins->execute([$d['label'], $d['value'], $d['icon'], $d['ord']]); } catch (Exception $e) { /* ignore */ }
        }
        $stmt = $pdo->query('SELECT id, label, value, icon, ord FROM indicators ORDER BY ord, id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    echo json_encode($rows);
    exit;
}

// The following actions require admin session
requireAdmin();

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $value = (int)($_POST['value'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid id']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE indicators SET value = ? WHERE id = ?');
    $ok = $stmt->execute([$value, $id]);
    echo json_encode(['success' => (bool)$ok]);
    exit;
}

if ($action === 'create') {
    $label = trim($_POST['label'] ?? '');
    $value = (int)($_POST['value'] ?? 0);
    $icon = trim($_POST['icon'] ?? '');
    if ($label === '') { echo json_encode(['success' => false, 'error' => 'Label required']); exit; }
    $stmt = $pdo->prepare('INSERT INTO indicators (label, value, icon, ord) VALUES (?, ?, ?, (SELECT COALESCE(MAX(ord),0)+1 FROM indicators))');
    $ok = $stmt->execute([$label, $value, $icon]);
    echo json_encode(['success' => (bool)$ok, 'id' => $pdo->lastInsertId()]);
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { echo json_encode(['success' => false, 'error' => 'Invalid id']); exit; }
    $stmt = $pdo->prepare('DELETE FROM indicators WHERE id = ?');
    $ok = $stmt->execute([$id]);
    echo json_encode(['success' => (bool)$ok]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
exit;
