<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa nhật ký</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; position:fixed; }
.sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
.sidebar a:hover { background: #495057; color: #fff; border-left: 3px solid #0d6efd; }
.content { padding: 30px; margin-left: 16.666667%; }
.card-container { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 800px; }
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
      <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-pencil"></i> Sửa nhật ký</h3>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <form action="index.php?act=hdv_journal_update" method="POST">
        <input type="hidden" name="id" value="<?= $journal['id'] ?>">
        
        <div class="mb-3">
          <label class="form-label">Chọn tour khởi hành</label>
          <select name="departure_id" class="form-select" required>
            <option value="">-- Chọn tour --</option>
            <?php foreach($myAssigns as $assign): ?>
              <?php if(isset($assign['departure_id'])): ?>
              <option value="<?= $assign['departure_id'] ?>" <?= $journal['departure_id'] == $assign['departure_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($assign['tour_name'] ?? '') ?> - 
                <?= date('d/m/Y H:i', strtotime($assign['departure_time'] ?? '')) ?>
              </option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Ghi chú</label>
          <textarea name="note" class="form-control" rows="5" required><?= htmlspecialchars($journal['note'] ?? '') ?></textarea>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Cập nhật</button>
          <a href="index.php?act=hdv_nhatky" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

