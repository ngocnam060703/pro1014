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
.filter-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}
.avatar-initial {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    font-weight: bold;
    font-size: 18px;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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
    <a href="index.php?act=guide" class="active"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
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
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-person-badge"></i> Danh sách Hướng dẫn viên</h3>
              <p class="text-muted mb-0">Tổng số: <strong><?= count($data ?? []) ?></strong> nhân viên</p>
          </div>
          <a href="index.php?act=guide-create" class="btn btn-primary btn-modern">
              <i class="bi bi-plus-circle"></i> Thêm nhân viên mới
          </a>
      </div>

      <?php if (!empty($_SESSION['message'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
              <i class="bi bi-check-circle"></i> <?= $_SESSION['message'] ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error'] ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <!-- Bộ lọc -->
      <div class="filter-section fade-in">
          <h5 class="mb-3"><i class="bi bi-funnel"></i> Bộ lọc</h5>
          <form method="get" action="index.php" class="row g-3">
              <input type="hidden" name="act" value="guide">
              <div class="col-md-4">
                  <label class="form-label fw-semibold">Phân loại</label>
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
                  <label class="form-label fw-semibold">Trạng thái</label>
                  <select name="status" class="form-select">
                      <option value="">Tất cả</option>
                      <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>Đang hoạt động</option>
                      <option value="inactive" <?= (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : '' ?>>Tạm nghỉ</option>
                      <option value="on_leave" <?= (isset($_GET['status']) && $_GET['status'] == 'on_leave') ? 'selected' : '' ?>>Nghỉ phép</option>
                  </select>
              </div>
              <div class="col-md-4 d-flex align-items-end">
                  <button type="submit" class="btn btn-primary btn-modern me-2"><i class="bi bi-funnel"></i> Lọc</button>
                  <a href="index.php?act=guide" class="btn btn-secondary btn-modern"><i class="bi bi-arrow-clockwise"></i> Reset</a>
              </div>
          </form>
      </div>

      <!-- Bảng danh sách -->
      <div class="table-container fade-in">
          <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
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
                                  'active' => 'badge-success',
                                  'inactive' => 'badge-secondary',
                                  'on_leave' => 'badge-warning'
                              ];
                              $statusText = [
                                  'active' => 'Đang hoạt động',
                                  'inactive' => 'Tạm nghỉ',
                                  'on_leave' => 'Nghỉ phép'
                              ];
                              $status = $guide['status'] ?? 'active';
                          ?>
                              <tr>
                                  <td class="fw-bold">#<?= $guide['id'] ?></td>
                                  <td>
                                      <?php if (!empty($guide['photo'])): ?>
                                          <img src="<?= htmlspecialchars($guide['photo']) ?>" alt="Avatar" class="avatar-circle">
                                      <?php else: ?>
                                          <div class="avatar-initial">
                                              <?= strtoupper(substr($guide['fullname'], 0, 1)) ?>
                                          </div>
                                      <?php endif; ?>
                                  </td>
                                  <td class="fw-semibold text-primary">
                                      <a href="index.php?act=guide-detail&id=<?= $guide['id'] ?>" class="text-decoration-none text-primary">
                                          <?= htmlspecialchars($guide['fullname']) ?>
                                      </a>
                                  </td>
                                  <td><i class="bi bi-telephone"></i> <?= htmlspecialchars($guide['phone']) ?></td>
                                  <td><i class="bi bi-envelope"></i> <?= htmlspecialchars($guide['email']) ?></td>
                                  <td>
                                      <span class="badge bg-info">
                                          <?= isset($guide['experience_years']) && $guide['experience_years'] > 0 ? $guide['experience_years'] . ' năm' : 'Mới' ?>
                                      </span>
                                  </td>
                                  <td>
                                      <?php 
                                      $rating = isset($guide['avg_rating']) && $guide['avg_rating'] ? number_format($guide['avg_rating'], 1) : '0.0';
                                      $totalTours = $guide['total_tours'] ?? 0;
                                      ?>
                                      <span class="badge badge-info badge-modern"><?= $rating ?>/5.0</span>
                                      <small class="text-muted">(<?= $totalTours ?> tour)</small>
                                  </td>
                                  <td>
                                      <span class="badge <?= $statusBadge[$status] ?? 'badge-secondary' ?> badge-modern">
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
                              <td colspan="9" class="text-center">
                                  <div class="empty-state">
                                      <i class="bi bi-person-x"></i>
                                      <h5 class="mt-3">Không có nhân viên nào</h5>
                                      <p class="text-muted">Hãy thêm nhân viên mới để bắt đầu</p>
                                  </div>
                              </td>
                          </tr>
                      <?php } ?>
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
