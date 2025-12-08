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
    background: linear-gradient(to right, #dfe9f3, #ffffff);
    font-family: 'Segoe UI', sans-serif;
}
.sidebar {
    height: 100vh;
    background: #343a40;
    padding-top: 20px;
}
.sidebar h4 { font-weight: 700; color:#fff; }
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
.sidebar a.active {
    color:#fff;
    background:#495057;
    border-left:3px solid #0d6efd;
}

.content { padding: 30px; }
.card {
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.table thead {
    background: linear-gradient(to right, #5a5afc, #6c63ff);
    color: #fff;
}
.btn-primary {
    background: linear-gradient(45deg,#5a5afc,#fc5a8d);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(45deg,#fc5a8d,#5a5afc);
}
.btn-warning, .btn-danger, .btn-success {
    border-radius: 50%;
}
</style>
</head>

<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>

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

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary"><i class="bi bi-card-list"></i> Danh sách Tour</h3>

      <div>
        <a href="index.php?act=tour-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm tour
        </a>
        <a href="index.php?act=dashboard" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại Dashboard
        </a>
      </div>
    </div>

    <div class="card">
      <div class="card-body p-4">
        <table class="table table-bordered table-hover align-middle text-center">
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
                  'domestic' => ['name' => 'Tour trong nước', 'badge' => 'primary'],
                  'international' => ['name' => 'Tour quốc tế', 'badge' => 'success'],
                  'customized' => ['name' => 'Tour theo yêu cầu', 'badge' => 'warning']
              ];
              return $categories[$category] ?? ['name' => 'Chưa phân loại', 'badge' => 'secondary'];
          }
          ?>
          <?php if (!empty($listTour)): ?>
            <?php foreach ($listTour as $tour): 
              $categoryInfo = getCategoryName($tour['category'] ?? 'domestic');
            ?>
            <tr>
              <td><?= $tour['id'] ?></td>
              <td class="fw-bold text-info"><?= htmlspecialchars($tour['tour_code'] ?? '-') ?></td>
              <td class="fw-semibold text-primary"><?= $tour['title'] ?></td>
              <td>
                <span class="badge bg-<?= $categoryInfo['badge'] ?>">
                  <?= $categoryInfo['name'] ?>
                </span>
              </td>
              <td><?= $tour['departure'] ?></td>
              <td class="fw-bold text-success"><?= number_format($tour['price']) ?> đ</td>
              <td class="fw-bold"><?= $tour['slots'] ?></td>

              <td class="text-center">
                <a href="index.php?act=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-info btn-sm me-1" title="Xem chi tiết">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="btn btn-warning btn-sm me-1">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="index.php?act=tour-delete&id=<?= $tour['id'] ?>" 
                   onclick="return confirm('Bạn có chắc muốn xóa tour ID <?= $tour['id'] ?> không?')" 
                   class="btn btn-danger btn-sm me-1">
                  <i class="bi bi-trash"></i>
                </a>
                <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-success btn-sm" title="Xem lịch trình">
                  <i class="bi bi-calendar-check"></i> Lịch trình
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-3">
                <i class="bi bi-info-circle"></i> Hiện chưa có tour nào
              </td>
            </tr>
          <?php endif; ?>
          </tbody>

        </table>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
