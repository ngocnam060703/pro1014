<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/hdv_model.php";
require_once __DIR__ . "/../../models/CustomerSpecialRequestModel.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$schedules = getScheduleByGuide($guide_id);

// Lấy số lượng yêu cầu đặc biệt cho mỗi tour
$requestModel = new CustomerSpecialRequestModel();
foreach ($schedules as &$sch) {
    if (isset($sch['departure_id'])) {
        $sch['special_requests_count'] = count($requestModel->getByDeparture($sch['departure_id']));
        $sch['pending_requests_count'] = $requestModel->getPendingCount($sch['departure_id']);
    } else {
        $sch['special_requests_count'] = 0;
        $sch['pending_requests_count'] = 0;
    }
}
unset($sch);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lịch trình HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#343a40; padding-top:20px; position:fixed; }
.sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
.sidebar a:hover{ background:#495057; color:#fff; border-left:3px solid #0d6efd; }
.content{ padding:30px; margin-left:16.666667%; }
.card-container{ background:#fff; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,0.1); }
.table thead th{ background:#0d6efd; color:#fff; text-align:center; }
.table tbody td{ text-align:center; vertical-align:middle; }
.table tbody tr:hover{ background:#e7f1ff; transition:0.3s; }
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
    <div class="card-container">
      <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-calendar-event"></i> Lịch trình của bạn</h3>

      <?php if(!empty($schedules)): ?>
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Tour</th>
              <th>Ngày khởi hành</th>
              <th>Điểm tập trung</th>
              <th>Số khách tối đa</th>
              <th>Ghi chú</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($schedules as $i => $sch): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td>
                <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $sch['departure_id'] ?>" class="text-primary fw-bold">
                  <?= htmlspecialchars($sch['tour_name'] ?? 'Chưa có') ?>
                </a>
                <?php if(isset($sch['special_requests_count']) && $sch['special_requests_count'] > 0): ?>
                  <br><small class="text-warning">
                    <i class="bi bi-exclamation-circle"></i> 
                    <?= $sch['special_requests_count'] ?> yêu cầu đặc biệt
                    <?php if($sch['pending_requests_count'] > 0): ?>
                      (<strong><?= $sch['pending_requests_count'] ?></strong> chờ xử lý)
                    <?php endif; ?>
                  </small>
                <?php endif; ?>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($sch['departure_time'] ?? '')) ?></td>
              <td><?= htmlspecialchars($sch['meeting_point'] ?? $sch['meeting_point'] ?? '') ?></td>
              <td><?= $sch['max_people'] ?? '' ?></td>
              <td><?= htmlspecialchars($sch['note'] ?? '') ?></td>
              <td>
                <?php
                  $status = $sch['status'] ?? 'scheduled';
                  $badgeClass = [
                    'scheduled' => 'badge bg-warning text-dark',
                    'in_progress' => 'badge bg-info text-white',
                    'completed' => 'badge bg-success text-white',
                    'pending' => 'badge bg-secondary text-white'
                  ];
                  $badge = $badgeClass[$status] ?? 'badge bg-secondary text-white';
                  $statusText = [
                    'scheduled' => 'Đã lên lịch',
                    'in_progress' => 'Đang diễn ra',
                    'completed' => 'Hoàn thành',
                    'pending' => 'Chờ xử lý'
                  ];
                  echo '<span class="' . $badge . '">' . ($statusText[$status] ?? ucfirst($status)) . '</span>';
                ?>
              </td>
              <td>
                <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $sch['departure_id'] ?>" class="btn btn-sm btn-primary">
                  <i class="bi bi-eye"></i> Chi tiết
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle"></i> Chưa có lịch trình nào được phân công.
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
