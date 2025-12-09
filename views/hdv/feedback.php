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
.card-container { 
  background: #fff; 
  border-radius: 20px; 
  padding: 30px; 
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  margin-bottom: 20px;
}
.feedback-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 20px;
  border-left: 4px solid #0dcaf0;
  transition: all 0.3s;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  height: 100%;
}
.feedback-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.star-rating { 
  color: #ffc107; 
  font-size: 1.5rem; 
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
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4 fw-bold">HDV</h4>
    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_schedule_list"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_feedback" class="active"><i class="bi bi-chat-left-text"></i> Phản hồi đánh giá</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #0dcaf0 0%, #0a58ca 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
          <i class="bi bi-chat-left-text"></i> Phản hồi đánh giá về tour
        </h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeedbackModal">
          <i class="bi bi-plus-circle"></i> Thêm phản hồi
        </button>
      </div>

      <?php if($schedule): ?>
      <div class="alert alert-info mb-4 fade-in" style="border-radius: 15px; border-left: 4px solid #0dcaf0;">
        <strong><i class="bi bi-info-circle"></i> Tour:</strong> <?= htmlspecialchars($schedule['tour_name']) ?> | 
        <strong><i class="bi bi-calendar-event"></i> Ngày khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($schedule['departure_time'])) ?>
      </div>
      <?php endif; ?>

      <?php if(!empty($feedbacks)): ?>
      <div class="row fade-in">
        <?php foreach($feedbacks as $feedback): ?>
        <div class="col-md-6 mb-3">
          <div class="feedback-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center mb-3">
              <span class="badge bg-primary" style="padding: 8px 15px; border-radius: 20px;">
                <i class="bi bi-tag"></i> <?= htmlspecialchars($feedback['feedback_type']) ?>
              </span>
              <?php if($feedback['rating']): ?>
              <div class="star-rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                  <i class="bi bi-star<?= $i <= $feedback['rating'] ? '-fill' : '' ?>"></i>
                <?php endfor; ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="card-body p-0">
              <?php if($feedback['provider_name']): ?>
              <h6 class="card-title mb-3">
                <i class="bi bi-building text-primary"></i> <?= htmlspecialchars($feedback['provider_name']) ?>
              </h6>
              <?php endif; ?>
              <?php if($feedback['comment']): ?>
              <p class="card-text mb-3">
                <i class="bi bi-chat-quote text-info"></i> <?= nl2br(htmlspecialchars($feedback['comment'])) ?>
              </p>
              <?php endif; ?>
              <?php if($feedback['suggestions']): ?>
              <p class="card-text mb-3">
                <strong><i class="bi bi-lightbulb text-warning"></i> Đề xuất:</strong> 
                <?= nl2br(htmlspecialchars($feedback['suggestions'])) ?>
              </p>
              <?php endif; ?>
              <?php if($feedback['tour_name']): ?>
              <small class="text-muted">
                <i class="bi bi-geo-alt"></i> Tour: <?= htmlspecialchars($feedback['tour_name']) ?>
              </small>
              <?php endif; ?>
            </div>
            <div class="card-footer bg-transparent border-0 text-muted mt-3 pt-3" style="border-top: 1px solid #e9ecef !important;">
              <small><i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($feedback['created_at'])) ?></small>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
        <div class="alert alert-info text-center fade-in" style="border-radius: 15px; padding: 40px;">
          <i class="bi bi-chat-left-dots" style="font-size: 3rem; opacity: 0.5;"></i>
          <h5 class="mt-3">Chưa có phản hồi nào.</h5>
          <p class="text-muted">Hãy thêm phản hồi để giúp cải thiện dịch vụ!</p>
          <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addFeedbackModal">
            <i class="bi bi-plus-circle"></i> Thêm phản hồi đầu tiên
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Thêm phản hồi -->
<div class="modal fade" id="addFeedbackModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 15px;">
      <div class="modal-header" style="background: linear-gradient(135deg, #0dcaf0 0%, #0a58ca 100%); color: white; border-radius: 15px 15px 0 0;">
        <h5 class="modal-title"><i class="bi bi-chat-left-text"></i> Thêm phản hồi đánh giá</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?act=hdv_feedback_store" method="POST">
        <div class="modal-body">
          <input type="hidden" name="guide_id" value="<?= $guide_id ?>">
          <input type="hidden" name="departure_id" value="<?= $departure_id ?>">
          
          <div class="mb-3">
            <label class="form-label fw-bold"><i class="bi bi-tag"></i> Loại phản hồi</label>
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
            <label class="form-label fw-bold"><i class="bi bi-building"></i> Tên nhà cung cấp (nếu có)</label>
            <input type="text" name="provider_name" class="form-control" placeholder="VD: Khách sạn ABC, Nhà hàng XYZ...">
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-bold"><i class="bi bi-star"></i> Đánh giá (1-5 sao)</label>
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
            <label class="form-label fw-bold"><i class="bi bi-chat-quote"></i> Nhận xét</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-bold"><i class="bi bi-lightbulb"></i> Đề xuất cải thiện</label>
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
