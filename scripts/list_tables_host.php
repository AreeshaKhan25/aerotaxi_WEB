<?php
function listTables($host){
    try{
        $pdo = new PDO("mysql:host={$host};port=3306;dbname=aerotaxi", 'root', '');
        $stmt = $pdo->query("SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema='aerotaxi'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Host: {$host}\n";
        echo json_encode($tables, JSON_PRETTY_PRINT) . "\n";
    } catch (Exception $e){
        echo "Host: {$host} ERROR: " . $e->getMessage() . "\n";
    }
}
listTables('127.0.0.1');
listTables('DESKTOP-VGH1AVV');
listTables('localhost');
