<?php
// backend/abogados.php
// CRUD endpoint for abogados (requires admin for create/update/delete)

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
$pdo = getPDO();

// Allow CORS for admin AJAX if needed (optional)
// header('Access-Control-Allow-Origin: *');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';

function json_resp($ok, $data = null, $msg = ''){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('ok'=>$ok, 'data'=>$data, 'msg'=>$msg));
    exit;
}

if($action === 'list'){
    // public list
    $stmt = $pdo->query('SELECT id,nombre,correo,celular,foto,foto_carnet,foto_full,descripcion,area_practica,formacion,experiencia,docencia,publicaciones,distinciones,facebook,instagram,linkedin,twitter,whatsapp,cargo,destacado,fecha_creacion FROM abogados ORDER BY fecha_creacion DESC');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // normalize URLs so frontend receives complete links
    function normalize_url($v){
        if(!$v) return null;
        $v = trim($v);
        if($v === '') return null;
        if(strpos($v, 'http://') === 0 || strpos($v, 'https://') === 0) return $v;
        return 'https://' . $v;
    }
    function normalize_whatsapp($v){
        if(!$v) return null;
        $v = trim($v);
        if($v === '') return null;
        // if it's already a wa.me link or starts with http, return normalized http
        if(strpos($v, 'http://') === 0 || strpos($v, 'https://') === 0) return $v;
        // extract digits
        $digits = preg_replace('/[^0-9]/', '', $v);
        if($digits === '') return null;
        return 'https://wa.me/' . $digits;
    }
    foreach($rows as &$r){
        $r['facebook'] = normalize_url(isset($r['facebook']) ? $r['facebook'] : null);
        $r['instagram'] = normalize_url(isset($r['instagram']) ? $r['instagram'] : null);
        $r['linkedin'] = normalize_url(isset($r['linkedin']) ? $r['linkedin'] : null);
        $r['twitter'] = normalize_url(isset($r['twitter']) ? $r['twitter'] : null);
        $r['whatsapp'] = normalize_whatsapp(isset($r['whatsapp']) ? $r['whatsapp'] : null);
        // normalize foto_carnet and foto_full to absolute URLs so frontend can load them reliably
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $base = $scheme . '://' . $host;
        $normalize = function($foto) use ($base){
            if(!$foto) return null;
            $foto = trim($foto);
            if($foto === '') return null;
            if(strpos($foto, 'http://') === 0 || strpos($foto, 'https://') === 0) return $foto;
            if($foto[0] !== '/') $foto = '/' . $foto;
            return $base . $foto;
        };
        $r['foto_carnet'] = $normalize(isset($r['foto_carnet']) ? $r['foto_carnet'] : null);
        $r['foto_full'] = $normalize(isset($r['foto_full']) ? $r['foto_full'] : null);
        // legacy `foto` field: prefer foto_carnet, then foto, then foto_full
        $legacy = isset($r['foto']) ? trim($r['foto']) : null;
        if($r['foto_carnet']){
            $r['foto'] = $r['foto_carnet'];
        } elseif($legacy && $legacy !== ''){
            $r['foto'] = $normalize($legacy);
        } else {
            $r['foto'] = $r['foto_full'];
        }
    }
    unset($r);
    json_resp(true, $rows);
}

if($action === 'get'){
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $pdo->prepare('SELECT * FROM abogados WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){
        // normalize urls for single record as well
        function _norm_url($v){
            if(!$v) return null;
            $v = trim($v);
            if($v === '') return null;
            if(strpos($v,'http://')===0 || strpos($v,'https://')===0) return $v;
            return 'https://' . $v;
        }
        function _norm_wa($v){
            if(!$v) return null;
            $v = trim($v);
            if($v === '') return null;
            if(strpos($v,'http://')===0 || strpos($v,'https://')===0) return $v;
            $d = preg_replace('/[^0-9]/','',$v);
            if($d === '') return null;
            return 'https://wa.me/' . $d;
        }
        $row['facebook'] = _norm_url(isset($row['facebook']) ? $row['facebook'] : null);
        $row['instagram'] = _norm_url(isset($row['instagram']) ? $row['instagram'] : null);
        $row['linkedin'] = _norm_url(isset($row['linkedin']) ? $row['linkedin'] : null);
        $row['twitter'] = _norm_url(isset($row['twitter']) ? $row['twitter'] : null);
        $row['whatsapp'] = _norm_wa(isset($row['whatsapp']) ? $row['whatsapp'] : null);
        // normalize foto_carnet and foto_full to absolute urls
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $base = $scheme . '://' . $host;
        $normalize = function($f) use ($base){
            if(!$f) return null;
            $f = trim($f);
            if($f === '') return null;
            if(strpos($f,'http://')===0 || strpos($f,'https://')===0) return $f;
            if($f[0] !== '/') $f = '/'.$f;
            return $base . $f;
        };
        $row['foto_carnet'] = $normalize(isset($row['foto_carnet']) ? $row['foto_carnet'] : null);
        $row['foto_full'] = $normalize(isset($row['foto_full']) ? $row['foto_full'] : null);
        $legacy = isset($row['foto']) ? trim($row['foto']) : null;
        if($row['foto_carnet']){
            $row['foto'] = $row['foto_carnet'];
        } elseif($legacy && $legacy !== ''){
            $row['foto'] = $normalize($legacy);
        } else {
            $row['foto'] = $row['foto_full'];
        }
    }
    json_resp(true, $row);
}

