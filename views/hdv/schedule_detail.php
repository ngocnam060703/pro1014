<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/GuideScheduleModel.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$departure_id = $_GET['departure_id'] ?? 0;

$scheduleModel = new GuideScheduleModel();
$schedule = $scheduleModel->getScheduleDetail($guide_id, $departure_id);
$itineraryDays = $schedule ? $scheduleModel->getItineraryDays($schedule['tour_id']) : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết lịch trình</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#343a40; padding-top:20px; position:fixed; }
.sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
.sidebar a:hover{ background:#495057; color:#fff; border-left:3px solid #0d6efd; }
.content{ padding:30px; margin-left:16.666667%; }
.card-container{ background:#fff; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,0.1); margin-bottom:20px; }
.timeline-item { border-left: 3px solid #0d6efd; padding-left: 20px; margin-bottom: 30px; position: relative; }
.timeline-item::before { content: ''; position: absolute; left: -8px; top: 0; width: 15px; height: 15px; border-radius: 50%; background: #0d6efd; }
</style>
</head>
<body>
<div class="row g-0">
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">HDV</h4>
    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_lichtrinh"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    <a href="index.php?act=hdv_customers&departure_id=<?= $departure_id ?>"><i class="bi bi-people"></i> Danh sách khách</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <?php if($schedule): ?>
    <div class="card-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-primary"><i class="bi bi-calendar3"></i> Chi tiết lịch trình</h3>
        <a href="index.php?act=hdv_lichtrinh" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <h5 class="text-primary">Thông tin tour</h5>
          <table class="table table-bordered">
            <tr><th width="40%">Tên tour:</th><td><?= htmlspecialchars($schedule['tour_name']) ?></td></tr>
            <tr><th>Ngày khởi hành:</th><td><?= date('d/m/Y H:i', strtotime($schedule['departure_time'])) ?></td></tr>
            <tr><th>Điểm tập trung:</th><td><?= htmlspecialchars($schedule['meeting_point']) ?></td></tr>
            <tr><th>Số khách tối đa:</th><td><?= $schedule['max_people'] ?? $schedule['seats_available'] ?></td></tr>
            <tr><th>Trạng thái:</th><td>
              <span class="badge bg-<?= $schedule['status'] == 'completed' ? 'success' : ($schedule['status'] == 'in_progress' ? 'info' : 'warning') ?>">
                <?= $schedule['status'] == 'completed' ? 'Hoàn thành' : ($schedule['status'] == 'in_progress' ? 'Đang diễn ra' : 'Đã lên lịch') ?>
              </span>
            </td></tr>
          </table>
        </div>
        <div class="col-md-6">
          <h5 class="text-primary">Ghi chú</h5>
          <div class="alert alert-info">
            <?= nl2br(htmlspecialchars($schedule['note'] ?? $schedule['departure_notes'] ?? 'Không có ghi chú')) ?>
          </div>
        </div>
      </div>

      <?php if(!empty($schedule['tour_description'])): ?>
      <div class="mb-4">
        <h5 class="text-primary">Mô tả tour</h5>
        <p><?= nl2br(htmlspecialchars($schedule['tour_description'])) ?></p>
      </div>
      <?php endif; ?>

      <?php if(!empty($schedule['tour_itinerary'])): ?>
      <div class="mb-4">
        <h5 class="text-primary">Lịch trình tổng quan</h5>
        <div class="alert alert-light">
          <?= nl2br(htmlspecialchars($schedule['tour_itinerary'])) ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if(!empty($itineraryDays)): ?>
      <div class="mb-4">
        <h5 class="text-primary mb-4">Lịch trình chi tiết từng ngày</h5>
        <?php foreach($itineraryDays as $day): ?>
        <div class="timeline-item">
          <h6 class="text-primary">Ngày <?= $day['day_number'] ?>: <?= htmlspecialchars($day['title'] ?? '') ?></h6>
          <?php if($day['description']): ?>
          <p><?= nl2br(htmlspecialchars($day['description'])) ?></p>
          <?php endif; ?>
          <?php if($day['activities']): ?>
          <p><strong>Hoạt động:</strong> <?= nl2br(htmlspecialchars($day['activities'])) ?></p>
          <?php endif; ?>
          <?php if($day['meals']): ?>
          <p><strong>Bữa ăn:</strong> <?= htmlspecialchars($day['meals']) ?></p>
          <?php endif; ?>
          <?php if($day['accommodation']): ?>
          <p><strong>Nơi nghỉ:</strong> <?= htmlspecialchars($day['accommodation']) ?></p>
          <?php endif; ?>
          <?php if($day['notes']): ?>
          <p class="text-muted"><small><?= nl2br(htmlspecialchars($day['notes'])) ?></small></p>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="d-flex gap-2">
        <a href="index.php?act=hdv_customers&departure_id=<?= $departure_id ?>" class="btn btn-primary">
          <i class="bi bi-people"></i> Xem danh sách khách
        </a>
        <a href="index.php?act=hdv_checkin&departure_id=<?= $departure_id ?>" class="btn btn-success">
          <i class="bi bi-check-circle"></i> Check-in khách
        </a>
        <a href="index.php?act=hdv_journal_create&departure_id=<?= $departure_id ?>" class="btn btn-info">
          <i class="bi bi-journal-plus"></i> Ghi nhật ký
        </a>
      </div>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">Không tìm thấy lịch trình!</div>
    <?php endif; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

