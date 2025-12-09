<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/GuideJournalModel.php";
require_once __DIR__ . "/../../models/GuideAssignModel.php";
require_once __DIR__ . "/../../models/DepartureModel.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$journalModel = new GuideJournalModel();
$journals = $journalModel->getByGuide($guide_id);

// Lấy danh sách departure từ các tour được phân công để tạo nhật ký mới
require_once __DIR__ . "/../../commons/function.php";
$assignModel = new GuideAssignModel();
$myAssigns = $assignModel->getByGuide($guide_id);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nhật ký tour</title>
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
.journal-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 20px;
  border-left: 4px solid #667eea;
  transition: all 0.3s;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.journal-card:hover {
  transform: translateX(5px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}
.table thead th { 
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff; 
  border: none;
  padding: 15px;
  font-weight: 600;
}
.table tbody tr:hover {
  background: #f8f9fa;
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
    <a href="index.php?act=hdv_nhatky" class="active"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_feedback"><i class="bi bi-chat-left-text"></i> Phản hồi đánh giá</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
          <i class="bi bi-journal-text"></i> Nhật ký tour
        </h3>
        <a href="index.php?act=hdv_journal_create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm nhật ký
        </a>
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

      <?php if(!empty($journals)): ?>
      <div class="table-responsive fade-in">
        <table class="table table-striped table-hover table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Tour</th>
              <th>Ngày</th>
              <th>Ngày số</th>
              <th>Hoạt động</th>
              <th>Ghi chú</th>
              <th>Hình ảnh</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($journals as $i => $item): ?>
            <tr>
              <td><strong><?= $i + 1 ?></strong></td>
              <td><i class="bi bi-geo-alt text-primary"></i> <?= htmlspecialchars($item['tour_name'] ?? $item['departure_name'] ?? 'N/A') ?></td>
              <td><?= $item['departure_time'] ? date('d/m/Y H:i', strtotime($item['departure_time'])) : 'N/A' ?></td>
              <td>
                <?php if(!empty($item['day_number'] ?? null)): ?>
                  <span class="badge bg-info"><i class="bi bi-calendar-day"></i> Ngày <?= htmlspecialchars($item['day_number']) ?></span>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if(!empty($item['activities'] ?? null)): ?>
                  <small><?= htmlspecialchars(substr($item['activities'], 0, 50)) . (strlen($item['activities']) > 50 ? '...' : '') ?></small>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <small><?= htmlspecialchars(substr($item['note'] ?? '', 0, 80)) . (strlen($item['note'] ?? '') > 80 ? '...' : '') ?></small>
                <?php if(!empty($item['customer_feedback'] ?? null)): ?>
                  <br><span class="badge bg-success"><i class="bi bi-chat-dots"></i> Có phản hồi KH</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if(!empty($item['photos'] ?? null)): ?>
                  <?php 
                    $photos = explode(',', $item['photos']);
                    $photoCount = count($photos);
                  ?>
                  <span class="badge bg-primary"><i class="bi bi-image"></i> <?= $photoCount ?> ảnh</span>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="index.php?act=hdv_journal_edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil"></i> Sửa
                </a>
                <a href="index.php?act=hdv_journal_delete&id=<?= $item['id'] ?>" 
                   class="btn btn-sm btn-danger" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                  <i class="bi bi-trash"></i> Xóa
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <div class="alert alert-info text-center fade-in" style="border-radius: 15px; padding: 40px;">
          <i class="bi bi-journal-x" style="font-size: 3rem; opacity: 0.5;"></i>
          <h5 class="mt-3">Chưa có nhật ký nào.</h5>
          <p class="text-muted">Hãy thêm nhật ký mới để ghi lại các hoạt động trong tour!</p>
          <a href="index.php?act=hdv_journal_create" class="btn btn-primary mt-3">
            <i class="bi bi-plus-circle"></i> Thêm nhật ký đầu tiên
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
