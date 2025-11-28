<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý lịch trình</title>

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
.btn-warning {
    border-radius: 50%;
}
.btn-danger {
    border-radius: 50%;
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
    <a href="index.php?act=schedule" style="color:#fff; background:#495057; border-left:3px solid #0d6efd;">
      <i class="bi bi-calendar-event"></i> Quản lý lịch trình
    </a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>

    <a href="index.php?act=guide-assign">
    <i class="bi bi-card-list"></i> Phân công HDV
    </a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-calendar-week"></i> Danh sách lịch trình
      </h3>

      <div>
        <a href="index.php?act=schedule-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm lịch trình
        </a>
        <a href="index.php?act=dashboard" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại Dashboard
        </a>
      </div>
    </div>

    <div class="card">
      <div class="card-body p-4">

        <table class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên Tour</th>
              <th>Ngày & giờ khởi hành</th>
              <th>Điểm tập trung</th>
              <th>Số chỗ còn</th>
              <th>Ghi chú</th>
              <th class="text-center">Hành động</th>
            </tr>
          </thead>

          <tbody>
          <?php if (!empty($listSchedule)): ?>
            <?php foreach ($listSchedule as $schedule): ?>
            <tr>
              <td><?= $schedule['id'] ?></td>

              <td class="fw-semibold text-primary">
                <?= $schedule['tour_name'] ?? '—' ?>
              </td>

              <td>
                <?php if (!empty($schedule['departure_time'])): ?>
                  📅 <?= date('d/m/Y', strtotime($schedule['departure_time'])) ?><br>
                  ⏰ <?= date('H:i', strtotime($schedule['departure_time'])) ?>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>

              <td><?= $schedule['meeting_point'] ?></td>
              <td class="text-center fw-bold text-success"><?= $schedule['seats_available'] ?></td>
              <td><?= $schedule['notes'] ?></td>

              <td class="text-center">
                <a href="index.php?act=schedule-edit&id=<?= $schedule['id'] ?>" 
                   class="btn btn-warning btn-sm me-1">
                  <i class="bi bi-pencil"></i>
                </a>

                <a href="index.php?act=schedule-delete&id=<?= $schedule['id'] ?>" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa lịch trình ID <?= $schedule['id'] ?> không?')" 
                   class="btn btn-danger btn-sm">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-3">
                <i class="bi bi-info-circle"></i> Hiện chưa có lịch trình nào
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>

      </div>
    </div>

  </div>
</div>

</body>
</html>