if($action === 'home_cards'){
    // Return all lawyers for carousel rotation on homepage
    $stmt = $pdo->query('SELECT id,nombre,correo,celular,foto,foto_carnet,foto_full,descripcion,area_practica,formacion,experiencia,docencia,publicaciones,distinciones,facebook,instagram,linkedin,twitter,whatsapp,cargo,destacado FROM abogados ORDER BY RAND()');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // helpers
    $normalize_url = function($v){
        if(!$v) return null;
        $v = trim($v);
        if($v === '') return null;
        if(strpos($v,'http://')===0 || strpos($v,'https://')===0) return $v;
        return 'https://'.$v;
    };
    $normalize_wa = function($v){
        if(!$v) return null;
        $v = trim($v);
        if($v === '') return null;
        if(strpos($v,'http://')===0 || strpos($v,'https://')===0) return $v;
        $d = preg_replace('/[^0-9]/','',$v);
        if($d === '') return null;
        return 'https://wa.me/'.$d;
    };
    $normalize_foto = function($f){
        if(!$f) return null;
        $f = trim($f);
        if($f === '') return null;
        if(strpos($f,'http://')===0 || strpos($f,'https://')===0) return $f;
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        if($f[0] !== '/') $f = '/'.$f;
        return $scheme.'://'.$host.$f;
    };

    foreach($rows as &$r){
        $r['facebook'] = $normalize_url(isset($r['facebook']) ? $r['facebook'] : null);
        $r['instagram'] = $normalize_url(isset($r['instagram']) ? $r['instagram'] : null);
        $r['linkedin'] = $normalize_url(isset($r['linkedin']) ? $r['linkedin'] : null);
        $r['twitter'] = $normalize_url(isset($r['twitter']) ? $r['twitter'] : null);
        $r['whatsapp'] = $normalize_wa(isset($r['whatsapp']) ? $r['whatsapp'] : null);
        $r['foto_carnet'] = $normalize_foto(isset($r['foto_carnet']) ? $r['foto_carnet'] : null);
        $r['foto_full'] = $normalize_foto(isset($r['foto_full']) ? $r['foto_full'] : null);
        $legacy = isset($r['foto']) ? trim($r['foto']) : null;
        if($r['foto_carnet']){
            $r['foto'] = $r['foto_carnet'];
        } elseif($legacy && $legacy !== ''){
            $r['foto'] = $normalize_foto($legacy);
        } else {
            $r['foto'] = $r['foto_full'];
        }
    }
    unset($r);

    // return as 'random' for compatibility with frontend naming
    json_resp(true, array('featured' => array(), 'random' => $rows));

}
// below actions require admin
requireAdmin();

