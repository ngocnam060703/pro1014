<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Danh sách phân công HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#343a40; padding-top:20px; }
.sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
.sidebar a:hover{ background:#495057; color:#fff; border-left:3px solid #0d6efd; }
.content{ padding:30px; }
.card-container{ background:#fff; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,0.1); max-width:1400px; margin:auto; }
.table thead th{ background:#0d6efd; color:#fff; text-align:center; }
.table tbody td{ text-align:center; vertical-align:middle; }
.table tbody tr:hover{ background:#e7f1ff; transition:0.3s; }
.btn-add{ background: linear-gradient(45deg,#0d6efd,#0a58ca); color:#fff; font-weight:600; }
.btn-add:hover{ background: linear-gradient(45deg,#0a58ca,#0d6efd); }
.badge-scheduled { background: #ffc107; color: #212529; }
.badge-in_progress { background: #0dcaf0; color: #fff; }
.badge-completed { background: #198754; color: #fff; }
.badge-paused { background: #6c757d; color: #fff; }
.badge-cancelled { background: #dc3545; color: #fff; }
.action-btn{ margin:0 2px; padding:6px 12px; border-radius:8px; }
.action-btn.edit{ background:#0d6efd; color:#fff; }
.action-btn.edit:hover{ background:#084298; }
.action-btn.delete{ background:#dc3545; color:#fff; }
.action-btn.delete:hover{ background:#a71d2a; }
.action-btn.detail{ background:#198754; color:#fff; }
.action-btn.detail:hover{ background:#146c43; }
.filter-section { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
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
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign" style="color:#fff; background:#495057; border-left:3px solid #0d6efd;"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-list-check"></i> Danh sách phân công HDV</h3>
        <a href="index.php?act=guide-assign-create" class="btn btn-add"><i class="bi bi-plus-circle"></i> Thêm phân công</a>
      </div>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <?= $_SESSION['message']; unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Bộ lọc và tìm kiếm -->
      <div class="filter-section">
        <form method="GET" action="index.php" class="row g-3">
          <input type="hidden" name="act" value="guide-assign">
          
          <div class="col-md-3">
            <label class="form-label">Tìm kiếm (HDV/Tour)</label>
            <input type="text" name="search" class="form-control" placeholder="Nhập tên HDV hoặc tour..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          </div>

          <div class="col-md-2">
            <label class="form-label">Lọc theo HDV</label>
            <select name="guide_id" class="form-select">
              <option value="">-- Tất cả --</option>
              <?php foreach($guides ?? [] as $g): ?>
                <option value="<?= $g['id'] ?>" <?= (isset($_GET['guide_id']) && $_GET['guide_id'] == $g['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($g['fullname']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Lọc theo Tour</label>
            <select name="tour_id" class="form-select">
              <option value="">-- Tất cả --</option>
              <?php foreach($tours ?? [] as $t): ?>
                <option value="<?= $t['id'] ?>" <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $t['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($t['title']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Lọc theo trạng thái</label>
            <select name="status" class="form-select">
              <option value="">-- Tất cả --</option>
              <option value="scheduled" <?= (isset($_GET['status']) && $_GET['status'] == 'scheduled') ? 'selected' : '' ?>>Chưa bắt đầu</option>
              <option value="in_progress" <?= (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'selected' : '' ?>>Đang chạy</option>
              <option value="paused" <?= (isset($_GET['status']) && $_GET['status'] == 'paused') ? 'selected' : '' ?>>Tạm dừng</option>
              <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>Đã kết thúc</option>
              <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Lọc theo ngày</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
          </div>

          <div class="col-md-1">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Lọc</button>
          </div>
        </form>
      </div>

      <table class="table table-hover table-bordered align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên tour</th>
            <th>Ngày khởi hành - Ngày kết thúc</th>
            <th>HDV được phân công</th>
            <th>Trạng thái phân công</th>
            <th>Ghi chú</th>
            <th>Thời gian phân công</th>
            <th>Người phân công</th>
            <th>Số lượng khách</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
<?php if(!empty($data)): ?>
  <?php foreach($data as $row): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['tour_title'] ?? 'Chưa có') ?></td>
    <td>
      <?php if(!empty($row['departure_time'])): ?>
        <strong>Khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($row['departure_time'])) ?><br>
      <?php endif; ?>
      <?php if(!empty($row['end_date'])): ?>
        <strong>Kết thúc:</strong> <?= date('d/m/Y', strtotime($row['end_date'])) ?>
        <?php if(!empty($row['end_time'])): ?>
          <?= date('H:i', strtotime($row['end_time'])) ?>
        <?php endif; ?>
      <?php else: ?>
        <span class="text-muted">Tour 1 ngày</span>
      <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($row['guide_name'] ?? 'Chưa có') ?></td>
    <td>
      <?php 
        $status = $row['status'] ?? 'scheduled';
        $badge = [
          'scheduled'=>'badge-scheduled',
          'in_progress'=>'badge-in_progress',
          'completed'=>'badge-completed',
          'paused'=>'badge-paused',
          'cancelled'=>'badge-cancelled'
        ][$status] ?? 'badge-scheduled';
        $statusText = [
          'scheduled' => 'Chưa bắt đầu',
          'in_progress' => 'Đang chạy',
          'completed' => 'Đã kết thúc',
          'paused' => 'Tạm dừng',
          'cancelled' => 'Đã hủy'
        ][$status] ?? $status;
      ?>
      <span class="badge <?= $badge ?>"><?= $statusText ?></span>
    </td>
    <td><?= htmlspecialchars(substr($row['note'] ?? '', 0, 50)) ?><?= strlen($row['note'] ?? '') > 50 ? '...' : '' ?></td>
    <td><?= !empty($row['assigned_at']) ? date('d/m/Y H:i', strtotime($row['assigned_at'])) : 'N/A' ?></td>
    <td><?= htmlspecialchars($row['assigned_by_name'] ?? 'N/A') ?></td>
    <td>
      <?php if(isset($row['booked_guests'])): ?>
        <?= $row['booked_guests'] ?> / <?= $row['max_people'] ?? 'N/A' ?>
      <?php else: ?>
        N/A
      <?php endif; ?>
    </td>
    <td>
      <a href="index.php?act=guide-assign-detail&id=<?= $row['id'] ?>" class="action-btn detail" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
      <a href="index.php?act=guide-assign-edit&id=<?= $row['id'] ?>" class="action-btn edit" title="Sửa"><i class="bi bi-pencil-square"></i></a>
      <a href="index.php?act=guide-assign-delete&id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="action-btn delete" title="Xóa"><i class="bi bi-trash"></i></a>
    </td>
  </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr><td colspan="10" class="text-center text-muted">Chưa có phân công HDV nào!</td></tr>
<?php endif; ?>
</tbody>

      </table>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
