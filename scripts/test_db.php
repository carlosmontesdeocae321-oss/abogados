<?php
require __DIR__ . '/../backend/db.php';
try{
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT 1');
    $r = $stmt->fetchColumn();
    echo "OK:$r\n";
}catch(Exception $e){
    echo "ERR:" . $e->getMessage() . "\n";
}
