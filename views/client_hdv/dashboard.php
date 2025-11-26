<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Hướng Dẫn Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f7fa">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">HDV – Dashboard</a>
            <a class="btn btn-light" href="?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">Đăng xuất</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Xin chào, <?= $_SESSION['user']['username'] ?>!</h3>

        <p>Chọn chức năng bên dưới:</p>

        <div class="row mt-4">

            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Lịch Tour</h5>
                    <p>Xem những tour bạn được phân công.</p>
                    <a href="?act=hdv_schedule" class="btn btn-primary">Xem lịch</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Nhật ký hành trình</h5>
                    <p>Ghi nhật ký sau mỗi tour.</p>
                    <a href="?act=hdv_journal" class="btn btn-primary">Nhật ký</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Báo cáo sự cố</h5>
                    <p>Báo cáo các vấn đề phát sinh trong tour.</p>
                    <a href="?act=hdv_incident" class="btn btn-primary">Báo cáo</a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
