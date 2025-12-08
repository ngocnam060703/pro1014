<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/hdv_model.php";
require_once __DIR__ . "/../../models/CustomerSpecialRequestModel.php";
require_once __DIR__ . "/../../models/GuideAssignModel.php";
require_once __DIR__ . "/../../commons/function.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$guide_name = $_SESSION['guide']['fullname'] ?? 'HDV';

// Lấy dữ liệu thực từ database
$totalToursToday = getCountToursTodayByGuide($guide_id);
$totalTours = getCountToursByGuide($guide_id);
$completedTours = 0; // Có thể tính từ status = 'completed'
$incidentsReported = getCountIncidentsByGuide($guide_id);
$journalsCount = getCountLogsByGuide($guide_id);

// Lấy số lượng yêu cầu đặc biệt đang chờ xử lý
$requestModel = new CustomerSpecialRequestModel();
$assignModel = new GuideAssignModel();
$myAssigns = $assignModel->getByGuide($guide_id);
$pendingRequestsCount = 0;
foreach ($myAssigns as $assign) {
    if (isset($assign['departure_id'])) {
        $pendingRequestsCount += $requestModel->getPendingCount($assign['departure_id']);
    }
}

// Lấy tour sắp tới (3 tour gần nhất)
$upcomingTours = [];
$today = date('Y-m-d');
$sql = "SELECT 
            ga.*,
            t.title AS tour_name,
            t.tour_code,
            d.departure_time,
            d.end_date,
            d.meeting_point,
            d.status AS departure_status,
            (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') AS booked_guests
        FROM guide_assign ga
        INNER JOIN departures d ON ga.departure_id = d.id
        INNER JOIN tours t ON d.tour_id = t.id
        WHERE ga.guide_id = ? 
        AND (d.status IN ('open', 'upcoming', 'in_progress') OR DATE(d.departure_time) >= ?)
        ORDER BY d.departure_time ASC
        LIMIT 3";
$upcomingTours = pdo_query($sql, $guide_id, $today);

// Tính tour đã hoàn thành
$sql_completed = "SELECT COUNT(*) as cnt
                  FROM guide_assign ga
                  INNER JOIN departures d ON ga.departure_id = d.id
                  WHERE ga.guide_id = ? AND d.status = 'completed'";
$result_completed = pdo_query_one($sql_completed, $guide_id);
$completedTours = $result_completed['cnt'] ?? 0;
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
.tour-item {
    padding: 15px;
    border-left: 4px solid #3498db;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s;
}
.tour-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}
.quick-action-btn {
    border-radius: 12px;
    padding: 15px 25px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
}
.quick-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-in {
    animation: fadeIn 0.6s ease-out;
}
</style>
</head>

