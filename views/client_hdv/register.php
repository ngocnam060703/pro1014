<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Đăng ký HDV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#eef2f7">
<div class="container mt-5" style="max-width:600px;">
    <div class="card p-4 shadow">
        <h3 class="text-center mb-3">Đăng ký Hướng dẫn viên</h3>

        <?php if (!empty($_SESSION['hdv_register_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['hdv_register_error']; unset($_SESSION['hdv_register_error']); ?></div>
        <?php endif; ?>

        <form action="index.php?act=hdv_register_post" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Chứng chỉ</label>
                <input type="text" name="certificate" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Tên đăng nhập (account_id)</label>
                <input type="text" name="account_id" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nhập lại mật khẩu</label>
                    <input type="password" name="password_confirm" class="form-control" required>
                </div>
            </div>

            <button class="btn btn-success w-100">Đăng ký</button>
        </form>

        <div class="mt-3">
            <a href="index.php?act=hdv_login" class="btn btn-outline-primary w-100">Trở về Đăng nhập</a>
        </div>
    </div>
</div>
</body>
</html>
