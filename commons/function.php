<?php
// Load environment config if not already loaded
if (!defined('PATH_ROOT')) {
    require_once __DIR__ . '/env.php';
}

// -------------------------------
// KẾT NỐI DATABASE
// -------------------------------
function connectDB() {
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
                        DB_USERNAME, DB_PASSWORD);

        // Báo lỗi dạng exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Trả về dữ liệu dạng mảng kết hợp
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}


// -------------------------------
// WRAPPER CHUẨN CHO PDO
// -------------------------------

// Lấy kết nối
function pdo_get_connection() {
    return connectDB();
}

// Thực thi INSERT, UPDATE, DELETE
function pdo_execute($sql, ...$params) {
    $conn = pdo_get_connection();
    $stmt = $conn->prepare($sql);
    return $stmt->execute($params);
}

// Truy vấn SELECT trả về nhiều dòng
function pdo_query($sql, ...$params) {
    $conn = pdo_get_connection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Truy vấn SELECT trả về 1 dòng
function pdo_query_one($sql, ...$params) {
    $conn = pdo_get_connection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

// Truy vấn SELECT trả về 1 giá trị (1 cột)
function pdo_query_value($sql, ...$params) {
    $conn = pdo_get_connection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}


// -------------------------------
// UPLOAD FILE
// -------------------------------
function uploadFile($file, $folderSave) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return null;
    }
    
    $file_upload = $file;
    $pathStorage = $folderSave . rand(10000, 99999) . '_' . $file_upload['name'];

    $tmp_file = $file_upload['tmp_name'];
    $pathSave = PATH_ROOT . $pathStorage;
    
    // Tạo thư mục nếu chưa tồn tại
    $dir = dirname($pathSave);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    if (move_uploaded_file($tmp_file, $pathSave)) {
        return $pathStorage;
    }
    return null;
}


// -------------------------------
// XÓA FILE
// -------------------------------
function deleteFile($file) {
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete);
    }
}
