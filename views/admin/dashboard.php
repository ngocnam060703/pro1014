<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình </a>
    <a href="index.php?act=service"><i class="bi bi-calendar-event"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>

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

    <div class="row g-3 mb-5">
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


    <!-- ========================= BIỂU ĐỒ ========================= -->
    <h5 class="mt-4">Thống kê trực quan</h5>

    <div class="row g-4 mt-1">

      <!-- Biểu đồ doanh thu -->
      <div class="col-md-6">
        <div class="card p-3">
          <h6 class="text-center">Doanh thu 7 ngày gần đây</h6>
          <canvas id="chartRevenue"></canvas>
        </div>
      </div>

      <!-- Biểu đồ số đơn -->
      <div class="col-md-6">
        <div class="card p-3">
          <h6 class="text-center">Số đơn theo ngày</h6>
          <canvas id="chartOrders"></canvas>
        </div>
      </div>

    </div>

  </div>
</div>

<!-- ========================= SCRIPT BIỂU ĐỒ ========================= -->
<script>
// Biểu đồ doanh thu (mặc định dữ liệu mẫu)
const ctx1 = document.getElementById('chartRevenue');

new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: [1200000, 1500000, 900000, 2000000, 1800000, 3000000, 2500000],
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    }
});

// Biểu đồ số đơn
const ctx2 = document.getElementById('chartOrders');

new Chart(ctx2, {
    type: 'line',
    data: {
        labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        datasets: [{
            label: 'Đơn hàng',
            data: [12, 9, 15, 8, 20, 18, 25],
            borderWidth: 2,
            borderColor: 'rgba(255, 99, 132, 1)'
        }]
    }
});
</script>

</body>
</html>
