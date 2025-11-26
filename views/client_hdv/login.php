<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Hướng Dẫn Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#eef2f7">

<div class="container mt-5" style="max-width:450px;">
    <div class="card p-4 shadow">
        <h3 class="text-center mb-3">Đăng nhập HDV</h3>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Sai tài khoản hoặc mật khẩu!</div>
        <?php endif; ?>

        <form action="index.php?act=hdv_login_post" method="POST">
            <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" name="account_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Đăng nhập</button>
        </form>
        <div class="mt-3">
        <a href="index.php?act=loginForm" class="btn btn-outline-success w-100">Đăng nhập Admin</a>
        </div>
    </div>
</div>

</body>
</html>
