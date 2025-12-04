<?php
// Giả sử bạn đã session_start() trong index.php
// Dữ liệu demo
$totalToursToday = 3;
$totalBookingsToday = 5;
$completedTours = 10;
$incidentsReported = 2;
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
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>

    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <h3 class="mb-4">Tổng quan HDV</h3>

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card p-3 card-stat bg-primary text-white">
          <h6><i class="bi bi-map"></i> Tour hôm nay</h6>
          <h2><?= $totalToursToday ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-success text-white">
          <h6><i class="bi bi-cart"></i> Booking hôm nay</h6>
          <h2><?= $totalBookingsToday ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-warning text-dark">
          <h6><i class="bi bi-check-circle"></i> Tour hoàn thành</h6>
          <h2><?= $completedTours ?></h2>
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

</body>
</html>
