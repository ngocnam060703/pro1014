<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['guide_logged_in'])){
header('Location: index.php?act=hdv_login'); exit;
}
$guide_id = $_SESSION['guide']['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_log'])){
$tour_id = (int)$_POST['tour_id'];
$content = trim($_POST['content']);
if ($content !== ''){
addLog($tour_id, $guide_id, $content);
header('Location: index.php?act=hdv_nhatky'); exit;
}
}


$logs = getLogsByGuide($guide_id);
$tours = getToursByGuide($guide_id);
include 'views/client_hdv/nhatky.php';