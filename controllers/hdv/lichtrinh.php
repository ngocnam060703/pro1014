<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['guide_logged_in'])){
    header('Location: index.php?act=hdv_login'); exit;
}

$guide_id = $_SESSION['guide']['id'];
require_once 'models/hdv_model.php';

$tours = getToursByGuide($guide_id); // Lấy đầy đủ thông tin phân công

include 'views/client_hdv/lichtrinh.php';