<body>
<div class="row g-0">
  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4 fw-bold">HDV</h4>
    <a href="index.php?act=hdv_home" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_schedule_list"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_feedback"><i class="bi bi-chat-left-text"></i> Phản hồi đánh giá</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    
    <!-- Welcome Card -->
    <div class="welcome-card fade-in">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h2 class="mb-2"><i class="bi bi-person-circle"></i> Chào mừng, <?= htmlspecialchars($guide_name) ?>!</h2>
          <p class="mb-0 opacity-75">Hôm nay là <?= date('d/m/Y') ?> - <?= date('l') == 'Monday' ? 'Thứ Hai' : (date('l') == 'Tuesday' ? 'Thứ Ba' : (date('l') == 'Wednesday' ? 'Thứ Tư' : (date('l') == 'Thursday' ? 'Thứ Năm' : (date('l') == 'Friday' ? 'Thứ Sáu' : (date('l') == 'Saturday' ? 'Thứ Bảy' : 'Chủ Nhật'))))) ?></p>
        </div>
        <div class="text-end">
          <i class="bi bi-calendar-check" style="font-size: 4rem; opacity: 0.3;"></i>
        </div>
      </div>
    </div>

    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
        <i class="bi bi-check-circle"></i> <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
        <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4 fade-in">
      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <i class="bi bi-calendar-event icon"></i>
          <div class="label"><i class="bi bi-calendar3"></i> Tour hôm nay</div>
          <div class="number"><?= $totalToursToday ?></div>
          <small>Tour cần thực hiện</small>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
          <i class="bi bi-journal-text icon"></i>
          <div class="label"><i class="bi bi-journal-check"></i> Nhật ký đã gửi</div>
          <div class="number"><?= $journalsCount ?></div>
          <small>Báo cáo đã gửi</small>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
          <i class="bi bi-calendar-check icon"></i>
          <div class="label"><i class="bi bi-list-check"></i> Tổng tour</div>
          <div class="number"><?= $totalTours ?></div>
          <small><?= $completedTours ?> đã hoàn thành</small>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
          <i class="bi bi-exclamation-triangle icon"></i>
          <div class="label"><i class="bi bi-shield-exclamation"></i> Sự cố báo cáo</div>
          <div class="number"><?= $incidentsReported ?></div>
          <small>Sự cố đã báo</small>
        </div>
      </div>
    </div>

    <!-- Warning Alert -->
    <?php if($pendingRequestsCount > 0): ?>
    <div class="alert alert-warning alert-dismissible fade show fade-in" role="alert" style="border-radius: 15px; border-left: 5px solid #ffc107;">
      <h5 class="mb-2"><i class="bi bi-exclamation-circle"></i> Cảnh báo: Có <?= $pendingRequestsCount ?> yêu cầu đặc biệt đang chờ xử lý!</h5>
      <p class="mb-0">Vui lòng kiểm tra và xử lý các yêu cầu đặc biệt của khách hàng trong các tour được phân công.</p>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="row g-3">
      <!-- Tour sắp tới -->
      <div class="col-md-8">
        <div class="card card-modern fade-in">
          <div class="card-header">
            <i class="bi bi-calendar-range"></i> Tour sắp tới
          </div>
          <div class="card-body">
            <?php if(!empty($upcomingTours)): ?>
              <?php foreach($upcomingTours as $tour): ?>
                <div class="tour-item">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                      <h6 class="mb-2 fw-bold text-primary">
                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($tour['tour_name']) ?>
                        <?php if(!empty($tour['tour_code'])): ?>
                          <span class="badge bg-secondary badge-modern"><?= htmlspecialchars($tour['tour_code']) ?></span>
                        <?php endif; ?>
                      </h6>
                      <p class="mb-1 text-muted">
                        <i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($tour['departure_time'])) ?>
                        <?php if(!empty($tour['end_date'])): ?>
                          - <?= date('d/m/Y', strtotime($tour['end_date'])) ?>
                        <?php endif; ?>
                      </p>
                      <?php if(!empty($tour['meeting_point'])): ?>
                        <p class="mb-1 text-muted">
                          <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($tour['meeting_point']) ?>
                        </p>
                      <?php endif; ?>
                      <div class="d-flex gap-2 mt-2">
                        <span class="badge bg-info badge-modern">
                          <i class="bi bi-people"></i> <?= $tour['booked_guests'] ?? 0 ?> khách
                        </span>
                        <?php
                        $status = $tour['departure_status'] ?? 'open';
                        $statusBadge = [
                          'open' => 'bg-success',
                          'upcoming' => 'bg-warning',
                          'in_progress' => 'bg-primary',
                          'completed' => 'bg-secondary'
                        ];
                        $statusText = [
                          'open' => 'Mở bán',
                          'upcoming' => 'Sắp khởi hành',
                          'in_progress' => 'Đang chạy',
                          'completed' => 'Đã hoàn thành'
                        ];
                        ?>
                        <span class="badge <?= $statusBadge[$status] ?? 'bg-secondary' ?> badge-modern">
                          <?= $statusText[$status] ?? $status ?>
                        </span>
                      </div>
                    </div>
                    <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $tour['departure_id'] ?>" 
                       class="btn btn-sm btn-primary">
                      <i class="bi bi-eye"></i> Xem chi tiết
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="text-center mt-3">
                <a href="index.php?act=hdv_schedule_list" class="btn btn-outline-primary">
                  <i class="bi bi-list-ul"></i> Xem tất cả lịch trình
                </a>
              </div>
            <?php else: ?>
              <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                <p class="mt-3">Chưa có tour sắp tới</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="col-md-4">
        <div class="card card-modern fade-in">
          <div class="card-header">
            <i class="bi bi-lightning-charge"></i> Thao tác nhanh
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <a href="index.php?act=hdv_schedule_list" class="btn btn-primary quick-action-btn">
                <i class="bi bi-calendar-event"></i> Xem lịch làm việc
              </a>
              <a href="index.php?act=hdv_nhatky" class="btn btn-success quick-action-btn">
                <i class="bi bi-journal-plus"></i> Ghi nhật ký
              </a>
              <a href="index.php?act=hdv_data" class="btn btn-danger quick-action-btn">
                <i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố
              </a>
              <a href="index.php?act=hdv_feedback" class="btn btn-info quick-action-btn">
                <i class="bi bi-chat-left-text"></i> Phản hồi đánh giá
              </a>
            </div>
          </div>
        </div>

        <!-- Info Card -->
        <div class="card card-modern fade-in mt-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
          <div class="card-body text-center">
            <i class="bi bi-info-circle" style="font-size: 2.5rem; opacity: 0.8;"></i>
            <h6 class="mt-3 mb-2">Thông tin hữu ích</h6>
            <p class="mb-0 small opacity-75">
              Nhớ ghi nhật ký hàng ngày và báo cáo sự cố kịp thời để đảm bảo chất lượng dịch vụ tốt nhất.
            </p>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Thêm hiệu ứng số đếm
document.addEventListener('DOMContentLoaded', function() {
    const numbers = document.querySelectorAll('.stat-card .number');
    numbers.forEach(num => {
        const target = parseInt(num.textContent);
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
    });
});
</script>
</body>
</html>
