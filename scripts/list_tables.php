<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=aerotaxi', 'root', '');
    $stmt = $pdo->query('SHOW TABLES');
    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    echo json_encode($rows, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
