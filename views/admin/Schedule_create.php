<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm lịch trình</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(to right, #dfe9f3, #ffffff);
    font-family: 'Segoe UI', sans-serif;
}
.sidebar {
    height: 100vh;
    background: #343a40;
    padding-top: 20px;
}
.sidebar h4 { font-weight: 700; color:#fff; }
.sidebar a {
    color: #ccc;
    padding: 12px;
    display: block;
    text-decoration: none;
    font-size: 15px;
    border-left: 3px solid transparent;
}
.sidebar a:hover {
    background: #495057;
    color: #fff;
    border-left: 3px solid #0d6efd;
}
.sidebar a.active {
    color:#fff;
    background:#495057;
    border-left:3px solid #0d6efd;
}
.content { padding: 30px; }
.card {
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.btn-primary, .btn-success, .btn-secondary {
    border-radius: 50px;
}
</style>
</head>
<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule" class="active"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-calendar-plus"></i> Thêm lịch trình
      </h3>
      <a href="index.php?act=schedule" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
      </a>
    </div>

    <div class="card p-4">
      <form action="index.php?act=schedule-store" method="post">

        <div class="mb-3">
          <label class="form-label">Chọn Tour</label>
          <select name="tour_id" class="form-select" required>
            <option value="">-- Chọn tour --</option>
            <?php foreach($listTour as $tour): ?>
              <option value="<?= $tour['id'] ?>"><?= $tour['title'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Ngày & giờ khởi hành</label>
          <input type="datetime-local" name="departure_time" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Điểm tập trung</label>
          <input type="text" name="meeting_point" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Số chỗ còn</label>
          <input type="number" name="seats_available" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Ghi chú</label>
          <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success me-2">
          <i class="bi bi-save"></i> Lưu
        </button>
        <a href="index.php?act=schedule" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>

      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
