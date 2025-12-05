<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Doanh thu - Chi phí - Lợi nhuận</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #dfe9f3, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            padding-top: 20px;
        }
        .sidebar h4 { font-weight: 700; color:#fff; }
        .sidebar a {
            color: #ccc;
            padding: 12px;
            display: block;
            text-decoration: none;
            font-size: 15px;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover {
            background: #495057;
            color: #fff;
            border-left: 3px solid #0d6efd;
        }
        .sidebar a.active {
            color:#fff;
            background:#495057;
            border-left:3px solid #0d6efd;
        }
        .content { padding: 30px; }
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card.revenue { border-left-color: #28a745; }
        .stat-card.expense { border-left-color: #dc3545; }
        .stat-card.profit { border-left-color: #007bff; }
        .stat-card.margin { border-left-color: #ffc107; }
    </style>
</head>
<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=revenue-report" class="active"><i class="bi bi-graph-up"></i> Báo cáo doanh thu</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-graph-up-arrow"></i> Báo cáo Doanh thu - Chi phí - Lợi nhuận
      </h3>
    </div>

    <!-- Bộ lọc -->
    <div class="card p-4 mb-4">
        <form method="get" action="index.php" class="row g-3">
            <input type="hidden" name="act" value="revenue-report">
            <div class="col-md-3">
                <label class="form-label">Tour</label>
                <select name="tour_id" class="form-select">
                    <option value="">Tất cả tour</option>
                    <?php foreach ($tours as $tour): ?>
                        <option value="<?= $tour['id'] ?>" <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tour['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Từ ngày</label>
                <input type="date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Đến ngày</label>
                <input type="date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Kỳ báo cáo</label>
                <select name="period" class="form-select">
                    <option value="by_tour" <?= ($periodType ?? 'by_tour') == 'by_tour' ? 'selected' : '' ?>>Theo tour</option>
                    <option value="daily" <?= ($periodType ?? '') == 'daily' ? 'selected' : '' ?>>Theo ngày</option>
                    <option value="monthly" <?= ($periodType ?? '') == 'monthly' ? 'selected' : '' ?>>Theo tháng</option>
                    <option value="quarterly" <?= ($periodType ?? '') == 'quarterly' ? 'selected' : '' ?>>Theo quý</option>
                    <option value="yearly" <?= ($periodType ?? '') == 'yearly' ? 'selected' : '' ?>>Theo năm</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel"></i> Lọc</button>
                <a href="index.php?act=revenue-report" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
            </div>
        </form>
    </div>

    <!-- Tổng quan -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card revenue p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Tổng Doanh thu</h6>
                        <h3 class="mb-0 text-success"><?= number_format($summary['total_revenue'], 0, ',', '.') ?> đ</h3>
                    </div>
                    <i class="bi bi-arrow-up-circle text-success" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card expense p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Tổng Chi phí</h6>
                        <h3 class="mb-0 text-danger"><?= number_format($summary['total_expenses'], 0, ',', '.') ?> đ</h3>
                    </div>
                    <i class="bi bi-arrow-down-circle text-danger" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card profit p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Tổng Lợi nhuận</h6>
                        <h3 class="mb-0 <?= $summary['total_profit'] >= 0 ? 'text-primary' : 'text-danger' ?>">
                            <?= number_format($summary['total_profit'], 0, ',', '.') ?> đ
                        </h3>
                    </div>
                    <i class="bi bi-graph-up <?= $summary['total_profit'] >= 0 ? 'text-primary' : 'text-danger' ?>" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card margin p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Tỷ suất lợi nhuận</h6>
                        <h3 class="mb-0 text-warning"><?= number_format($summary['profit_margin'], 2) ?>%</h3>
                    </div>
                    <i class="bi bi-percent text-warning" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng báo cáo theo tour -->
    <div class="card p-4 mb-4">
        <h5 class="mb-3"><i class="bi bi-table"></i> Báo cáo theo Tour</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>STT</th>
                        <th>Tour</th>
                        <th class="text-end">Doanh thu</th>
                        <th class="text-end">Chi phí</th>
                        <th class="text-end">Lợi nhuận</th>
                        <th class="text-end">Tỷ suất LN</th>
                        <th class="text-center">Số booking</th>
                        <th class="text-center">Số khách</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reportData)): ?>
                        <?php foreach ($reportData as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['tour_name']) ?></td>
                                <td class="text-end text-success"><?= number_format($row['total_revenue'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td class="text-end text-danger"><?= number_format($row['total_expenses'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td class="text-end <?= ($row['total_profit'] ?? 0) >= 0 ? 'text-primary fw-bold' : 'text-danger fw-bold' ?>">
                                    <?= number_format($row['total_profit'] ?? 0, 0, ',', '.') ?> đ
                                </td>
                                <td class="text-end">
                                    <span class="badge <?= ($row['profit_margin'] ?? 0) >= 0 ? 'bg-success' : 'bg-danger' ?>">
                                        <?= number_format($row['profit_margin'] ?? 0, 2) ?>%
                                    </span>
                                </td>
                                <td class="text-center"><?= $row['booking_count'] ?? 0 ?></td>
                                <td class="text-center"><?= $row['guest_count'] ?? 0 ?></td>
                                <td class="text-center">
                                    <a href="index.php?act=revenue-report-detail&tour_id=<?= $row['tour_id'] ?>&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Biểu đồ so sánh -->
    <?php if (!empty($reportData) && count($reportData) > 0): ?>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <h5 class="mb-3"><i class="bi bi-bar-chart"></i> So sánh Doanh thu theo Tour</h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <h5 class="mb-3"><i class="bi bi-pie-chart"></i> Phân bổ Lợi nhuận</h5>
                    <canvas id="profitChart"></canvas>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Top tour theo lợi nhuận -->
    <?php if (!empty($topTours)): ?>
        <div class="card p-4">
            <h5 class="mb-3"><i class="bi bi-trophy"></i> Top Tour theo Lợi nhuận</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-warning">
                        <tr>
                            <th>Hạng</th>
                            <th>Tour</th>
                            <th class="text-end">Lợi nhuận</th>
                            <th class="text-end">Tỷ suất LN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topTours as $index => $tour): ?>
                            <tr>
                                <td>
                                    <?php if ($index == 0): ?>
                                        <span class="badge bg-warning text-dark">🥇 1</span>
                                    <?php elseif ($index == 1): ?>
                                        <span class="badge bg-secondary">🥈 2</span>
                                    <?php elseif ($index == 2): ?>
                                        <span class="badge bg-danger">🥉 3</span>
                                    <?php else: ?>
                                        <?= $index + 1 ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                                <td class="text-end fw-bold text-primary"><?= number_format($tour['total_profit'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td class="text-end"><?= number_format($tour['profit_margin'] ?? 0, 2) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
<?php if (!empty($reportData) && count($reportData) > 0): ?>
// Biểu đồ doanh thu
const revenueCtx = document.getElementById('revenueChart');
if (revenueCtx) {
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: [<?= implode(',', array_map(function($r) { return "'" . htmlspecialchars($r['tour_name'], ENT_QUOTES) . "'"; }, array_slice($reportData, 0, 10))) ?>],
            datasets: [{
                label: 'Doanh thu',
                data: [<?= implode(',', array_map(function($r) { return $r['total_revenue'] ?? 0; }, array_slice($reportData, 0, 10))) ?>],
                backgroundColor: 'rgba(40, 167, 69, 0.8)'
            }, {
                label: 'Chi phí',
                data: [<?= implode(',', array_map(function($r) { return $r['total_expenses'] ?? 0; }, array_slice($reportData, 0, 10))) ?>],
                backgroundColor: 'rgba(220, 53, 69, 0.8)'
            }, {
                label: 'Lợi nhuận',
                data: [<?= implode(',', array_map(function($r) { return $r['total_profit'] ?? 0; }, array_slice($reportData, 0, 10))) ?>],
                backgroundColor: 'rgba(0, 123, 255, 0.8)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Biểu đồ phân bổ lợi nhuận
const profitCtx = document.getElementById('profitChart');
if (profitCtx) {
    new Chart(profitCtx, {
        type: 'pie',
        data: {
            labels: [<?= implode(',', array_map(function($r) { return "'" . htmlspecialchars($r['tour_name'], ENT_QUOTES) . "'"; }, array_slice($reportData, 0, 10))) ?>],
            datasets: [{
                data: [<?= implode(',', array_map(function($r) { return max(0, $r['total_profit'] ?? 0); }, array_slice($reportData, 0, 10))) ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(199, 199, 199, 0.8)',
                    'rgba(83, 102, 255, 0.8)',
                    'rgba(255, 99, 255, 0.8)',
                    'rgba(99, 255, 132, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
}
<?php endif; ?>
</script>
</body>
</html>

