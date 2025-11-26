<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
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
    <h4 class="text-center text-light mb-4">ADMIN</h4>

    <a href="index.php?act=dashboard">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="index.php?act=account">
      <i class="bi bi-people"></i> Quản lý tài khoản
    </a>

    <a href="index.php?act=guide">
      <i class="bi bi-person-badge"></i> Quản lý nhân viên
    </a>
     
    <a href="index.php?act=schedule">
      <i class="bi bi-calendar-event"></i> Quản lý lịch trình 
    </a>
    <a href="index.php?act=service">
      <i class="bi bi-calendar-event"></i> Quản lý dịch vụ
    </a>

    <a href="index.php?act=tour">
      <i class="bi bi-card-list"></i> Quản lý Tour
    </a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <h3 class="mb-4">Tổng quan hệ thống</h3>

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card p-3 card-stat bg-primary text-white">
          <h6><i class="bi bi-people"></i> Tổng người dùng</h6>
          <h2><?= $totalUsers ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-success text-white">
          <h6><i class="bi bi-map"></i> Tổng tour</h6>
          <h2><?= $totalTours ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-warning text-dark">
          <h6><i class="bi bi-cart"></i> Đơn đặt hôm nay</h6>
          <h2><?= $ordersToday ?></h2>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card p-3 card-stat bg-danger text-white">
          <h6><i class="bi bi-currency-dollar"></i> Doanh thu hôm nay</h6>
          <h2><?= number_format($revenueToday) ?> đ</h2>
        </div>
      </div>
    </div>

    <!-- QUICK FUNCTIONS -->
    <h5>Chức năng nhanh</h5>

    <div class="row g-3">
      <div class="col-md-3">
        <a href="index.php?act=account" class="btn btn-outline-primary w-100 p-3">
          <i class="bi bi-people"></i> Quản lý tài khoản
        </a>
      </div>

      <div class="col-md-3">
        <a href="index.php?act=guide" class="btn btn-outline-info w-100 p-3">
          <i class="bi bi-person-badge"></i> Quản lý nhân viên
        </a>
      </div>

      <div class="col-md-3">
        <a href="index.php?act=schedule" class="btn btn-outline-info w-100 p-3">
          <i class="bi bi-calendar-event"></i> Quản lý lịch trình 
        </a>
      </div>
       <div class="col-md-3">
        <a href="index.php?act=service" class="btn btn-outline-info w-100 p-3">
          <i class="bi bi-calendar-event"></i> Quản lý lịch trình 
        </a>
      </div>
      

      <div class="col-md-3">
        <a href="index.php?act=tour" class="btn btn-outline-success w-100 p-3">
          <i class="bi bi-card-list"></i> Quản lý tour
        </a>
      </div>

      <div class="col-md-3">
        <a href="index.php?act=tour-create" class="btn btn-outline-warning w-100 p-3">
          <i class="bi bi-plus-circle"></i> Thêm tour mới
        </a>
      </div>
    </div>

  </div>
</div>
</body>
</html>
