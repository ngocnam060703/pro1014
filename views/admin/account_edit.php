<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sửa tài khoản</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="row g-0">
  <div class="col-2" style="background:#343a40; min-height:100vh; padding-top:20px; color:#ddd;">
    <h4 class="text-center">ADMIN</h4>
    <a href="index.php?act=dashboard" style="color:#ddd; display:block; padding:8px;">Dashboard</a>
    <a href="index.php?act=account" style="color:#ddd; display:block; padding:8px;">Quản lý tài khoản</a>
  </div>
  <div class="col-10 p-4">
    <h3>Sửa tài khoản</h3>
    <form action="index.php?act=account-update" method="POST">
      <input type="hidden" name="id" value="<?= $user['id'] ?>">
      
      <div class="mb-3">
        <label class="form-label">Tên đăng nhập</label>
        <input name="username" class="form-control" required value="<?= $user['username'] ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Họ và tên</label>
        <input name="full_name" class="form-control" required value="<?= $user['full_name'] ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" required value="<?= $user['email'] ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input name="phone" class="form-control" value="<?= $user['phone'] ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Mật khẩu (để trống nếu không đổi)</label>
        <input name="password" type="password" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Vai trò</label>
        <select name="role" class="form-select">
          <option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>Admin</option>
          <option value="user" <?= $user['role']=='user' ? 'selected' : '' ?>>User</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
          <option value="active" <?= $user['status']=='active' ? 'selected' : '' ?>>Active</option>
          <option value="inactive" <?= $user['status']=='inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>

      <button class="btn btn-success">Cập nhật</button>
      <a href="index.php?act=account" class="btn btn-secondary">Quay lại</a>
    </form>
  </div>
</div>
</body>
</html>
