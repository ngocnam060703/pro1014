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
    body { 
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .sidebar { 
      height: 100vh; 
      background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
      padding-top: 20px; 
      position: fixed;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    .sidebar a { 
      color: #ecf0f1; 
      padding: 15px 20px; 
      display: block; 
      text-decoration: none; 
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }
    .sidebar a:hover { 
      background: rgba(255,255,255,0.1); 
      color: #fff; 
      border-left: 3px solid #3498db;
      transform: translateX(5px);
    }
    .sidebar a.active {
      background: rgba(52, 152, 219, 0.2);
      border-left: 3px solid #3498db;
      color: #fff;
    }
    .content { 
      padding: 30px; 
      margin-left: 16.666667%;
    }
    .welcome-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 20px;
      padding: 30px;
      color: white;
      margin-bottom: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .stat-card {
      border-radius: 15px;
      padding: 25px;
      color: white;
      margin-bottom: 20px;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      transition: all 0.5s;
    }
    .stat-card:hover::before {
      top: -30%;
      right: -30%;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .stat-card .icon {
      font-size: 3rem;
      opacity: 0.3;
      position: absolute;
      right: 20px;
      top: 20px;
    }
    .stat-card .number {
      font-size: 2.5rem;
      font-weight: bold;
      margin: 10px 0;
    }
    .stat-card .label {
      font-size: 0.9rem;
      opacity: 0.9;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .card-modern {
      border-radius: 15px;
      border: none;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: all 0.3s;
      margin-bottom: 20px;
      background: white;
    }
    .card-modern:hover {
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      transform: translateY(-3px);
    }
    .card-modern .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 15px 15px 0 0 !important;
      padding: 15px 20px;
      font-weight: 600;
    }
    .quick-action-btn {
      border-radius: 12px;
      padding: 20px;
      font-weight: 600;
      transition: all 0.3s;
      border: none;
      text-decoration: none;
      display: block;
      color: white;
    }
    .quick-action-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      color: white;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }
    .chart-container {
      position: relative;
      height: 300px;
      padding: 20px;
    }
  </style>
</head>

<body>
<div class="row g-0">
  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4 fw-bold">ADMIN</h4>
    <a href="index.php?act=dashboard" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    
    <!-- Welcome Card -->
    <div class="welcome-card fade-in">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h2 class="mb-2"><i class="bi bi-shield-check"></i> Chào mừng, <?= htmlspecialchars($_SESSION['user']['full_name'] ?? 'Admin') ?>!</h2>
          <p class="mb-0 opacity-75">Hôm nay là <?= date('d/m/Y') ?> - <?php 
            $dayNames = ['Monday' => 'Thứ Hai', 'Tuesday' => 'Thứ Ba', 'Wednesday' => 'Thứ Tư', 
                        'Thursday' => 'Thứ Năm', 'Friday' => 'Thứ Sáu', 'Saturday' => 'Thứ Bảy', 'Sunday' => 'Chủ Nhật'];
            echo $dayNames[date('l')] ?? date('l');
          ?></p>
        </div>
        <div class="text-end">
          <i class="bi bi-graph-up-arrow" style="font-size: 4rem; opacity: 0.3;"></i>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4 fade-in">
      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <i class="bi bi-people icon"></i>
          <div class="label"><i class="bi bi-person-check"></i> Tổng người dùng</div>
          <div class="number"><?= $totalUsers ?></div>
          <small>Người dùng hệ thống</small>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
          <i class="bi bi-map icon"></i>
          <div class="label"><i class="bi bi-geo-alt"></i> Tổng tour</div>
          <div class="number"><?= $totalTours ?></div>
          <small>Tour đang quản lý</small>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
          <i class="bi bi-cart icon"></i>
          <div class="label"><i class="bi bi-cart-check"></i> Đơn đặt hôm nay</div>
          <div class="number"><?= $ordersToday ?></div>
          <small>Đơn mới hôm nay</small>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
          <i class="bi bi-currency-dollar icon"></i>
          <div class="label"><i class="bi bi-cash-coin"></i> Doanh thu hôm nay</div>
          <div class="number"><?= number_format($revenueToday / 1000000, 1) ?>M</div>
          <small><?= number_format($revenueToday) ?> đ</small>
        </div>
      </div>
    </div>

    <!-- Quick Functions -->
    <div class="row g-3 mb-4 fade-in">
      <div class="col-12">
        <h5 class="text-white mb-3"><i class="bi bi-lightning-charge"></i> Thao tác nhanh</h5>
      </div>
      <div class="col-md-3">
        <a href="index.php?act=account" class="quick-action-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <i class="bi bi-people" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
          Quản lý tài khoản
        </a>
      </div>
      <div class="col-md-3">
        <a href="index.php?act=guide" class="quick-action-btn" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
          <i class="bi bi-person-badge" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
          Quản lý nhân viên
        </a>
      </div>
      <div class="col-md-3">
        <a href="index.php?act=tour" class="quick-action-btn" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
          <i class="bi bi-card-list" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
          Quản lý tour
        </a>
      </div>
      <div class="col-md-3">
        <a href="index.php?act=tour-create" class="quick-action-btn" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
          <i class="bi bi-plus-circle" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
          Thêm tour mới
        </a>
      </div>
    </div>

    <!-- Charts -->
    <div class="row g-3 fade-in">
      <div class="col-12">
        <h5 class="text-white mb-3"><i class="bi bi-bar-chart"></i> Thống kê trực quan</h5>
      </div>

      <!-- Biểu đồ doanh thu -->
      <div class="col-md-6">
        <div class="card card-modern">
          <div class="card-header">
            <i class="bi bi-graph-up"></i> Doanh thu 7 ngày gần đây
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartRevenue"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Biểu đồ số đơn -->
      <div class="col-md-6">
        <div class="card card-modern">
          <div class="card-header">
            <i class="bi bi-cart-check"></i> Số đơn theo ngày
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartOrders"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Biểu đồ phân bố trạng thái -->
      <div class="col-md-6">
        <div class="card card-modern">
          <div class="card-header">
            <i class="bi bi-pie-chart"></i> Phân bố trạng thái booking
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartStatus"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Top tour bán chạy -->
      <div class="col-md-6">
        <div class="card card-modern">
          <div class="card-header">
            <i class="bi bi-trophy"></i> Top 5 tour bán chạy
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartTopTours"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ========================= SCRIPT BIỂU ĐỒ ========================= -->
<script>
<?php
// Chuẩn bị dữ liệu cho biểu đồ doanh thu
$revenueLabels = [];
$revenueValues = [];
$last7Days = [];

// Tạo mảng 7 ngày gần đây
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dayName = date('D', strtotime($date));
    $dayNameVi = ['Mon' => 'T2', 'Tue' => 'T3', 'Wed' => 'T4', 'Thu' => 'T5', 'Fri' => 'T6', 'Sat' => 'T7', 'Sun' => 'CN'][$dayName] ?? date('d/m', strtotime($date));
    $last7Days[$date] = ['label' => $dayNameVi, 'revenue' => 0, 'orders' => 0];
}

// Điền dữ liệu doanh thu
if (!empty($revenueData)) {
    foreach ($revenueData as $row) {
        $date = date('Y-m-d', strtotime($row['date']));
        if (isset($last7Days[$date])) {
            $last7Days[$date]['revenue'] = (float)$row['revenue'];
        }
    }
}

// Điền dữ liệu số đơn
if (!empty($ordersData)) {
    foreach ($ordersData as $row) {
        $date = date('Y-m-d', strtotime($row['date']));
        if (isset($last7Days[$date])) {
            $last7Days[$date]['orders'] = (int)$row['count'];
        }
    }
}

// Tạo mảng labels và values
foreach ($last7Days as $day) {
    $revenueLabels[] = $day['label'];
    $revenueValues[] = $day['revenue'];
}

// Dữ liệu số đơn
$ordersValues = array_column($last7Days, 'orders');

// Dữ liệu phân bố trạng thái
$statusLabels = [];
$statusValues = [];
$statusColors = [
    'confirmed' => 'rgba(40, 167, 69, 0.8)',
    'pending' => 'rgba(255, 193, 7, 0.8)',
    'cancelled' => 'rgba(220, 53, 69, 0.8)',
    'completed' => 'rgba(23, 162, 184, 0.8)'
];

if (!empty($statusDistribution)) {
    foreach ($statusDistribution as $row) {
        $statusLabels[] = ucfirst($row['status']);
        $statusValues[] = (int)$row['count'];
    }
} else {
    $statusLabels = ['Chưa có dữ liệu'];
    $statusValues = [0];
}

// Dữ liệu top tours
$topTourLabels = [];
$topTourValues = [];
if (!empty($topTours)) {
    foreach ($topTours as $tour) {
        $title = htmlspecialchars($tour['title'] ?? 'N/A');
        $topTourLabels[] = mb_substr($title, 0, 20) . (mb_strlen($title) > 20 ? '...' : '');
        $topTourValues[] = (int)($tour['booking_count'] ?? 0);
    }
} else {
    $topTourLabels = ['Chưa có dữ liệu'];
    $topTourValues = [0];
}
?>

// Biểu đồ doanh thu
const ctx1 = document.getElementById('chartRevenue');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?= json_encode($revenueLabels) ?>,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: <?= json_encode($revenueValues) ?>,
            backgroundColor: 'rgba(102, 126, 234, 0.6)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' đ';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN', {notation: 'compact'}).format(value);
                    }
                }
            }
        }
    }
});

