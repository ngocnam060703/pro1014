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
      <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
      <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
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
            <h6 class="text-center mb-3"><i class="bi bi-graph-up"></i> Doanh thu 7 ngày gần đây</h6>
            <canvas id="chartRevenue"></canvas>
          </div>
        </div>

        <!-- Biểu đồ số đơn -->
        <div class="col-md-6">
          <div class="card p-3">
            <h6 class="text-center mb-3"><i class="bi bi-cart-check"></i> Số đơn theo ngày</h6>
            <canvas id="chartOrders"></canvas>
          </div>
        </div>

        <!-- Biểu đồ phân bố trạng thái -->
        <div class="col-md-6">
          <div class="card p-3">
            <h6 class="text-center mb-3"><i class="bi bi-pie-chart"></i> Phân bố trạng thái booking</h6>
            <canvas id="chartStatus"></canvas>
          </div>
        </div>

        <!-- Top tour bán chạy -->
        <div class="col-md-6">
          <div class="card p-3">
            <h6 class="text-center mb-3"><i class="bi bi-trophy"></i> Top 5 tour bán chạy</h6>
            <canvas id="chartTopTours"></canvas>
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
              backgroundColor: 'rgba(54, 162, 235, 0.6)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
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
              borderColor: 'rgba(255, 99, 132, 1)',
              backgroundColor: 'rgba(255, 99, 132, 0.1)',
              fill: true,
              tension: 0.4
          }]
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  display: true
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
              ]
          }]
      },
      options: {
          responsive: true,
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
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
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
  </script>

  </body>
  </html>
