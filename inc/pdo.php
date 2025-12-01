<?php
// inc/pdo.php
$DB_DSN  = 'mysql:host=localhost;dbname=travel_system;charset=utf8mb4'; // đúng DSN
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('DB Connection failed: ' . $e->getMessage());
}

// Các hàm tiện ích PDO
if (!function_exists('pdo_query')) {
    function pdo_query($sql, $params = []){
        global $pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

if (!function_exists('pdo_query_value')) {
    function pdo_query_value($sql, $params = []){
        global $pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}

if (!function_exists('pdo_execute')) {
    function pdo_execute($sql, $params = []){
        global $pdo;
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }
}