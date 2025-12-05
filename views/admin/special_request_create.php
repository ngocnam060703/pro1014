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
<title>Thêm yêu cầu đặc biệt</title>
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
</style>
</head>
<body>
<div class="row g-0">
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request" class="active"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-plus-circle"></i> Thêm yêu cầu đặc biệt
      </h3>
      <a href="index.php?act=special-request" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
      </a>
    </div>

    <div class="card p-4">
      <form action="index.php?act=special-request-store" method="POST">
        <div class="mb-3">
          <label class="form-label">Chọn booking</label>
          <select name="booking_id" class="form-select" required>
            <option value="">-- Chọn booking --</option>
            <?php foreach($bookings as $booking): ?>
              <option value="<?= $booking['id'] ?>">
                <?= htmlspecialchars($booking['customer_name']) ?> - 
                <?= htmlspecialchars($booking['tour_title'] ?? 'N/A') ?> - 
                <?= date('d/m/Y', strtotime($booking['created_at'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Loại yêu cầu</label>
          <select name="request_type" class="form-select" required>
            <option value="">-- Chọn loại --</option>
            <option value="diet">Ăn uống (ăn chay, kiêng, dị ứng...)</option>
            <option value="medical">Y tế (bệnh lý, thuốc, điều kiện sức khỏe...)</option>
            <option value="accessibility">Khả năng tiếp cận (xe lăn, hỗ trợ di chuyển...)</option>
            <option value="other">Khác (phòng nghỉ, yêu cầu đặc biệt khác...)</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Mô tả chi tiết</label>
          <textarea name="description" class="form-control" rows="5" required 
                    placeholder="Mô tả chi tiết yêu cầu đặc biệt của khách hàng..."></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Trạng thái</label>
          <select name="status" class="form-select">
            <option value="pending">Chờ xử lý</option>
            <option value="confirmed">Đã xác nhận</option>
            <option value="completed">Hoàn thành</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Ghi chú nội bộ</label>
          <textarea name="notes" class="form-control" rows="3" 
                    placeholder="Ghi chú cho nhân viên và HDV..."></textarea>
        </div>

        <button type="submit" class="btn btn-success me-2">
          <i class="bi bi-save"></i> Lưu
        </button>
        <a href="index.php?act=special-request" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