// Biểu đồ số đơn
const ctx2 = document.getElementById('chartOrders');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: <?= json_encode($revenueLabels) ?>,
        datasets: [{
            label: 'Số đơn',
            data: <?= json_encode($ordersValues) ?>,
            borderWidth: 3,
            borderColor: 'rgba(245, 112, 154, 1)',
            backgroundColor: 'rgba(245, 112, 154, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: 'rgba(245, 112, 154, 1)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Biểu đồ phân bố trạng thái
const ctx3 = document.getElementById('chartStatus');
new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
            data: <?= json_encode($statusValues) ?>,
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Biểu đồ top tours
const ctx4 = document.getElementById('chartTopTours');
new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: <?= json_encode($topTourLabels) ?>,
        datasets: [{
            label: 'Số lượt đặt',
            data: <?= json_encode($topTourValues) ?>,
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Animation số đếm
document.addEventListener('DOMContentLoaded', function() {
    const numbers = document.querySelectorAll('.stat-card .number');
    numbers.forEach(num => {
        const text = num.textContent;
        const isNumber = /^\d+/.test(text);
        if (isNumber) {
            const target = parseInt(text);
            let current = 0;
            const increment = target / 30;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    num.textContent = target;
                    clearInterval(timer);
                } else {
                    num.textContent = Math.floor(current);
                }
            }, 50);
        }
    });
});
</script>

</body>
</html>
