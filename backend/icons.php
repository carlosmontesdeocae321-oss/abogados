<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=utf-8');

$action = trim($_REQUEST['action'] ?? 'list');
// icons directory (relative to project root)
$iconsDir = __DIR__ . '/../images/icons/indicators';
$iconsUrlBase = '/images/icons/indicators/';

if ($action === 'list') {
    $files = [];
    if (is_dir($iconsDir)) {
        foreach (scandir($iconsDir) as $f) {
            if ($f === '.' || $f === '..') continue;
            if (is_file($iconsDir . '/' . $f) && preg_match('/\.svg$/i', $f)) {
                $files[] = ['file' => $f, 'url' => $iconsUrlBase . rawurlencode($f)];
            }
        }
    }
    echo json_encode($files);
    exit;
}

// upload requires admin
requireAdmin();

if ($action === 'upload') {
    if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No file uploaded']);
        exit;
    }
    $f = $_FILES['file'];
    $name = basename($f['name']);
    // sanitize name
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '-', $name);
    if (!preg_match('/\.svg$/i', $name)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Only SVG allowed']);
        exit;
    }
    if (!is_dir($iconsDir)) mkdir($iconsDir, 0755, true);
    $target = $iconsDir . '/' . $name;
    // basic MIME check
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $f['tmp_name']);
    finfo_close($finfo);
    if ($mime !== 'image/svg+xml') {
        // allow some svg-like types but be strict
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid MIME type']);
        exit;
    }
    if (!move_uploaded_file($f['tmp_name'], $target)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Move failed']);
        exit;
    }
    echo json_encode(['success' => true, 'file' => $name, 'url' => $iconsUrlBase . rawurlencode($name)]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
exit;
