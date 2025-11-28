<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Hướng dẫn viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background:#f5f6fa">
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Sửa Hướng dẫn viên</h3>

        <form action="index.php?act=guide-update" method="post">
            <!-- ID ẩn -->
            <input type="hidden" name="id" value="<?= $guide['id'] ?>">

            <div class="mb-3">
                <label for="fullname" class="form-label">Tên</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?= $guide['fullname'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= $guide['phone'] ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $guide['email'] ?>">
            </div>

            <div class="mb-3">
                <label for="certificate" class="form-label">Chứng chỉ</label>
                <input type="text" class="form-control" id="certificate" name="certificate" value="<?= $guide['certificate'] ?>">
               
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Lưu thay đổi
            </button>
            <a href="index.php?act=guide" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
</body>
</html>
