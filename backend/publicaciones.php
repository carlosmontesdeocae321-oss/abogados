<?php
// backend/publicaciones.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$pdo = getPDO();
$returnTo = trim($_POST['return_to'] ?? '');

if (in_array($action, ['create', 'update', 'delete'], true)) {
    requireAdmin();
}

$appendQueryParam = function($url, $key, $value) {
    $sep = (strpos($url, '?') === false) ? '?' : '&';
    return $url . $sep . rawurlencode($key) . '=' . rawurlencode($value);
};

if ($action === 'list'){
    $stmt = $pdo->query('SELECT id, titulo, descripcion, imagen, categoria, fecha FROM publicaciones ORDER BY fecha DESC');
    $rows = $stmt->fetchAll();
    header('Content-Type: application/json');
    echo json_encode($rows);
    exit;
}

if ($action === 'get' && !empty($_GET['id'])){
    $stmt = $pdo->prepare('SELECT * FROM publicaciones WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $row = $stmt->fetch();
    echo json_encode($row);
    exit;
}

if ($action === 'create'){
    // expects multipart/form-data
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $fecha = trim($_POST['fecha'] ?? date('Y-m-d'));
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $autor = trim($_SESSION['admin_name'] ?? '');
    if ($autor === '') $autor = trim($_SESSION['admin_email'] ?? '');
    if ($autor === '') $autor = 'Administrador';
    $estado = trim($_POST['estado'] ?? 'Publicado');
    $etiquetas = trim($_POST['etiquetas'] ?? '');
    $tiempoLectura = trim($_POST['tiempo_lectura'] ?? '');
    $fuenteUrl = trim($_POST['fuente_url'] ?? '');
    $imagenAlt = trim($_POST['imagen_alt'] ?? '');

    if (!$titulo) { http_response_code(422); echo json_encode(['error'=>'Título requerido']); exit; }

    // Persist additional editorial metadata inside descripcion to avoid DB schema changes.
    $metaLines = [];
    if ($subtitulo !== '') $metaLines[] = 'Subtitulo: ' . $subtitulo;
    if ($autor !== '') $metaLines[] = 'Autor: ' . $autor;
    if ($estado !== '') $metaLines[] = 'Estado: ' . $estado;
    if ($etiquetas !== '') $metaLines[] = 'Etiquetas: ' . $etiquetas;
    if ($tiempoLectura !== '') $metaLines[] = 'Tiempo de lectura: ' . $tiempoLectura;
    if ($fuenteUrl !== '') $metaLines[] = 'Fuente: ' . $fuenteUrl;
    if ($imagenAlt !== '') $metaLines[] = 'Alt imagen: ' . $imagenAlt;
    $imagenPath = null;
    $mediaPaths = [];

    $saveMedia = function($tmpName, $originalName, $size) use (&$imagenPath, &$mediaPaths){
        $ext = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext === '') return;

        $imageExt = ['jpg','jpeg','png','webp','gif'];
        $videoExt = ['mp4','webm','mov','m4v'];
        $kind = null;
        if (in_array($ext, $imageExt, true)) $kind = 'image';
        if (in_array($ext, $videoExt, true)) $kind = 'video';
        if ($kind === null) {
            throw new Exception('Tipo de archivo no permitido: ' . $originalName);
        }

        if ($kind === 'image' && $size > 5 * 1024 * 1024) {
            throw new Exception('La imagen excede 5MB: ' . $originalName);
        }
        if ($kind === 'video' && $size > 50 * 1024 * 1024) {
            throw new Exception('El video excede 50MB: ' . $originalName);
        }

        $subdir = $kind === 'image' ? 'imagenes' : 'videos';
        $prefix = $kind === 'image' ? 'img_' : 'vid_';
        $destDir = rtrim(UPLOADS_PATH, '/') . '/' . $subdir . '/';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);

        $fn = $prefix . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $destDir . $fn;
        if (!move_uploaded_file($tmpName, $dest)) {
            throw new Exception('No se pudo guardar: ' . $originalName);
        }

        $webPath = 'uploads/' . $subdir . '/' . $fn;
        $mediaPaths[] = $kind . ':' . $webPath;
        // Keep first valid media as preview in publicaciones.imagen
        if ($imagenPath === null) {
            $imagenPath = $webPath;
        }

        return $kind;
    };

    $imageCount = 0;
    $videoCount = 0;

    // New field: multiple media files
    if (!empty($_FILES['medios']) && isset($_FILES['medios']['name']) && is_array($_FILES['medios']['name'])) {
        $count = count($_FILES['medios']['name']);
        for($i = 0; $i < $count; $i++){
            $err = $_FILES['medios']['error'][$i] ?? UPLOAD_ERR_NO_FILE;
            if ($err === UPLOAD_ERR_NO_FILE) continue;
            if ($err !== UPLOAD_ERR_OK) {
                http_response_code(422); echo json_encode(['error'=>'Error al subir archivo multimedia']); exit;
            }
            try {
                $kind = $saveMedia(
                    $_FILES['medios']['tmp_name'][$i],
                    $_FILES['medios']['name'][$i],
                    (int)($_FILES['medios']['size'][$i] ?? 0)
                );
                if ($kind === 'image') $imageCount++;
                if ($kind === 'video') $videoCount++;
            } catch(Exception $e){
                http_response_code(422); echo json_encode(['error'=>$e->getMessage()]); exit;
            }
        }
    }

    // Backward compatibility (single file)
    if ($imageCount === 0 && $videoCount === 0) {
        if (!empty($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK){
            try {
                $kind = $saveMedia($_FILES['media']['tmp_name'], $_FILES['media']['name'], (int)$_FILES['media']['size']);
                if ($kind === 'image') $imageCount++;
                if ($kind === 'video') $videoCount++;
            } catch(Exception $e){
                http_response_code(422); echo json_encode(['error'=>$e->getMessage()]); exit;
            }
        } elseif (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            try {
                $kind = $saveMedia($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name'], (int)$_FILES['imagen']['size']);
                if ($kind === 'image') $imageCount++;
                if ($kind === 'video') $videoCount++;
            } catch(Exception $e){
                http_response_code(422); echo json_encode(['error'=>$e->getMessage()]); exit;
            }
        }
    }

    // Business rule: several images OR 1 video (no mix)
    if ($videoCount > 1) {
        http_response_code(422); echo json_encode(['error'=>'Solo se permite un video por publicacion']); exit;
    }
    if ($videoCount === 1 && $imageCount > 0) {
        http_response_code(422); echo json_encode(['error'=>'No se puede mezclar video con imagenes en la misma publicacion']); exit;
    }
    if (!empty($metaLines)) {
        $descripcion = "[META]\n" . implode("\n", $metaLines) . "\n[/META]\n\n" . $descripcion;
    }

    if (!empty($mediaPaths)) {
        $metaMedia = "Media: " . implode(' | ', $mediaPaths);
        // add media paths to existing meta block or append if no block exists
        if (strpos($descripcion, "[META]\n") === 0) {
            $descripcion = preg_replace('/\[\/META\]/', $metaMedia . "\n[/META]", $descripcion, 1);
        } else {
            $descripcion = "[META]\n" . $metaMedia . "\n[/META]\n\n" . $descripcion;
        }
    }

    $stmt = $pdo->prepare('INSERT INTO publicaciones (titulo, descripcion, imagen, categoria, fecha) VALUES (?, ?, ?, ?, ?)');
    try{
        $stmt->execute([$titulo, $descripcion, $imagenPath, $categoria, $fecha]);
        if ($returnTo !== '') {
            $target = $appendQueryParam($returnTo, 'notice', 'created');
            header('Location: ' . $target);
            exit;
        }
        echo json_encode(['success'=>true,'id'=>$pdo->lastInsertId()]);
    } catch(Exception $e){ http_response_code(500); echo json_encode(['error'=>'No se pudo crear publicación']); }
    exit;
}

if ($action === 'update'){
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(422);
        echo json_encode(['error' => 'ID de publicación inválido']);
        exit;
    }

    $stmtCurrent = $pdo->prepare('SELECT id, imagen FROM publicaciones WHERE id = ?');
    $stmtCurrent->execute([$id]);
    $current = $stmtCurrent->fetch();
    if (!$current) {
        http_response_code(404);
        echo json_encode(['error' => 'Publicación no encontrada']);
        exit;
    }

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $fecha = trim($_POST['fecha'] ?? date('Y-m-d'));
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $autor = trim($_SESSION['admin_name'] ?? '');
    if ($autor === '') $autor = trim($_SESSION['admin_email'] ?? '');
    if ($autor === '') $autor = 'Administrador';
    $estado = trim($_POST['estado'] ?? 'Publicado');
    $etiquetas = trim($_POST['etiquetas'] ?? '');
    $tiempoLectura = trim($_POST['tiempo_lectura'] ?? '');
    $fuenteUrl = trim($_POST['fuente_url'] ?? '');
    $imagenAlt = trim($_POST['imagen_alt'] ?? '');

    if (!$titulo) {
        http_response_code(422);
        echo json_encode(['error' => 'Título requerido']);
        exit;
    }

    $metaLines = [];
    if ($subtitulo !== '') $metaLines[] = 'Subtitulo: ' . $subtitulo;
    if ($autor !== '') $metaLines[] = 'Autor: ' . $autor;
    if ($estado !== '') $metaLines[] = 'Estado: ' . $estado;
    if ($etiquetas !== '') $metaLines[] = 'Etiquetas: ' . $etiquetas;
    if ($tiempoLectura !== '') $metaLines[] = 'Tiempo de lectura: ' . $tiempoLectura;
    if ($fuenteUrl !== '') $metaLines[] = 'Fuente: ' . $fuenteUrl;
    if ($imagenAlt !== '') $metaLines[] = 'Alt imagen: ' . $imagenAlt;

    $imagenPath = !empty($current['imagen']) ? (string)$current['imagen'] : null;
    $mediaPaths = [];

    $saveMedia = function($tmpName, $originalName, $size) use (&$imagenPath, &$mediaPaths){
        $ext = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext === '') return;

        $imageExt = ['jpg','jpeg','png','webp','gif'];
        $videoExt = ['mp4','webm','mov','m4v'];
        $kind = null;
        if (in_array($ext, $imageExt, true)) $kind = 'image';
        if (in_array($ext, $videoExt, true)) $kind = 'video';
        if ($kind === null) {
            throw new Exception('Tipo de archivo no permitido: ' . $originalName);
        }

        if ($kind === 'image' && $size > 5 * 1024 * 1024) {
            throw new Exception('La imagen excede 5MB: ' . $originalName);
        }
        if ($kind === 'video' && $size > 50 * 1024 * 1024) {
            throw new Exception('El video excede 50MB: ' . $originalName);
        }

        $subdir = $kind === 'image' ? 'imagenes' : 'videos';
        $prefix = $kind === 'image' ? 'img_' : 'vid_';
        $destDir = rtrim(UPLOADS_PATH, '/') . '/' . $subdir . '/';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);

        $fn = $prefix . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $destDir . $fn;
        if (!move_uploaded_file($tmpName, $dest)) {
            throw new Exception('No se pudo guardar: ' . $originalName);
        }

        $webPath = 'uploads/' . $subdir . '/' . $fn;
        $mediaPaths[] = $kind . ':' . $webPath;
        if ($imagenPath === null || !empty($mediaPaths)) {
            $imagenPath = $webPath;
        }

        return $kind;
    };

    $imageCount = 0;
    $videoCount = 0;

    if (!empty($_FILES['medios']) && isset($_FILES['medios']['name']) && is_array($_FILES['medios']['name'])) {
        $count = count($_FILES['medios']['name']);
        for($i = 0; $i < $count; $i++){
            $err = $_FILES['medios']['error'][$i] ?? UPLOAD_ERR_NO_FILE;
            if ($err === UPLOAD_ERR_NO_FILE) continue;
            if ($err !== UPLOAD_ERR_OK) {
                http_response_code(422); echo json_encode(['error'=>'Error al subir archivo multimedia']); exit;
            }
            try {
                $kind = $saveMedia(
                    $_FILES['medios']['tmp_name'][$i],
                    $_FILES['medios']['name'][$i],
                    (int)($_FILES['medios']['size'][$i] ?? 0)
                );
                if ($kind === 'image') $imageCount++;
                if ($kind === 'video') $videoCount++;
            } catch(Exception $e){
                http_response_code(422); echo json_encode(['error'=>$e->getMessage()]); exit;
            }
        }
    }

    if ($imageCount === 0 && $videoCount === 0) {
        if (!empty($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK){
            try {
                $kind = $saveMedia($_FILES['media']['tmp_name'], $_FILES['media']['name'], (int)$_FILES['media']['size']);
                if ($kind === 'image') $imageCount++;
                if ($kind === 'video') $videoCount++;
            } catch(Exception $e){
                http_response_code(422); echo json_encode(['error'=>$e->getMessage()]); exit;
            }
        } elseif (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            try {
                $kind = $saveMedia($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name'], (int)$_FILES['imagen']['size']);
                if ($kind === 'image') $imageCount++;
                if ($kind === 'video') $videoCount++;
            } catch(Exception $e){
                http_response_code(422); echo json_encode(['error'=>$e->getMessage()]); exit;
            }
        }
    }

    if ($videoCount > 1) {
        http_response_code(422); echo json_encode(['error'=>'Solo se permite un video por publicacion']); exit;
    }
    if ($videoCount === 1 && $imageCount > 0) {
        http_response_code(422); echo json_encode(['error'=>'No se puede mezclar video con imagenes en la misma publicacion']); exit;
    }

    if (!empty($metaLines)) {
        $descripcion = "[META]\n" . implode("\n", $metaLines) . "\n[/META]\n\n" . $descripcion;
    }

    if (!empty($mediaPaths)) {
        $metaMedia = 'Media: ' . implode(' | ', $mediaPaths);
        if (strpos($descripcion, "[META]\n") === 0) {
            $descripcion = preg_replace('/\[\/META\]/', $metaMedia . "\n[/META]", $descripcion, 1);
        } else {
            $descripcion = "[META]\n" . $metaMedia . "\n[/META]\n\n" . $descripcion;
        }
    }

    $stmt = $pdo->prepare('UPDATE publicaciones SET titulo = ?, descripcion = ?, imagen = ?, categoria = ?, fecha = ? WHERE id = ?');
    try{
        $stmt->execute([$titulo, $descripcion, $imagenPath, $categoria, $fecha, $id]);
        if ($returnTo !== '') {
            $target = $appendQueryParam($returnTo, 'notice', 'updated');
            header('Location: ' . $target);
            exit;
        }
        echo json_encode(['success'=>true,'id'=>$id]);
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(['error'=>'No se pudo actualizar publicación']);
    }
    exit;
}

if ($action === 'delete' && !empty($_POST['id'])){
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare('DELETE FROM publicaciones WHERE id = ?');
    try{
        $stmt->execute([$id]);
        if ($returnTo !== '') {
            $target = $appendQueryParam($returnTo, 'notice', 'deleted');
            header('Location: ' . $target);
            exit;
        }
        echo json_encode(['success'=>true]);
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(['error'=>'No se pudo eliminar']);
    }
    exit;
}

http_response_code(400);
echo json_encode(['error'=>'Acción inválida']);

?>