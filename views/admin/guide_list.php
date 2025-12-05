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
<title>Quản lý Hướng dẫn viên</title>

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
.sidebar h4 { font-weight: 700; color: #fff; }
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
.btn-primary, .btn-warning, .btn-danger {
    border-radius: 50px;
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
    <a href="index.php?act=guide" class="active"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary"><i class="bi bi-person-badge"></i> Danh sách Hướng dẫn viên</h3>
        <a href="index.php?act=guide-create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm nhân viên mới
        </a>
    </div>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <div class="card p-3 mb-3 shadow-sm">
        <form method="get" action="index.php" class="row g-3">
            <input type="hidden" name="act" value="guide">
            <div class="col-md-4">
                <label class="form-label">Phân loại</label>
                <select name="category" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="domestic" <?= (isset($_GET['category']) && $_GET['category'] == 'domestic') ? 'selected' : '' ?>>Tour trong nước</option>
                    <option value="international" <?= (isset($_GET['category']) && $_GET['category'] == 'international') ? 'selected' : '' ?>>Tour quốc tế</option>
                    <option value="specialized_route" <?= (isset($_GET['category']) && $_GET['category'] == 'specialized_route') ? 'selected' : '' ?>>Chuyên tuyến</option>
                    <option value="group_tour" <?= (isset($_GET['category']) && $_GET['category'] == 'group_tour') ? 'selected' : '' ?>>Chuyên khách đoàn</option>
                    <option value="customized" <?= (isset($_GET['category']) && $_GET['category'] == 'customized') ? 'selected' : '' ?>>Tour theo yêu cầu</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>Đang hoạt động</option>
                    <option value="inactive" <?= (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : '' ?>>Tạm nghỉ</option>
                    <option value="on_leave" <?= (isset($_GET['status']) && $_GET['status'] == 'on_leave') ? 'selected' : '' ?>>Nghỉ phép</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel"></i> Lọc</button>
                <a href="index.php?act=guide" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
            </div>
        </form>
    </div>

    <div class="card p-3 shadow-sm">
      <table class="table table-bordered table-hover align-middle mb-0">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Ảnh</th>
                  <th>Tên</th>
                  <th>SĐT</th>
                  <th>Email</th>
                  <th>Kinh nghiệm</th>
                  <th>Đánh giá</th>
                  <th>Trạng thái</th>
                  <th class="text-center">Hành động</th>
              </tr>
          </thead>
          <tbody>
              <?php if (!empty($data)) { ?>
                  <?php foreach ($data as $guide) { 
                      $statusBadge = [
                          'active' => 'bg-success',
                          'inactive' => 'bg-secondary',
                          'on_leave' => 'bg-warning'
                      ];
                      $statusText = [
                          'active' => 'Đang hoạt động',
                          'inactive' => 'Tạm nghỉ',
                          'on_leave' => 'Nghỉ phép'
                      ];
                      $status = $guide['status'] ?? 'active';
                  ?>
                      <tr>
                          <td><?= $guide['id'] ?></td>
                          <td>
                              <?php if (!empty($guide['photo'])): ?>
                                  <img src="<?= htmlspecialchars($guide['photo']) ?>" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                              <?php else: ?>
                                  <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; font-size: 16px;">
                                      <?= strtoupper(substr($guide['fullname'], 0, 1)) ?>
                                  </div>
                              <?php endif; ?>
                          </td>
                          <td class="fw-semibold text-primary">
                              <a href="index.php?act=guide-detail&id=<?= $guide['id'] ?>" class="text-decoration-none">
                                  <?= htmlspecialchars($guide['fullname']) ?>
                              </a>
                          </td>
                          <td><?= htmlspecialchars($guide['phone']) ?></td>
                          <td><?= htmlspecialchars($guide['email']) ?></td>
                          <td>
                              <?= isset($guide['experience_years']) && $guide['experience_years'] > 0 ? $guide['experience_years'] . ' năm' : 'Mới' ?>
                          </td>
                          <td>
                              <?php 
                              $rating = isset($guide['avg_rating']) && $guide['avg_rating'] ? number_format($guide['avg_rating'], 1) : '0.0';
                              $totalTours = $guide['total_tours'] ?? 0;
                              ?>
                              <span class="badge bg-info"><?= $rating ?>/5.0</span>
                              <small class="text-muted">(<?= $totalTours ?> tour)</small>
                          </td>
                          <td>
                              <span class="badge <?= $statusBadge[$status] ?? 'bg-secondary' ?>">
                                  <?= $statusText[$status] ?? 'N/A' ?>
                              </span>
                          </td>
                          <td class="text-center">
                              <a href="index.php?act=guide-detail&id=<?= $guide['id'] ?>" class="btn btn-info btn-sm me-1" title="Chi tiết">
                                  <i class="bi bi-eye"></i>
                              </a>
                              <a href="index.php?act=guide-edit&id=<?= $guide['id'] ?>" class="btn btn-warning btn-sm me-1" title="Sửa">
                                  <i class="bi bi-pencil-square"></i>
                              </a>
                              <a href="index.php?act=guide-delete&id=<?= $guide['id'] ?>" 
                                 onclick="return confirm('Bạn có chắc muốn xóa nhân viên này?')" 
                                 class="btn btn-danger btn-sm" title="Xóa">
                                  <i class="bi bi-trash"></i>
                              </a>
                          </td>
                      </tr>
                  <?php } ?>
              <?php } else { ?>
                  <tr>
                      <td colspan="9" class="text-center text-muted py-3">Không có nhân viên nào</td>
                  </tr>
              <?php } ?>
          </tbody>
      </table>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
