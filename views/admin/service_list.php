<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý dịch vụ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background: #f5f6fa; }
    .sidebar { height: 100vh; background: #343a40; padding-top: 20px; }
    .sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
    .sidebar a:hover { background: #495057; color: #fff; }
    .content { padding: 30px; }
    .table th { background: #0d6efd; color: #fff; }
</style>
</head>
<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">ADMIN</h4>

    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-calendar-event"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="d-flex justify-content-between mb-3">
      <h3 class="fw-bold">Danh sách dịch vụ</h3>

      <div>
        <a href="index.php?act=service-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm dịch vụ
        </a>
        
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">

        <table class="table table-bordered table-hover">
          

          <table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>ID</th>
      <th>Chuyến đi</th>
      <th>Tên dịch vụ</th>
      <th>Trạng thái</th>
      <th>Ghi chú</th>
      <th>Hành động</th>
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
          <td class="d-flex gap-1">
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
        <td colspan="6" class="text-center text-muted">Chưa có dịch vụ nào</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>


      </div>
    </div>

  </div>
</div>
</body>
</html>
