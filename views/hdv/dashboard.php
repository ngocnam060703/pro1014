<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/hdv_model.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;

// Lấy dữ liệu thực từ database
$totalToursToday = getCountToursTodayByGuide($guide_id);
$totalTours = getCountToursByGuide($guide_id);
$completedTours = 0; // Có thể tính từ status = 'completed'
$incidentsReported = getCountIncidentsByGuide($guide_id);
$journalsCount = getCountLogsByGuide($guide_id);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background: #f5f6fa; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; }
.sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
.sidebar a:hover { background: #495057; color: #fff; }
.content { padding: 30px; }
.card-stat { border-radius: 10px; }
</style>
</head>

<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">HDV</h4>

    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_lichtrinh"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_feedback"><i class="bi bi-chat-left-text"></i> Phản hồi đánh giá</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>

    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <h3 class="mb-4">Tổng quan HDV - <?= htmlspecialchars($_SESSION['guide']['fullname'] ?? '') ?></h3>
    
    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card p-3 card-stat bg-primary text-white">
          <h6><i class="bi bi-map"></i> Tour hôm nay</h6>
          <h2><?= $totalToursToday ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-success text-white">
          <h6><i class="bi bi-journal-text"></i> Nhật ký đã gửi</h6>
          <h2><?= $journalsCount ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-info text-white">
          <h6><i class="bi bi-calendar-check"></i> Tổng tour được giao</h6>
          <h2><?= $totalTours ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-danger text-white">
          <h6><i class="bi bi-exclamation-triangle"></i> Sự cố báo cáo</h6>
          <h2><?= $incidentsReported ?></h2>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
