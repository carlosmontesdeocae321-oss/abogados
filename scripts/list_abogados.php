<?php
// scripts/list_abogados.php
// CLI helper: lista abogados en la terminal o como JSON (use --json)

require_once __DIR__ . '/../backend/db.php';
$pdo = getPDO();

try{
    $stmt = $pdo->query('SELECT id, nombre, correo, celular, area_practica, cargo, destacado FROM abogados ORDER BY nombre');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e){
    fwrite(STDERR, "Error al consultar la base de datos: " . $e->getMessage() . PHP_EOL);
    exit(1);
}

$args = isset($argv) ? $argv : [];
$jsonMode = in_array('--json', $args, true);

if($jsonMode){
    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    exit(0);
}

// Prepare a simple table view
$cols = ['id' => 4, 'nombre' => 20, 'correo' => 28, 'celular' => 14, 'area_practica' => 20, 'cargo' => 16, 'destacado' => 9];
// auto-expand widths based on content (but limit max)
foreach($rows as $r){
    foreach($cols as $k => $w){
        $len = mb_strlen(isset($r[$k]) ? $r[$k] : '');
        if($len + 1 > $cols[$k]) $cols[$k] = min($len + 1, 60);
    }
}

$fmtParts = [];
foreach($cols as $k => $w){ $fmtParts[] = "%-{$w}s"; }
$fmt = implode(' | ', $fmtParts) . PHP_EOL;

// Header
printf($fmt, 'ID', 'Nombre', 'Correo', 'Celular', 'Área', 'Cargo', 'Destacado');
// Separator
$sep = array_map(function($w){ return str_repeat('-', $w); }, $cols);
printf($fmt, ...$sep);

foreach($rows as $r){
    printf(
        $fmt,
        isset($r['id']) ? $r['id'] : '',
        isset($r['nombre']) ? $r['nombre'] : '',
        isset($r['correo']) ? $r['correo'] : '',
        isset($r['celular']) ? $r['celular'] : '',
        isset($r['area_practica']) ? $r['area_practica'] : '',
        isset($r['cargo']) ? $r['cargo'] : '',
        isset($r['destacado']) ? ($r['destacado'] ? 'SI' : 'NO') : ''
    );
}

// Summary
printf(PHP_EOL . "Total abogados: %d" . PHP_EOL, count($rows));

exit(0);
