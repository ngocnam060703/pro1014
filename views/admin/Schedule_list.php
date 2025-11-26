<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý lịch trình</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f5f6fa; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; }
.sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
.sidebar a:hover { background: #495057; color: #fff; }
.content { padding: 30px; }
.card-stat { border-radius: 10px; }
</style>
</head>
<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">ADMIN</h4>

    <a href="index.php?act=dashboard">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="index.php?act=account">
      <i class="bi bi-people"></i> Quản lý tài khoản
    </a>

    <a href="index.php?act=guide">
      <i class="bi bi-person-badge"></i> Quản lý nhân viên
    </a>

    <a href="index.php?act=schedule">
      <i class="bi bi-person-badge"></i> Quản lý lịch trình
    </a>
    <a href="index.php?act=service">
        <i class="bi bi-calendar-event"></i> Quản lý dịch vụ

    <a href="index.php?act=tour">
      <i class="bi bi-card-list"></i> Quản lý Tour
    </a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-3">
      <h3 class="fw-bold">Danh sách lịch trình</h3>
      <div>
        <a href="index.php?act=schedule-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm lịch trình
        </a>
        <a href="index.php?act=dashboard" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại Dashboard
        </a>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Tên Tour</th>
              <th>Ngày & giờ khởi hành</th>
              <th>Điểm tập trung</th>
              <th>Số chỗ còn</th>
              <th>Ghi chú</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($listSchedule)): ?>
            <?php foreach ($listSchedule as $schedule): ?>
              <tr>
                <td><?= $schedule['id'] ?></td>
                <td><?= $schedule['tour_name'] ?? 'Chưa có tour' ?></td>
                <td>
                  <?php if (!empty($schedule['departure_time'])): ?>
                    📅 <?= date('d/m/Y', strtotime($schedule['departure_time'])) ?><br>
                    ⏰ <?= date('H:i', strtotime($schedule['departure_time'])) ?>
                  <?php else: ?>
                    📅 00/00/0000<br>
                    ⏰ 00:00
                  <?php endif; ?>
                </td>
                <td><?= $schedule['meeting_point'] ?? '' ?></td>
                <td><?= $schedule['seats_available'] ?? '' ?></td>
                <td><?= $schedule['notes'] ?? '' ?></td>
                <td class="d-flex gap-1">
                  <a href="index.php?act=schedule-edit&id=<?= $schedule['id'] ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
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
              <td colspan="7" class="text-center text-muted">Chưa có lịch trình nào</td>
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
