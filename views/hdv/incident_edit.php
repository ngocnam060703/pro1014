<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa báo cáo sự cố</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; position:fixed; }
.sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
.sidebar a:hover { background: #495057; color: #fff; border-left: 3px solid #0d6efd; }
.content { padding: 30px; margin-left: 16.666667%; }
.card-container { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 900px; }
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
      <h3 class="mb-4 fw-bold text-danger"><i class="bi bi-pencil"></i> Sửa báo cáo sự cố</h3>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <form action="index.php?act=hdv_incident_update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $incident['id'] ?>">
        
        <div class="mb-3">
          <label class="form-label">Chọn tour khởi hành</label>
          <select name="departure_id" class="form-select" required>
            <option value="">-- Chọn tour --</option>
            <?php foreach($myAssigns as $assign): ?>
              <?php if(isset($assign['departure_id'])): ?>
              <option value="<?= $assign['departure_id'] ?>" <?= $incident['departure_id'] == $assign['departure_id'] ? 'selected' : '' ?>>
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
              <option value="Khách hàng" <?= $incident['incident_type'] == 'Khách hàng' ? 'selected' : '' ?>>Khách hàng</option>
              <option value="Phương tiện" <?= $incident['incident_type'] == 'Phương tiện' ? 'selected' : '' ?>>Phương tiện</option>
              <option value="Thời tiết" <?= $incident['incident_type'] == 'Thời tiết' ? 'selected' : '' ?>>Thời tiết</option>
              <option value="Dịch vụ" <?= $incident['incident_type'] == 'Dịch vụ' ? 'selected' : '' ?>>Dịch vụ</option>
              <option value="Khác" <?= $incident['incident_type'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Mức độ</label>
            <select name="severity" class="form-select" required>
              <option value="low" <?= ($incident['severity'] == 'low' || $incident['severity'] == 'thấp') ? 'selected' : '' ?>>Thấp</option>
              <option value="medium" <?= ($incident['severity'] == 'medium' || $incident['severity'] == 'trung bình' || $incident['severity'] == 'Trung bình') ? 'selected' : '' ?>>Trung bình</option>
              <option value="high" <?= ($incident['severity'] == 'high' || $incident['severity'] == 'cao' || $incident['severity'] == 'Cao') ? 'selected' : '' ?>>Cao</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Mô tả sự cố</label>
          <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($incident['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Giải pháp</label>
          <textarea name="solution" class="form-control" rows="3"><?= htmlspecialchars($incident['solution'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Hình ảnh hiện tại</label>
          <?php if(!empty($incident['photos'])): ?>
            <div class="mb-2">
              <img src="<?= htmlspecialchars($incident['photos']) ?>" alt="Hình ảnh sự cố" class="img-thumbnail" style="max-width: 300px;">
            </div>
          <?php endif; ?>
          <label class="form-label">Cập nhật hình ảnh (nếu có)</label>
          <input type="file" name="photos" class="form-control" accept="image/*">
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-danger">Cập nhật</button>
          <a href="index.php?act=hdv_data" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

