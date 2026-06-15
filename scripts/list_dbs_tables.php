<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $dbs = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
    $result = [];
    foreach ($dbs as $db) {
        $stmt = $pdo->query("SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = '".$db."'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $result[$db] = $tables;
    }
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
