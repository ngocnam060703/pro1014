<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/GuideFeedbackModel.php";
require_once __DIR__ . "/../../models/GuideScheduleModel.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$departure_id = $_GET['departure_id'] ?? 0;

$feedbackModel = new GuideFeedbackModel();
$feedbacks = $departure_id ? $feedbackModel->getByDeparture($departure_id) : $feedbackModel->getByGuide($guide_id);

$scheduleModel = new GuideScheduleModel();
$schedule = $departure_id ? $scheduleModel->getScheduleDetail($guide_id, $departure_id) : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Phản hồi đánh giá</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#343a40; padding-top:20px; position:fixed; }
.sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
.sidebar a:hover{ background:#495057; color:#fff; border-left:3px solid #0d6efd; }
.content{ padding:30px; margin-left:16.666667%; }
.card-container{ background:#fff; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,0.1); margin-bottom:20px; }
.star-rating { color: #ffc107; font-size: 1.5rem; }
</style>
</head>
<body>
<div class="row g-0">
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

  <div class="col-10 content">
    <div class="card-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-info"><i class="bi bi-chat-left-text"></i> Phản hồi đánh giá về tour</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeedbackModal">
          <i class="bi bi-plus-circle"></i> Thêm phản hồi
        </button>
      </div>

      <?php if($schedule): ?>
      <div class="alert alert-info mb-4">
        <strong>Tour:</strong> <?= htmlspecialchars($schedule['tour_name']) ?> | 
        <strong>Ngày khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($schedule['departure_time'])) ?>
      </div>
      <?php endif; ?>

      <?php if(!empty($feedbacks)): ?>
      <div class="row">
        <?php foreach($feedbacks as $feedback): ?>
        <div class="col-md-6 mb-3">
          <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
              <span class="badge bg-primary"><?= htmlspecialchars($feedback['feedback_type']) ?></span>
              <?php if($feedback['rating']): ?>
              <div class="star-rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                  <i class="bi bi-star<?= $i <= $feedback['rating'] ? '-fill' : '' ?>"></i>
                <?php endfor; ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <?php if($feedback['provider_name']): ?>
              <h6 class="card-title"><?= htmlspecialchars($feedback['provider_name']) ?></h6>
              <?php endif; ?>
              <?php if($feedback['comment']): ?>
              <p class="card-text"><?= nl2br(htmlspecialchars($feedback['comment'])) ?></p>
              <?php endif; ?>
              <?php if($feedback['suggestions']): ?>
              <p class="card-text"><strong>Đề xuất:</strong> <?= nl2br(htmlspecialchars($feedback['suggestions'])) ?></p>
              <?php endif; ?>
              <?php if($feedback['tour_name']): ?>
              <small class="text-muted">Tour: <?= htmlspecialchars($feedback['tour_name']) ?></small>
              <?php endif; ?>
            </div>
            <div class="card-footer text-muted">
              <small><?= date('d/m/Y H:i', strtotime($feedback['created_at'])) ?></small>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> Chưa có phản hồi nào. Hãy thêm phản hồi để giúp cải thiện dịch vụ!
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Thêm phản hồi -->
<div class="modal fade" id="addFeedbackModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm phản hồi đánh giá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?act=hdv_feedback_store" method="POST">
        <div class="modal-body">
          <input type="hidden" name="guide_id" value="<?= $guide_id ?>">
          <input type="hidden" name="departure_id" value="<?= $departure_id ?>">
          
          <div class="mb-3">
            <label class="form-label">Loại phản hồi</label>
            <select name="feedback_type" class="form-select" required>
              <option value="">-- Chọn loại --</option>
              <option value="hotel">Khách sạn</option>
              <option value="restaurant">Nhà hàng</option>
              <option value="transport">Vận chuyển</option>
              <option value="service">Dịch vụ</option>
              <option value="other">Khác</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Tên nhà cung cấp (nếu có)</label>
            <input type="text" name="provider_name" class="form-control" placeholder="VD: Khách sạn ABC, Nhà hàng XYZ...">
          </div>
          
          <div class="mb-3">
            <label class="form-label">Đánh giá (1-5 sao)</label>
            <select name="rating" class="form-select">
              <option value="">-- Chọn đánh giá --</option>
              <option value="5">5 sao - Rất tốt</option>
              <option value="4">4 sao - Tốt</option>
              <option value="3">3 sao - Bình thường</option>
              <option value="2">2 sao - Kém</option>
              <option value="1">1 sao - Rất kém</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Nhận xét</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Đề xuất cải thiện</label>
            <textarea name="suggestions" class="form-control" rows="3" placeholder="Những điểm cần cải thiện..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

