<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['guide_logged_in'])){
header('Location: index.php?act=hdv_login'); exit;
}
$guide_id = $_SESSION['guide']['id'];


$count_tours = countTours($guide_id);
$count_logs = countLogs($guide_id);


include 'views/client_hdv/home.php';