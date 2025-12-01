<?php
// models/Database.php
// =========================
// CẤU HÌNH DATABASE
// =========================

function db_connect() {
    $db_host = '127.0.0.1';
    $db_name = 'your_database';   // ⚠ ĐỔI THÀNH TÊN DB CỦA BẠN
    $db_user = 'root';
    $db_pass = '';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (Exception $e) {
        die('DB Connection error: ' . $e->getMessage());
    }
}

// =========================
// QUERY HELPER
// =========================

// Lấy nhiều dòng
function db_query($sql, ...$params) {
    $pdo = db_connect();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Lấy 1 dòng
function db_query_one($sql, ...$params) {
    $pdo = db_connect();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

// INSERT / UPDATE / DELETE
function db_execute($sql, ...$params) {
    $pdo = db_connect();
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}