if($action === 'save-socio'){
    // Save/update Socio Fundador data
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if(!$id) json_resp(false, null, 'ID requerido');
    
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $celular = isset($_POST['celular']) ? trim($_POST['celular']) : null;
    $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : null;
    $area = isset($_POST['area_practica']) ? trim($_POST['area_practica']) : null;
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $facebook = isset($_POST['facebook']) ? trim($_POST['facebook']) : null;
    $instagram = isset($_POST['instagram']) ? trim($_POST['instagram']) : null;
    $linkedin = isset($_POST['linkedin']) ? trim($_POST['linkedin']) : null;
    $twitter = isset($_POST['twitter']) ? trim($_POST['twitter']) : null;
    $whatsapp = isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : null;

    // Convert empty strings to null
    $correo = $correo === '' ? null : $correo;
    $celular = $celular === '' ? null : $celular;
    $cargo = $cargo === '' ? null : $cargo;
    $area = $area === '' ? null : $area;
    $descripcion = $descripcion === '' ? null : $descripcion;
    $facebook = $facebook === '' ? null : $facebook;
    $instagram = $instagram === '' ? null : $instagram;
    $linkedin = $linkedin === '' ? null : $linkedin;
    $twitter = $twitter === '' ? null : $twitter;
    $whatsapp = $whatsapp === '' ? null : $whatsapp;

    $uploadsDir = __DIR__ . '/../uploads/abogados/';
    if(!is_dir($uploadsDir)) { @mkdir($uploadsDir, 0777, true); }

    $processFile = function($fieldName) use (&$uploadsDir){
        if(!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) return null;
        $f = $_FILES[$fieldName];
        if($f['error'] !== UPLOAD_ERR_OK){
            $uploadErrors = array(
                UPLOAD_ERR_INI_SIZE => 'Archivo muy grande.',
                UPLOAD_ERR_FORM_SIZE => 'Archivo supeara el tamaño permitido.',
                UPLOAD_ERR_PARTIAL => 'Archivo subido parcialmente.',
                UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal.',
                UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo.',
                UPLOAD_ERR_EXTENSION => 'Carga detenida por extensión PHP.'
            );
            $msg = isset($uploadErrors[$f['error']]) ? $uploadErrors[$f['error']] : 'Error al subir.';
            json_resp(false, null, $msg);
        }
        $check = @getimagesize($f['tmp_name']);
        if($check === false){ json_resp(false, null, 'No es una imagen válida.'); }
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, ['jpg','jpeg','png','gif','webp'])){ json_resp(false, null, 'Formato no soportado.'); }
        $basename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $uploadsDir . $basename;
        if(!move_uploaded_file($f['tmp_name'], $dest)){ json_resp(false, null, 'No se pudo mover el archivo.'); }
        return '/uploads/abogados/' . $basename;
    };

    $fotoFullPath = $processFile('foto_full');
    $fotoCarnetPath = $processFile('foto_carnet');

    try{
        // Fetch old photos to unlink if replaced
        $old = $pdo->prepare('SELECT foto_full, foto_carnet FROM abogados WHERE id = ?');
        $old->execute([$id]);
        $o = $old->fetch(PDO::FETCH_ASSOC);
        if($o){
            if($fotoFullPath && !empty($o['foto_full'])){ $oldFile = __DIR__ . '/..' . $o['foto_full']; if(file_exists($oldFile)) @unlink($oldFile); }
            if($fotoCarnetPath && !empty($o['foto_carnet'])){ $oldFile = __DIR__ . '/..' . $o['foto_carnet']; if(file_exists($oldFile)) @unlink($oldFile); }
        }

        // Build dynamic update based on which files were uploaded
        if($fotoFullPath && $fotoCarnetPath){
            $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, cargo=?, area_practica=?, descripcion=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=?, foto_full=?, foto_carnet=? WHERE id=?');
            $stmt->execute([$nombre, $correo, $celular, $cargo, $area, $descripcion, $facebook, $instagram, $linkedin, $twitter, $whatsapp, $fotoFullPath, $fotoCarnetPath, $id]);
        } else if($fotoFullPath){
            $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, cargo=?, area_practica=?, descripcion=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=?, foto_full=? WHERE id=?');
            $stmt->execute([$nombre, $correo, $celular, $cargo, $area, $descripcion, $facebook, $instagram, $linkedin, $twitter, $whatsapp, $fotoFullPath, $id]);
        } else if($fotoCarnetPath){
            $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, cargo=?, area_practica=?, descripcion=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=?, foto_carnet=? WHERE id=?');
            $stmt->execute([$nombre, $correo, $celular, $cargo, $area, $descripcion, $facebook, $instagram, $linkedin, $twitter, $whatsapp, $fotoCarnetPath, $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, cargo=?, area_practica=?, descripcion=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=? WHERE id=?');
            $stmt->execute([$nombre, $correo, $celular, $cargo, $area, $descripcion, $facebook, $instagram, $linkedin, $twitter, $whatsapp, $id]);
        }
        json_resp(true, null, 'Socio Fundador actualizado');
    } catch(Exception $e){ json_resp(false, null, 'Error BD: ' . $e->getMessage()); }
}

if($action === 'save'){
    // supports create and update
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $celular = isset($_POST['celular']) ? trim($_POST['celular']) : null;
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $area = isset($_POST['area_practica']) ? trim($_POST['area_practica']) : null;
    $facebook = isset($_POST['facebook']) ? trim($_POST['facebook']) : null;
    $instagram = isset($_POST['instagram']) ? trim($_POST['instagram']) : null;
    $linkedin = isset($_POST['linkedin']) ? trim($_POST['linkedin']) : null;
    $twitter = isset($_POST['twitter']) ? trim($_POST['twitter']) : null;
    $whatsapp = isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : null;
    $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : null;
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $formacion = isset($_POST['formacion']) ? trim($_POST['formacion']) : null;
    $experiencia = isset($_POST['experiencia']) ? trim($_POST['experiencia']) : null;
    $docencia = isset($_POST['docencia']) ? trim($_POST['docencia']) : null;
    $publicaciones = isset($_POST['publicaciones']) ? trim($_POST['publicaciones']) : null;
    $distinciones = isset($_POST['distinciones']) ? trim($_POST['distinciones']) : null;

    $correo = $correo === '' ? null : $correo;
    $celular = $celular === '' ? null : $celular;
    $descripcion = $descripcion === '' ? null : $descripcion;
    $area = $area === '' ? null : $area;
    $facebook = $facebook === '' ? null : $facebook;
    $instagram = $instagram === '' ? null : $instagram;
    $linkedin = $linkedin === '' ? null : $linkedin;
    $twitter = $twitter === '' ? null : $twitter;
    $whatsapp = $whatsapp === '' ? null : $whatsapp;
    $cargo = $cargo === '' ? null : $cargo;
    $formacion = $formacion === '' ? null : $formacion;
    $experiencia = $experiencia === '' ? null : $experiencia;
    $docencia = $docencia === '' ? null : $docencia;
    $publicaciones = $publicaciones === '' ? null : $publicaciones;
    $distinciones = $distinciones === '' ? null : $distinciones;

    // Handle uploads for foto_carnet and foto_full
    $fotoCarnetPath = null;
    $fotoFullPath = null;
    $uploadsDir = __DIR__ . '/../uploads/abogados/';
    if(!is_dir($uploadsDir)) { @mkdir($uploadsDir, 0777, true); }
    if(!is_dir($uploadsDir) || !is_writable($uploadsDir)){
        json_resp(false, null, 'La carpeta de subidas no es escribible: ' . $uploadsDir);
    }

    $processFile = function($fieldName) use (&$uploadsDir){
        if(!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) return null;
        $f = $_FILES[$fieldName];
        if($f['error'] !== UPLOAD_ERR_OK){
            $uploadErrors = array(
                UPLOAD_ERR_INI_SIZE => 'El archivo supera el límite permitido por el servidor.',
                UPLOAD_ERR_FORM_SIZE => 'El archivo supera el tamaño permitido por el formulario.',
                UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente.',
                UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal del servidor.',
                UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en el disco.',
                UPLOAD_ERR_EXTENSION => 'La carga fue detenida por una extensión de PHP.'
            );
            $msg = isset($uploadErrors[$f['error']]) ? $uploadErrors[$f['error']] : 'Error al subir el archivo.';
            json_resp(false, null, $msg);
        }
        $check = @getimagesize($f['tmp_name']);
        if($check === false){ json_resp(false, null, 'El archivo subido no es una imagen válida.'); }
        // Límite de tamaño removido - sin restricción de tamaño
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, ['jpg','jpeg','png','gif','webp'])){ json_resp(false, null, 'Formato de imagen no soportado.'); }
        $basename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $uploadsDir . $basename;
        if(!move_uploaded_file($f['tmp_name'], $dest)){
            $diag = array(
                'field' => $fieldName,
                'tmp_name' => isset($f['tmp_name']) ? $f['tmp_name'] : null,
                'tmp_exists' => isset($f['tmp_name']) ? file_exists($f['tmp_name']) : false,
                'is_uploaded_file' => isset($f['tmp_name']) ? is_uploaded_file($f['tmp_name']) : false,
                'uploads_dir' => $uploadsDir,
                'uploads_writable' => is_dir($uploadsDir) ? is_writable($uploadsDir) : false
            );
            json_resp(false, null, 'No fue posible mover el archivo subido. Destino: ' . $dest . ' — Diagnóstico: ' . json_encode($diag));
        }
        return '/uploads/abogados/' . $basename;
    };

    $fotoCarnetPath = $processFile('foto_carnet');
    $fotoFullPath = $processFile('foto_full');

    try{
        if($id){
            // update
            // fetch old fotos to unlink if replaced
            $old = $pdo->prepare('SELECT foto, foto_carnet, foto_full FROM abogados WHERE id = ?');
            $old->execute([$id]);
            $o = $old->fetch(PDO::FETCH_ASSOC);
            if($o){
                if($fotoCarnetPath && !empty($o['foto_carnet'])){ $oldFile = __DIR__ . '/..' . $o['foto_carnet']; if(file_exists($oldFile)) @unlink($oldFile); }
                if($fotoFullPath && !empty($o['foto_full'])){ $oldFile = __DIR__ . '/..' . $o['foto_full']; if(file_exists($oldFile)) @unlink($oldFile); }
            }
            // build dynamic update depending on which files were uploaded
            if($fotoCarnetPath && $fotoFullPath){
                $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, foto_carnet=?, foto_full=?, descripcion=?, area_practica=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=?, cargo=?, destacado=?, formacion=?, experiencia=?, docencia=?, publicaciones=?, distinciones=? WHERE id=?');
                $stmt->execute([$nombre,$correo,$celular,$fotoCarnetPath,$fotoFullPath,$descripcion,$area,$facebook,$instagram,$linkedin,$twitter,$whatsapp,$cargo,$destacado,$formacion,$experiencia,$docencia,$publicaciones,$distinciones,$id]);
            } else if($fotoCarnetPath){
                $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, foto_carnet=?, descripcion=?, area_practica=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=?, cargo=?, destacado=?, formacion=?, experiencia=?, docencia=?, publicaciones=?, distinciones=? WHERE id=?');
                $stmt->execute([$nombre,$correo,$celular,$fotoCarnetPath,$descripcion,$area,$facebook,$instagram,$linkedin,$twitter,$whatsapp,$cargo,$destacado,$formacion,$experiencia,$docencia,$publicaciones,$distinciones,$id]);
            } else if($fotoFullPath){
                $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, foto_full=?, descripcion=?, area_practica=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=?, cargo=?, destacado=?, formacion=?, experiencia=?, docencia=?, publicaciones=?, distinciones=? WHERE id=?');
                $stmt->execute([$nombre,$correo,$celular,$fotoFullPath,$descripcion,$area,$facebook,$instagram,$linkedin,$twitter,$whatsapp,$cargo,$destacado,$formacion,$experiencia,$docencia,$publicaciones,$distinciones,$id]);
            } else {
                $stmt = $pdo->prepare('UPDATE abogados SET nombre=?, correo=?, celular=?, descripcion=?, area_practica=?, facebook=?, instagram=?, linkedin=?, twitter=?, whatsapp=?, cargo=?, destacado=?, formacion=?, experiencia=?, docencia=?, publicaciones=?, distinciones=? WHERE id=?');
                $stmt->execute([$nombre,$correo,$celular,$descripcion,$area,$facebook,$instagram,$linkedin,$twitter,$whatsapp,$cargo,$destacado,$formacion,$experiencia,$docencia,$publicaciones,$distinciones,$id]);
            }
            json_resp(true, null, 'Actualizado');
        } else {
            $stmt = $pdo->prepare('INSERT INTO abogados (nombre, correo, celular, foto_carnet, foto_full, descripcion, area_practica, facebook, instagram, linkedin, twitter, whatsapp, cargo, destacado, formacion, experiencia, docencia, publicaciones, distinciones) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$nombre,$correo,$celular,$fotoCarnetPath,$fotoFullPath,$descripcion,$area,$facebook,$instagram,$linkedin,$twitter,$whatsapp,$cargo,$destacado,$formacion,$experiencia,$docencia,$publicaciones,$distinciones]);
            json_resp(true, ['id' => $pdo->lastInsertId()], 'Creado');
        }
    } catch(Exception $e){ json_resp(false, null, 'Error BD: ' . $e->getMessage()); }
}

if($action === 'delete'){
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if(!$id) json_resp(false,null,'ID inválido');
    try{
        $old = $pdo->prepare('SELECT foto, foto_carnet, foto_full FROM abogados WHERE id = ?');
        $old->execute([$id]);
        $o = $old->fetch(PDO::FETCH_ASSOC);
        if($o){
            if(!empty($o['foto'])){ $oldFile = __DIR__ . '/..' . $o['foto']; if(file_exists($oldFile)) @unlink($oldFile); }
            if(!empty($o['foto_carnet'])){ $oldFile = __DIR__ . '/..' . $o['foto_carnet']; if(file_exists($oldFile)) @unlink($oldFile); }
            if(!empty($o['foto_full'])){ $oldFile = __DIR__ . '/..' . $o['foto_full']; if(file_exists($oldFile)) @unlink($oldFile); }
        }
        $stmt = $pdo->prepare('DELETE FROM abogados WHERE id = ?');
        $stmt->execute([$id]);
        json_resp(true,null,'Eliminado');
    }catch(Exception $e){ json_resp(false,null,'Error: '.$e->getMessage()); }
}

json_resp(false,null,'Acción desconocida');
