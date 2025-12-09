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
<title>Quản lý Tour</title>

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
.badge-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.price-highlight {
    font-size: 1.1rem;
    font-weight: bold;
    color: #198754;
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
    <a href="index.php?act=tour" class="active"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-card-list"></i> Danh sách Tour</h3>
          <p class="text-muted mb-0">Tổng số: <strong><?= count($listTour ?? []) ?></strong> tour</p>
        </div>
        <div>
          <a href="index.php?act=tour-create" class="btn btn-primary btn-modern me-2">
            <i class="bi bi-plus-circle"></i> Thêm tour
          </a>
          <a href="index.php?act=dashboard" class="btn btn-secondary btn-modern">
            <i class="bi bi-arrow-left-circle"></i> Quay lại
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
                <th>Mã Tour</th>
                <th>Địa điểm</th>
                <th>Danh mục</th>
                <th>Điểm khởi hành</th>
                <th>Giá</th>
                <th>Số chỗ</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            // Hàm hiển thị tên danh mục
            function getCategoryName($category) {
                $categories = [
                    'domestic' => ['name' => 'Tour trong nước', 'badge' => 'badge-primary'],
                    'international' => ['name' => 'Tour quốc tế', 'badge' => 'badge-success'],
                    'customized' => ['name' => 'Tour theo yêu cầu', 'badge' => 'badge-warning'],
                    'specialized_route' => ['name' => 'Chuyên tuyến', 'badge' => 'badge-primary'],
                    'group_tour' => ['name' => 'Chuyên khách đoàn', 'badge' => 'badge-success']
                ];
                return $categories[$category] ?? ['name' => 'Chưa phân loại', 'badge' => 'badge-secondary'];
            }
            ?>
            <?php if (!empty($listTour)): ?>
              <?php foreach ($listTour as $tour): 
                $categoryInfo = getCategoryName($tour['category'] ?? 'domestic');
              ?>
                <tr>
                  <td class="fw-bold">#<?= $tour['id'] ?></td>
                  <td class="fw-bold text-info">
                    <i class="bi bi-tag-fill"></i> <?= htmlspecialchars($tour['tour_code'] ?? '-') ?>
                  </td>
                  <td class="fw-semibold text-primary">
                    <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($tour['title']) ?>
                  </td>
                  <td>
                    <span class="badge <?= $categoryInfo['badge'] ?> badge-modern">
                      <?= $categoryInfo['name'] ?>
                    </span>
                  </td>
                  <td>
                    <i class="bi bi-geo"></i> <?= htmlspecialchars($tour['departure'] ?? '—') ?>
                  </td>
                  <td class="price-highlight">
                    <i class="bi bi-currency-dollar"></i> <?= number_format($tour['price'] ?? 0) ?> đ
                  </td>
                  <td>
                    <span class="badge bg-info"><?= $tour['slots'] ?? 0 ?> chỗ</span>
                  </td>
                  <td class="text-center">
                    <a href="index.php?act=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-info btn-sm me-1" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="btn btn-warning btn-sm me-1" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="index.php?act=tour-delete&id=<?= $tour['id'] ?>" 
                       onclick="return confirm('Bạn có chắc muốn xóa tour ID <?= $tour['id'] ?> không?')" 
                       class="btn btn-danger btn-sm me-1" title="Xóa">
                      <i class="bi bi-trash"></i>
                    </a>
                    <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-success btn-sm" title="Xem lịch trình">
                      <i class="bi bi-calendar-check"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">
                  <div class="empty-state">
                    <i class="bi bi-card-list"></i>
                    <h5 class="mt-3">Hiện chưa có tour nào</h5>
                    <p class="text-muted">Hãy thêm tour mới để bắt đầu</p>
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
