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
body { background: #f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; position:fixed; }
.sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
.sidebar a:hover { background: #495057; color: #fff; border-left: 3px solid #0d6efd; }
.content { padding: 30px; margin-left: 16.666667%; }
.card-container { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); margin-bottom: 20px; }
</style>
</head>

<body>
<div class="row g-0">
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

  <div class="col-10 content">
    <div class="card-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-danger"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</h3>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
          <i class="bi bi-plus-circle"></i> Báo cáo sự cố mới
        </button>
      </div>

      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $_SESSION['message']; unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if(!empty($incidents)): ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
          <thead class="table-danger">
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
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($item['tour_name'] ?? $item['departure_name'] ?? 'N/A') ?></td>
              <td><?= $item['departure_time'] ? date('d/m/Y H:i', strtotime($item['departure_time'])) : 'N/A' ?></td>
              <td><?= htmlspecialchars($item['incident_type'] ?? '') ?></td>
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
                  echo '<span class="' . $badge . '">' . ($severityText[$severity] ?? ucfirst($severity)) . '</span>';
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
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle"></i> Chưa có báo cáo sự cố nào.
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Thêm báo cáo sự cố -->
<div class="modal fade" id="addIncidentModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Báo cáo sự cố mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?act=hdv_incident_store" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="guide_id" value="<?= $guide_id ?>">
          <div class="mb-3">
            <label class="form-label">Chọn tour khởi hành</label>
            <select name="departure_id" class="form-select" required>
              <option value="">-- Chọn tour --</option>
              <?php foreach($myAssigns as $assign): ?>
                <?php if(isset($assign['departure_id'])): ?>
                <option value="<?= $assign['departure_id'] ?>">
                  <?= htmlspecialchars($assign['tour_name'] ?? '') ?> - 
                  <?= date('d/m/Y H:i', strtotime($assign['departure_time'] ?? '')) ?>
                </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Loại sự cố</label>
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
              <label class="form-label">Mức độ</label>
              <select name="severity" class="form-select" required>
                <option value="low">Thấp</option>
                <option value="medium">Trung bình</option>
                <option value="high">Cao</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Mô tả sự cố</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Giải pháp</label>
            <textarea name="solution" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Hình ảnh (nếu có)</label>
            <input type="file" name="photos" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger">Gửi báo cáo</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
