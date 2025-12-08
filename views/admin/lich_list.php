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
    <title>Lịch khởi hành - <?= htmlspecialchars($tour['title']) ?></title>
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
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-calendar-check"></i> Lịch Khởi Hành: <?= htmlspecialchars($tour['title']) ?>
      </h3>
      <div>
        <a href="index.php?act=lich-create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm Lịch
        </a>
        <a href="index.php?act=tour" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại Tour
        </a>
      </div>
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

    <div class="card">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead>
              <tr>
                <th>Mã lịch</th>
                <th>Ngày & giờ khởi hành</th>
                <th>Điểm gặp</th>
                <th>Số chỗ</th>
                <th>Ghi chú</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($listLich)): ?>
                <?php foreach($listLich as $lich): ?>
                  <tr>
                    <td class="fw-bold">#<?= $lich['id'] ?></td>
                    <td>
                      <?php if (!empty($lich['departure_time'])): ?>
                        📅 <?= date('d/m/Y', strtotime($lich['departure_time'])) ?><br>
                        ⏰ <?= date('H:i', strtotime($lich['departure_time'])) ?>
                      <?php else: ?>
                        <span class="text-muted">Chưa có</span>
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($lich['meeting_point'] ?? '—') ?></td>
                    <td class="text-center">
                      <span class="badge bg-info"><?= $lich['seats_available'] ?? 0 ?> chỗ</span>
                    </td>
                    <td>
                      <small><?= !empty($lich['notes']) ? htmlspecialchars($lich['notes']) : '—' ?></small>
                    </td>
                    <td class="text-center">
                      <a href="index.php?act=schedule-detail&id=<?= $lich['id'] ?>" 
                         class="btn btn-info btn-sm me-1" title="Chi tiết">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="index.php?act=lich-edit&id=<?= $lich['id'] ?>" 
                         class="btn btn-warning btn-sm me-1" title="Sửa">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="index.php?act=lich-delete&id=<?= $lich['id'] ?>" 
                         class="btn btn-danger btn-sm" 
                         title="Xóa"
                         onclick="return confirm('Bạn có chắc muốn xóa lịch #<?= $lich['id'] ?> không?')">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-3">
                    <i class="bi bi-info-circle"></i> Chưa có lịch khởi hành nào cho tour này
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
