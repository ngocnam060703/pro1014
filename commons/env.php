<?php 

// Base URL
define('BASE_URL', 'http://localhost/duan1/');

// Database config
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'travel_system');

define('PATH_ROOT', __DIR__ . '/../');

// -------------------------
// Tạo kết nối Database
// -------------------------

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

if ($conn->connect_errno) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Optional: báo OK khi debug
// echo "Database connected OK<br>";
