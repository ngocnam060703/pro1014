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
        <h3 class="mb-0 fw-bold text-primary"><i class="bi bi-journal-text"></i> Nhật ký tour</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJournalModal">
          <i class="bi bi-plus-circle"></i> Thêm nhật ký
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

      <?php if(!empty($journals)): ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
          <thead class="table-primary">
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
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($item['tour_name'] ?? $item['departure_name'] ?? 'N/A') ?></td>
              <td><?= $item['departure_time'] ? date('d/m/Y H:i', strtotime($item['departure_time'])) : 'N/A' ?></td>
              <td>
                <?php if(!empty($item['day_number'] ?? null)): ?>
                  <span class="badge bg-info">Ngày <?= htmlspecialchars($item['day_number']) ?></span>
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
                  <br><span class="badge bg-success">Có phản hồi KH</span>
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
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle"></i> Chưa có nhật ký nào. Hãy thêm nhật ký mới!
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Thêm nhật ký -->
<div class="modal fade" id="addJournalModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm nhật ký mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?act=hdv_journal_store" method="POST" enctype="multipart/form-data">
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
              <label class="form-label">Số ngày trong tour</label>
              <input type="number" name="day_number" class="form-control" min="1" placeholder="VD: 1, 2, 3...">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Thời tiết</label>
              <select name="weather" class="form-select">
                <option value="">-- Chọn --</option>
                <option value="sunny">Nắng đẹp</option>
                <option value="cloudy">Có mây</option>
                <option value="rainy">Có mưa</option>
                <option value="windy">Có gió</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Các hoạt động trong ngày</label>
            <textarea name="activities" class="form-control" rows="3" placeholder="Liệt kê các hoạt động chính..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Ghi chú / Nhật ký</label>
            <textarea name="note" class="form-control" rows="5" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Phản hồi của khách hàng</label>
            <textarea name="customer_feedback" class="form-control" rows="3" placeholder="Ghi lại phản hồi, ý kiến của khách..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Hình ảnh (nếu có)</label>
            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
            <small class="text-muted">Có thể chọn nhiều ảnh</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
