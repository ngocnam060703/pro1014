<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết Booking #<?= $booking['id'] ?></title>

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
.sidebar h4 { font-weight: 700; }
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
.content { padding: 30px; }
.card {
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.btn-primary {
    background: linear-gradient(45deg,#5a5afc,#fc5a8d);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(45deg,#fc5a8d,#5a5afc);
}
.btn-success, .btn-secondary {
    border-radius: 6px;
}
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
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking" style="color:#fff; background:#495057; border-left:3px solid #0d6efd;">
      <i class="bi bi-cart"></i> Quản lý Booking
    </a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-cart-check"></i> Chi tiết Booking #<?= $booking['id'] ?>
      </h3>
    </div>

    <div class="card mb-4">
      <div class="card-body p-4">
        <p><strong>Khách hàng:</strong> <?= $booking['customer_name'] ?></p>
        <p><strong>Email:</strong> <?= $booking['customer_email'] ?></p>
        <p><strong>Phone:</strong> <?= $booking['customer_phone'] ?></p>
        <p><strong>Tour:</strong> <?= $booking['tour_title'] ?></p>
        <p><strong>Số lượng:</strong> <?= $booking['num_people'] ?></p>
        <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_price']) ?> đ</p>
        <p><strong>Ngày đặt:</strong> <?= date('d/m/Y', strtotime($booking['booking_date'])) ?></p>
        <p><strong>Trạng thái:</strong> <?= $booking['status'] ?></p>

        <form action="index.php?act=booking-update-status" method="post" class="mt-3">
            <input type="hidden" name="id" value="<?= $booking['id'] ?>">
            <select name="status" class="form-select mb-2 w-25">
                <option value="Pending" <?= $booking['status']=='Pending'?'selected':'' ?>>Pending</option>
                <option value="Confirmed" <?= $booking['status']=='Confirmed'?'selected':'' ?>>Confirmed</option>
                <option value="Cancelled" <?= $booking['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle"></i> Cập nhật trạng thái
            </button>
        </form>

        <a href="index.php?act=booking" class="btn btn-secondary mt-3">
          <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
        </a>
      </div>
    </div>

  </div>
</div>
</body>
</html>
