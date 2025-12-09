<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$user = $user ?? ['username'=>'','full_name'=>'','email'=>'','phone'=>'','role'=>'user','status'=>'active'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($user['id']) ? 'Sửa tài khoản' : 'Thêm tài khoản' ?></title>
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
}
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s;
}
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 12px 30px;
    transition: all 0.3s;
    font-weight: 600;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}
.btn-secondary {
    border-radius: 10px;
    padding: 12px 30px;
    transition: all 0.3s;
}
.btn-secondary:hover {
    transform: translateY(-2px);
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
  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4 fw-bold">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account" class="active"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
          <i class="bi bi-person-circle"></i> <?= isset($user['id']) ? 'Sửa tài khoản' : 'Thêm tài khoản' ?>
        </h3>
        <a href="index.php?act=account" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
        </a>
      </div>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
          <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
          <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['message']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <form action="index.php?act=<?= isset($user['id']) ? 'account-update' : 'account-store' ?>" method="post" class="fade-in">
        <?php if(isset($user['id'])): ?>
          <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <?php endif; ?>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-person-badge"></i> Tên đăng nhập
            </label>
            <input type="text" name="username" class="form-control" 
                   value="<?= htmlspecialchars($user['username']) ?>" 
                   placeholder="Nhập tên đăng nhập" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-person"></i> Họ & Tên
            </label>
            <input type="text" name="full_name" class="form-control" 
                   value="<?= htmlspecialchars($user['full_name']) ?>" 
                   placeholder="Nhập họ và tên" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" class="form-control" 
                   value="<?= htmlspecialchars($user['email']) ?>" 
                   placeholder="example@email.com" required>
            <small class="text-muted"><i class="bi bi-info-circle"></i> Email phải là duy nhất</small>
          </div>

          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-telephone"></i> Số điện thoại
            </label>
            <input type="text" name="phone" class="form-control" 
                   value="<?= htmlspecialchars($user['phone']) ?>" 
                   placeholder="Nhập số điện thoại">
          </div>

          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-lock"></i> Mật khẩu <?= isset($user['id']) ? '(để trống nếu không đổi)' : '' ?>
            </label>
            <input type="password" name="password" class="form-control" 
                   placeholder="<?= isset($user['id']) ? 'Để trống nếu không đổi' : 'Nhập mật khẩu' ?>" 
                   <?= isset($user['id']) ? '' : 'required' ?>>
            <?php if(!isset($user['id'])): ?>
              <small class="text-muted"><i class="bi bi-shield-check"></i> Mật khẩu tối thiểu 6 ký tự</small>
            <?php endif; ?>
          </div>

          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-shield-check"></i> Vai trò
            </label>
            <select name="role" class="form-select">
              <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>
                Admin
              </option>
              <option value="user" <?= $user['role']=='user'?'selected':'' ?>>
                User
              </option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-circle-fill"></i> Trạng thái
            </label>
            <select name="status" class="form-select">
              <option value="active" <?= $user['status']=='active'?'selected':'' ?>>
                Hoạt động
              </option>
              <option value="inactive" <?= $user['status']=='inactive'?'selected':'' ?>>
                Không hoạt động
              </option>
            </select>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> <?= isset($user['id']) ? 'Cập nhật' : 'Thêm' ?> tài khoản
          </button>
          <a href="index.php?act=account" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
