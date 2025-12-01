<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['guide_logged_in'])){
    header('Location: index.php?act=hdv_login');
    exit;
}

require_once "models/hdv_model.php"; // include model

$guide_id = $_SESSION['guide']['id'];
$upload_error = "";

// Xử lý upload file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])){
    if (!empty($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK){
        $tmp = $_FILES['file']['tmp_name'];
        $name = basename($_FILES['file']['name']);
        $target = __DIR__ . '/../../uploads/' . $name; // project-root/uploads

        if (move_uploaded_file($tmp, $target)){
            $desc = $_POST['description'] ?? '';
            addGuideFile($guide_id, $name, $desc);
            header('Location: index.php?act=hdv_data');
            exit;
        } else {
            $upload_error = 'Không thể lưu file.';
        }
    } else {
        $upload_error = 'Chưa chọn file hoặc lỗi upload.';
    }
}

// Lấy danh sách file của HDV
$files = getGuideFiles($guide_id);

// Load view
include 'views/client_hdv/data.php';
