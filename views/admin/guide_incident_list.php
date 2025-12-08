<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Danh sách sự cố HDV</title>

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
.btn-sm {
    padding: 4px 7px;
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
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident" class="active">
      <i class="bi bi-exclamation-triangle"></i> Danh sách sự cố
    </a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-exclamation-triangle"></i> Danh sách sự cố HDV
      </h3>

      <div>
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
              <th>Tên HDV</th>
              <th>Chuyến đi</th>
              <th>Loại sự cố</th>
              <th>Mức độ</th>
              <th>Mô tả</th>
              <th>Giải pháp</th>
              <th>Ảnh</th>
              <th>Hành động</th>
            </tr>
          </thead>

          <tbody>
          <?php if (!empty($incidents)): ?>
            <?php foreach ($incidents as $i): ?>
            <tr>
              <td><?= $i['id'] ?></td>
              <td class="fw-semibold text-primary"><?= $i['guide_name'] ?></td>
              <td><?= $i['departure_name'] ?></td>
              <td><?= $i['incident_type'] ?></td>
              <td>
                <?php
                  $severity = $i['severity'] ?? 'low';
                  $badgeClass = [
                    'low' => 'badge bg-info',
                    'medium' => 'badge bg-warning',
                    'high' => 'badge bg-danger'
                  ];
                  $badge = $badgeClass[$severity] ?? 'badge bg-secondary';
                  $severityText = [
                    'low' => 'Thấp',
                    'medium' => 'Trung bình',
                    'high' => 'Cao'
                  ];
                  echo '<span class="' . $badge . '">' . ($severityText[$severity] ?? ucfirst($severity)) . '</span>';
                ?>
              </td>
              <td><?= $i['description'] ?></td>
              <td><?= $i['solution'] ?></td>

              <td>
                <?php if(!empty($i['photos'])): ?>
                  <img src="<?= $i['photos'] ?>" width="60" height="60" style="object-fit:cover; border-radius:8px;">
                <?php else: ?>
                  <span class="text-muted">Chưa có</span>
                <?php endif; ?>
              </td>

              <td>
                <a href="index.php?act=guide-incident-detail&id=<?= $i['id'] ?>" 
                   class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>

                <a href="index.php?act=guide-incident-edit&id=<?= $i['id'] ?>" 
                   class="btn btn-warning btn-sm text-white"><i class="bi bi-pencil-square"></i></a>

                <a href="index.php?act=guide-incident-delete&id=<?= $i['id'] ?>" 
                   onclick="return confirm('Bạn chắc chắn muốn xóa sự cố này?')"
                   class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
              </td>

            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="text-center text-muted py-3">
                <i class="bi bi-info-circle"></i> Hiện chưa có sự cố nào
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
