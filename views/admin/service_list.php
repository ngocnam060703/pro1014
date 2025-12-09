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
<title>Quản lý dịch vụ</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
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
    text-align: center;
    margin-bottom: 30px;
}
.sidebar a {
    color: #ecf0f1;
    padding: 15px 20px;
    display: block;
    text-decoration: none;
    font-size: 15px;
    border-left: 3px solid transparent;
    transition: all 0.3s;
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
    margin-bottom: 20px;
}
.table-container {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.table thead th {
    border: none;
    padding: 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}
.table tbody tr {
    transition: all 0.3s;
    border-bottom: 1px solid #e9ecef;
}
.table tbody tr:hover {
    background: linear-gradient(to right, #f8f9ff 0%, #fff 50%);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.table tbody td {
    padding: 15px;
    vertical-align: middle;
}
.btn-modern {
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
}
.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
}
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-in {
    animation: fadeIn 0.6s ease-out;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}
.empty-state i {
    font-size: 5rem;
    opacity: 0.3;
    margin-bottom: 20px;
}
</style>
</head>

<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service" class="active"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
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
        <div>
          <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-grid"></i> Danh sách dịch vụ</h3>
          <p class="text-muted mb-0">Tổng số: <strong><?= count($listService ?? []) ?></strong> dịch vụ</p>
        </div>
        <div>
          <a href="index.php?act=service-create" class="btn btn-primary btn-modern me-2">
            <i class="bi bi-plus-circle"></i> Thêm dịch vụ
          </a>
          <a href="index.php?act=dashboard" class="btn btn-secondary btn-modern">
            <i class="bi bi-arrow-left-circle"></i> Quay lại
          </a>
        </div>
      </div>

      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> <?= $_SESSION['message']; unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Bảng danh sách -->
      <div class="table-container fade-in">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Chuyến đi</th>
                <th>Tên dịch vụ</th>
                <th>Trạng thái</th>
                <th>Ghi chú</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
            <?php if(!empty($listService)): ?>
              <?php foreach($listService as $sv): 
                // Xác định badge cho trạng thái
                $statusBadge = 'badge-secondary';
                $statusText = $sv['status'] ?? 'N/A';
                if (stripos($statusText, 'active') !== false || stripos($statusText, 'hoạt động') !== false) {
                    $statusBadge = 'badge-success';
                } elseif (stripos($statusText, 'inactive') !== false || stripos($statusText, 'tạm dừng') !== false) {
                    $statusBadge = 'badge-warning';
                } elseif (stripos($statusText, 'cancel') !== false || stripos($statusText, 'hủy') !== false) {
                    $statusBadge = 'badge-danger';
                }
              ?>
                <tr>
                  <td class="fw-bold">#<?= $sv['id'] ?></td>
                  <td>
                    <i class="bi bi-airplane"></i> <?= htmlspecialchars($sv['trip_name'] ?? 'Chưa có tour') ?>
                  </td>
                  <td class="fw-semibold text-primary">
                    <i class="bi bi-star-fill"></i> <?= htmlspecialchars($sv['service_name']) ?>
                  </td>
                  <td>
                    <span class="badge <?= $statusBadge ?> badge-modern"><?= htmlspecialchars($statusText) ?></span>
                  </td>
                  <td>
                    <?php if (!empty($sv['notes'])): ?>
                      <small class="text-muted"><?= htmlspecialchars(substr($sv['notes'], 0, 50)) ?><?= strlen($sv['notes']) > 50 ? '...' : '' ?></small>
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="index.php?act=service-edit&id=<?= $sv['id'] ?>" class="btn btn-warning btn-sm me-1" title="Sửa">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="index.php?act=service-delete&id=<?= $sv['id'] ?>" 
                       onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')" 
                       class="btn btn-danger btn-sm" title="Xóa">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center">
                  <div class="empty-state">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <h5 class="mt-3">Chưa có dịch vụ nào</h5>
                    <p class="text-muted">Hãy thêm dịch vụ mới để bắt đầu</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
