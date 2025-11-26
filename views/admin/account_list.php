<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý tài khoản</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f5f6fa; }
    .sidebar { height:100vh; background:#343a40; padding-top:20px; }
    .sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
    .sidebar a:hover{ background:#495057; color:#fff; }
    .content{ padding:30px; }
    .table th{ background:#0d6efd; color:#fff; }
  </style>
</head>
<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">ADMIN</h4>

    <a href="index.php?act=dashboard">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="index.php?act=account">
      <i class="bi bi-people"></i> Quản lý tài khoản
    </a>

    <a href="index.php?act=guide">
      <i class="bi bi-person-badge"></i> Quản lý nhân viên
    </a>
   <a href="index.php?act=schedule">
      <i class="bi bi-calendar-event"></i> Quản lý lịch trình 
    </a>

        <a href="index.php?act=service">
        <i class="bi bi-calendar-event"></i> Quản lý dịch vụ
    <a href="index.php?act=tour">
      <i class="bi bi-card-list"></i> Quản lý Tour
    </a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-3">
      <h3>Danh sách tài khoản</h3>
      <a href="index.php?act=account-create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Thêm tài khoản</a>
    </div>

    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Họ & Tên</th>
          <th>Email</th>
          <th>Vai trò</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)) { foreach ($users as $u) { ?>
          <tr>
            <td><?= htmlspecialchars($u['id'] ?? '') ?></td>
            <td><?= htmlspecialchars($u['name'] ?? $u['full_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($u['role'] ?? '') ?></td>
            <td><?= htmlspecialchars($u['status'] ?? '') ?></td>
            <td>
              <a href="index.php?act=account-edit&id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i> Sửa
              </a>
              <a href="index.php?act=account-delete&id=<?= $u['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i> Xóa
              </a>
            </td>
          </tr>
        <?php } } else { ?>
          <tr><td colspan="6" class="text-center text-muted">Không có tài khoản</td></tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
