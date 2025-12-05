<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Khi sửa, gán mặc định để tránh lỗi
$guide = $guide ?? ['id'=>'', 'fullname'=>'', 'phone'=>'', 'email'=>'', 'certificate'=>''];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa Hướng dẫn viên</title>
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
.btn-primary { background: linear-gradient(45deg,#5a5afc,#fc5a8d); border: none; }
.btn-primary:hover { background: linear-gradient(45deg,#fc5a8d,#5a5afc); }
.btn-success { background: linear-gradient(45deg,#28a745,#218838); border: none; color:#fff; }
.btn-success:hover { background: linear-gradient(45deg,#218838,#28a745); color:#fff; }
</style>
</head>
<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide" class="active"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary"><i class="bi bi-person-plus"></i> Sửa Hướng dẫn viên</h3>
        <a href="index.php?act=guide" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card p-4">
      <form action="index.php?act=guide-update" method="post">
          <input type="hidden" name="id" value="<?= $guide['id'] ?>">

          <div class="mb-3">
              <label class="form-label">Tên</label>
              <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($guide['fullname']) ?>" required>
          </div>

          <div class="mb-3">
              <label class="form-label">Số điện thoại</label>
              <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($guide['phone']) ?>">
          </div>

          <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($guide['email']) ?>">
          </div>

          <div class="mb-3">
              <label class="form-label">Chứng chỉ</label>
              <input type="text" name="certificate" class="form-control" value="<?= htmlspecialchars($guide['certificate']) ?>">
          </div>

          <button type="submit" class="btn btn-success">
              <i class="bi bi-save"></i> Lưu thay đổi
          </button>
          <a href="index.php?act=guide" class="btn btn-secondary">Hủy</a>
      </form>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
