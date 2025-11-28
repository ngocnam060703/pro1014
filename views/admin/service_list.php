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
.table thead {
    background: linear-gradient(to right, #5a5afc, #6c63ff);
    color: #fff;
}
.btn-primary, .btn-warning, .btn-danger {
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
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service" class="active"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary"><i class="bi bi-grid"></i> Danh sách dịch vụ</h3>
      <div>
        <a href="index.php?act=service-create" class="btn btn-primary me-2">
          <i class="bi bi-plus-circle"></i> Thêm dịch vụ
        </a>
        <a href="index.php?act=dashboard" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại Dashboard
        </a>
      </div>
    </div>

    <div class="card p-3">
      <div class="card-body p-0">
        <table class="table table-bordered table-hover align-middle mb-0">
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
            <?php foreach($listService as $sv): ?>
              <tr>
                <td><?= $sv['id'] ?></td>
                <td><?= $sv['trip_name'] ?? 'Chưa có tour' ?></td>
                <td><?= $sv['service_name'] ?></td>
                <td><?= $sv['status'] ?></td>
                <td><?= $sv['notes'] ?></td>
                <td class="text-center d-flex justify-content-center gap-1">
                  <a href="index.php?act=service-edit&id=<?= $sv['id'] ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="index.php?act=service-delete&id=<?= $sv['id'] ?>" onclick="return confirm('Xóa dịch vụ này?')" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-3">
                <i class="bi bi-info-circle"></i> Chưa có dịch vụ nào
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
