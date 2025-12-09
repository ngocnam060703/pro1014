<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/GuideIncidentModel.php";
require_once __DIR__ . "/../../models/GuideAssignModel.php";
require_once __DIR__ . "/../../commons/function.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$incidentModel = new GuideIncidentModel();
$incidents = $incidentModel->getByGuide($guide_id);

// Lấy danh sách departure từ các tour được phân công
$assignModel = new GuideAssignModel();
$myAssigns = $assignModel->getByGuide($guide_id);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Báo cáo sự cố</title>
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
.incident-card {
  background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 20px;
  border-left: 4px solid #dc3545;
  transition: all 0.3s;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.incident-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(220, 53, 69, 0.2);
}
.table thead th { 
  background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
  color: #fff; 
  border: none;
  padding: 15px;
  font-weight: 600;
}
.table tbody tr:hover {
  background: #fff5f5;
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
    <a href="index.php?act=hdv_feedback"><i class="bi bi-chat-left-text"></i> Phản hồi đánh giá</a>
    <a href="index.php?act=hdv_data" class="active"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
          <i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố
        </h3>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addIncidentModal" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); border: none;">
          <i class="bi bi-plus-circle"></i> Báo cáo sự cố mới
        </button>
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

      <?php if(!empty($incidents)): ?>
      <div class="table-responsive fade-in">
        <table class="table table-striped table-hover table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Tour</th>
              <th>Ngày khởi hành</th>
              <th>Loại sự cố</th>
              <th>Mức độ</th>
              <th>Mô tả</th>
              <th>Giải pháp</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($incidents as $i => $item): ?>
            <tr>
              <td><strong><?= $i + 1 ?></strong></td>
              <td><i class="bi bi-geo-alt text-danger"></i> <?= htmlspecialchars($item['tour_name'] ?? $item['departure_name'] ?? 'N/A') ?></td>
              <td><i class="bi bi-calendar-event"></i> <?= $item['departure_time'] ? date('d/m/Y H:i', strtotime($item['departure_time'])) : 'N/A' ?></td>
              <td><span class="badge bg-secondary"><?= htmlspecialchars($item['incident_type'] ?? '') ?></span></td>
              <td>
                <?php
                  $severity = $item['severity'] ?? 'low';
                  $badgeClass = [
                    'low' => 'badge bg-info',
                    'medium' => 'badge bg-warning',
                    'high' => 'badge bg-danger'
                  ];
                  $badge = $badgeClass[$severity] ?? 'badge bg-secondary';
                  $severityText = [
                    'low' => 'Thấp',
                    'medium' => 'Trung bình',
                    'high' => 'Cao'
                  ];
                  echo '<span class="' . $badge . '" style="padding: 8px 15px; border-radius: 20px; font-weight: 600;">' . ($severityText[$severity] ?? ucfirst($severity)) . '</span>';
                ?>
              </td>
              <td><?= htmlspecialchars(substr($item['description'] ?? '', 0, 50)) . (strlen($item['description'] ?? '') > 50 ? '...' : '') ?></td>
              <td><?= htmlspecialchars(substr($item['solution'] ?? '', 0, 50)) . (strlen($item['solution'] ?? '') > 50 ? '...' : '') ?></td>
              <td>
                <a href="index.php?act=hdv_incident_edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil"></i> Sửa
                </a>
                <a href="index.php?act=hdv_incident_delete&id=<?= $item['id'] ?>" 
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
          <i class="bi bi-shield-check" style="font-size: 3rem; opacity: 0.5;"></i>
          <h5 class="mt-3">Chưa có báo cáo sự cố nào.</h5>
          <p class="text-muted">Hãy báo cáo ngay khi có sự cố xảy ra trong tour!</p>
          <button class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#addIncidentModal" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); border: none;">
            <i class="bi bi-plus-circle"></i> Báo cáo sự cố đầu tiên
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Thêm báo cáo sự cố -->
<div class="modal fade" id="addIncidentModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 15px;">
      <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; border-radius: 15px 15px 0 0;">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố mới</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?act=hdv_incident_store" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="guide_id" value="<?= $guide_id ?>">
          <div class="mb-3">
            <label class="form-label fw-bold"><i class="bi bi-calendar-event"></i> Chọn tour khởi hành</label>
            <select name="departure_id" class="form-select" required>
              <option value="">-- Chọn tour --</option>
              <?php foreach($myAssigns as $assign): ?>
                <?php if(isset($assign['departure_id'])): ?>
                <option value="<?= $assign['departure_id'] ?>">
                  <?= htmlspecialchars($assign['tour_name'] ?? '') ?>
                </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold"><i class="bi bi-tag"></i> Loại sự cố</label>
              <select name="incident_type" class="form-select" required>
                <option value="">-- Chọn loại --</option>
                <option value="Khách hàng">Khách hàng</option>
                <option value="Phương tiện">Phương tiện</option>
                <option value="Thời tiết">Thời tiết</option>
                <option value="Dịch vụ">Dịch vụ</option>
                <option value="Khác">Khác</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold"><i class="bi bi-exclamation-circle"></i> Mức độ</label>
              <select name="severity" class="form-select" required>
                <option value="low">Thấp</option>
                <option value="medium">Trung bình</option>
                <option value="high">Cao</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold"><i class="bi bi-file-text"></i> Mô tả sự cố</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold"><i class="bi bi-check-circle"></i> Giải pháp</label>
            <textarea name="solution" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold"><i class="bi bi-image"></i> Hình ảnh (nếu có)</label>
            <input type="file" name="photos" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); border: none;">Gửi báo cáo</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
