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
<title>Quản lý tài khoản</title>
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
.sidebar h4 { 
    font-weight: 700; 
    color: #fff; 
}
.sidebar a {
    color: #ecf0f1;
    padding: 15px 20px;
    display: block;
    text-decoration: none;
    font-size: 15px;
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
    color: #fff;
    background: rgba(52, 152, 219, 0.2);
    border-left: 3px solid #3498db;
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
.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    padding: 15px;
    font-weight: 600;
    text-align: center;
}
.table tbody td {
    padding: 15px;
    vertical-align: middle;
    text-align: center;
}
.table tbody tr {
    transition: all 0.3s;
    border-bottom: 1px solid #e9ecef;
}
.table tbody tr:hover {
    background: linear-gradient(90deg, #e7f1ff 0%, #f0f8ff 100%);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    transition: all 0.3s;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}
.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    border: none;
    border-radius: 8px;
    transition: all 0.3s;
}
.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(255, 193, 7, 0.4);
}
.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
    border: none;
    border-radius: 8px;
    transition: all 0.3s;
}
.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(220, 53, 69, 0.4);
}
.badge-role {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
}
.badge-admin {
    background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
    color: #fff;
}
.badge-user {
    background: linear-gradient(135deg, #0dcaf0 0%, #0a58ca 100%);
    color: #fff;
}
.badge-status {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
}
.badge-active {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    color: #fff;
}
.badge-inactive {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: #fff;
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
    <h4 class="text-center mb-4 fw-bold">ADMIN</h4>
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
          <i class="bi bi-people"></i> Danh sách tài khoản
        </h3>
        <a href="index.php?act=account-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm tài khoản
        </a>
      </div>

      <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
          <i class="bi bi-check-circle"></i> <?= $_SESSION['message'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
          <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <div class="table-responsive fade-in">
        <table class="table table-bordered table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th><i class="bi bi-person"></i> Họ & Tên</th>
              <th><i class="bi bi-envelope"></i> Email</th>
              <th><i class="bi bi-shield-check"></i> Vai trò</th>
              <th><i class="bi bi-circle-fill"></i> Trạng thái</th>
              <th class="text-center"><i class="bi bi-gear"></i> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($users)) { foreach ($users as $u) { ?>
              <tr>
                <td><strong><?= htmlspecialchars($u['id'] ?? '') ?></strong></td>
                <td class="fw-semibold text-primary">
                  <i class="bi bi-person-circle"></i> <?= htmlspecialchars($u['name'] ?? $u['full_name'] ?? '') ?>
                </td>
                <td>
                  <i class="bi bi-envelope-at"></i> <?= htmlspecialchars($u['email'] ?? '') ?>
                </td>
                <td>
                  <?php 
                    $role = htmlspecialchars($u['role'] ?? '');
                    $badgeClass = ($role == 'admin') ? 'badge-admin' : 'badge-user';
                  ?>
                  <span class="badge badge-role <?= $badgeClass ?>">
                    <i class="bi bi-<?= ($role == 'admin') ? 'shield-check' : 'person' ?>"></i> <?= ucfirst($role) ?>
                  </span>
                </td>
                <td>
                  <?php 
                    $status = htmlspecialchars($u['status'] ?? 'inactive');
                    $statusBadge = ($status == 'active' || $status == 'Active') ? 'badge-active' : 'badge-inactive';
                    $statusText = ($status == 'active' || $status == 'Active') ? 'Hoạt động' : 'Không hoạt động';
                  ?>
                  <span class="badge badge-status <?= $statusBadge ?>">
                    <i class="bi bi-<?= ($status == 'active' || $status == 'Active') ? 'check-circle' : 'x-circle' ?>"></i> <?= $statusText ?>
                  </span>
                </td>
                <td class="text-center">
                  <a href="index.php?act=account-edit&id=<?= $u['id'] ?>" class="btn btn-warning btn-sm me-1" title="Sửa">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="index.php?act=account-delete&id=<?= $u['id'] ?>" 
                     onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')" 
                     class="btn btn-danger btn-sm" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php } } else { ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                  <p class="mt-3 mb-0">Không có tài khoản nào</p>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
