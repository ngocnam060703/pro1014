<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý Booking</title>

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
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.price-highlight {
    font-weight: bold;
    color: #198754;
    font-size: 1.05rem;
}
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
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking" class="active"><i class="bi bi-cart"></i> Quản lý Booking</a>
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
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-cart-check"></i> Quản lý Booking</h3>
              <p class="text-muted mb-0">Tổng số: <strong><?= count($bookings ?? []) ?></strong> booking</p>
          </div>
          <div>
              <a href="index.php?act=booking-create" class="btn btn-primary btn-modern me-2">
                  <i class="bi bi-plus-circle"></i> Tạo Booking Mới
              </a>
              <a href="index.php?act=dashboard" class="btn btn-secondary btn-modern">
                  <i class="bi bi-arrow-left-circle"></i> Quay lại Dashboard
              </a>
          </div>
      </div>

      <!-- Bảng danh sách -->
      <div class="table-container fade-in">
          <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Khách hàng</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Tour</th>
                          <th>Số lượng</th>
                          <th>Tổng tiền</th>
                          <th>Ngày đặt</th>
                          <th>Trạng thái</th>
                          <th class="text-center">Hành động</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php if (!empty($bookings)): ?>
                      <?php foreach ($bookings as $b): ?>
                          <tr>
                              <td class="fw-bold">#<?= $b['id'] ?></td>
                              <td class="fw-semibold text-primary">
                                  <i class="bi bi-person"></i> <?= htmlspecialchars($b['customer_name']) ?>
                              </td>
                              <td><i class="bi bi-envelope"></i> <?= htmlspecialchars($b['customer_email']) ?></td>
                              <td><i class="bi bi-telephone"></i> <?= htmlspecialchars($b['customer_phone']) ?></td>
                              <td>
                                  <i class="bi bi-map"></i> <?= htmlspecialchars($b['tour_title']) ?>
                              </td>
                              <td class="text-center">
                                  <span class="badge bg-info"><?= $b['num_people'] ?> người</span>
                              </td>
                              <td class="price-highlight text-end">
                                  <i class="bi bi-currency-dollar"></i> <?= number_format($b['total_price']) ?> đ
                              </td>
                              <td>
                                  <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($b['booking_date'])) ?>
                              </td>
                              <td>
                                  <?php
                                  $status = strtolower($b['status'] ?? 'pending');
                                  $badges = [
                                      'pending' => '<span class="badge badge-modern badge-warning">Chờ xác nhận</span>',
                                      'deposit_paid' => '<span class="badge badge-modern badge-info">Đã cọc</span>',
                                      'completed' => '<span class="badge badge-modern badge-success">Hoàn tất</span>',
                                      'cancelled' => '<span class="badge badge-modern badge-danger">Hủy</span>'
                                  ];
                                  echo $badges[$status] ?? '<span class="badge badge-modern badge-secondary">' . htmlspecialchars($b['status']) . '</span>';
                                  ?>
                              </td>
                              <td class="text-center">
                                  <a href="index.php?act=booking-detail&id=<?= $b['id'] ?>" class="btn btn-info btn-sm me-1" title="Chi tiết">
                                      <i class="bi bi-eye"></i>
                                  </a>
                                  <a href="index.php?act=booking-delete&id=<?= $b['id'] ?>" 
                                     onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?')" 
                                     class="btn btn-danger btn-sm" title="Xóa">
                                      <i class="bi bi-trash"></i>
                                  </a>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="10" class="text-center">
                              <div class="empty-state">
                                  <i class="bi bi-cart-x"></i>
                                  <h5 class="mt-3">Hiện chưa có booking nào</h5>
                                  <p class="text-muted">Hãy tạo booking mới để bắt đầu</p>
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
