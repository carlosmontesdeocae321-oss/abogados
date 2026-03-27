<?php
// scripts/list_abogados_from_seed.php
// Extrae datos de abogados desde un archivo seed SQL y los lista en terminal.

// Candidate seed files (checked in order)
$candidates = [__DIR__ . '/../db/seed_abogados_preview.sql', __DIR__ . '/../db/seed_abogados_example.sql', __DIR__ . '/../db/seed_abogados_full.sql'];
$seed = null;
foreach($candidates as $c){ if(file_exists($c)){ $seed = $c; break; } }
if(!$seed){ fwrite(STDERR, "No se encontró archivo seed de abogados en db/\n"); exit(1); }

$sql = file_get_contents($seed);
if($sql === false){ fwrite(STDERR, "Error al leer archivo seed: $seed\n"); exit(1); }

// Find the INSERT INTO abogados ... VALUES (...) ; block
$pos = stripos($sql, 'INSERT INTO abogados');
if($pos === false){ fwrite(STDERR, "No se encontró INSERT INTO abogados en $seed\n"); exit(1); }

// take the rest of the file from the INSERT position — we'll parse tuples until EOF
$insertBlock = substr($sql, $pos);

// Locate VALUES keyword
$vpos = stripos($insertBlock, 'VALUES');
if($vpos === false){ fwrite(STDERR, "No se encontró VALUES en el INSERT\n"); exit(1); }

$valuesText = substr($insertBlock, $vpos + strlen('VALUES'));

// Parser: split top-level parenthesis groups
$tuples = [];
$buf = '';
$depth = 0;
$len = strlen($valuesText);
for($i=0;$i<$len;$i++){
    $ch = $valuesText[$i];
    if($ch === '('){ $depth++; }
    if($depth > 0) $buf .= $ch;
    if($ch === ')'){
        $depth--;
        if($depth === 0){
            $tuples[] = $buf;
            $buf = '';
        }
    }
}

if(empty($tuples)){ fwrite(STDERR, "No se extrajeron tuplas del INSERT\n"); exit(1); }

// Columns order in seed (preview):
$columns = ['nombre','correo','celular','foto_carnet','foto_full','descripcion','area_practica','formacion','experiencia','docencia','publicaciones','distinciones','facebook','instagram','linkedin','twitter','whatsapp','cargo','destacado','fecha_creacion'];

$rows = [];
foreach($tuples as $t){
    // remove surrounding parentheses
    $inner = trim($t);
    if($inner[0] === '(') $inner = substr($inner,1);
    if(substr($inner,-1) === ')') $inner = substr($inner,0,-1);

    // split top-level commas (not inside quotes)
    $fields = [];
    $f = '';
    $inQuote = false;
    $len2 = strlen($inner);
    for($i=0;$i<$len2;$i++){
        $c = $inner[$i];
        if($c === "'"){
            // handle escaped '' inside SQL strings
            if($inQuote && $i+1 < $len2 && $inner[$i+1] === "'"){ $f .= "'"; $i++; continue; }
            $inQuote = !$inQuote;
            continue; // do not include quote chars
        }
        if(!$inQuote && $c === ','){
            $fields[] = trim($f);
            $f = '';
            continue;
        }
        $f .= $c;
    }
    if($f !== '') $fields[] = trim($f);

    // clean fields (strip surrounding quotes and unescape '')
    $clean = [];
    foreach($fields as $fld){
        $fld = trim($fld);
        if(strlen($fld) >= 2 && $fld[0] === "'" && substr($fld,-1) === "'"){
            $fld = substr($fld,1,-1);
            $fld = str_replace("''", "'", $fld);
        }
        // convert SQL NULL to null
        if(strtoupper($fld) === 'NULL') $fld = null;
        $clean[] = $fld;
    }

    // map to columns
    $r = [];
    for($k=0;$k<count($columns);$k++){
        $r[$columns[$k]] = isset($clean[$k]) ? $clean[$k] : null;
    }
    $rows[] = $r;
}

// Output simple table
foreach($rows as $r){
    printf("- %s (%s) — %s — %s\n", $r['nombre'] ?? '', $r['cargo'] ?? '', $r['correo'] ?? '', $r['area_practica'] ?? '');
}

printf("\nTotal abogados extraídos: %d\n", count($rows));

exit(0);
