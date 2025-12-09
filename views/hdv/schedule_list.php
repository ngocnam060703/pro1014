<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lịch làm việc HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#343a40; padding-top:20px; position:fixed; }
.sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
.sidebar a:hover{ background:#495057; color:#fff; border-left:3px solid #0d6efd; }
.sidebar a.active{ color:#fff; background:#495057; border-left:3px solid #0d6efd; }
.content{ padding:30px; margin-left:16.666667%; }
.card-container{ background:#fff; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,0.1); }
.table thead th{ background:#0d6efd; color:#fff; text-align:center; }
.table tbody td{ text-align:center; vertical-align:middle; }
.table tbody tr:hover{ background:#e7f1ff; transition:0.3s; }
.filter-section { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
.badge-scheduled { background: #ffc107; color: #212529; }
.badge-in_progress { background: #0dcaf0; color: #fff; }
.badge-completed { background: #198754; color: #fff; }
.badge-paused { background: #6c757d; color: #fff; }
</style>
</head>
<body>
<div class="row g-0">
  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">HDV</h4>
    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_schedule_list" class="active"><i class="bi bi-calendar-event"></i> Lịch làm việc</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_profile"><i class="bi bi-person-circle"></i> Hồ sơ</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card-container">
      <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-calendar-event"></i> Lịch làm việc của tôi</h3>

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

      <!-- Bộ lọc -->
      <div class="filter-section">
        <form method="GET" action="index.php" class="row g-3">
          <input type="hidden" name="act" value="hdv_schedule_list">
          
          <div class="col-md-3">
            <label class="form-label">Tìm kiếm theo tên tour</label>
            <input type="text" name="search" class="form-control" placeholder="Nhập tên tour..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          </div>

          <div class="col-md-2">
            <label class="form-label">Lọc theo tháng</label>
            <input type="month" name="month" class="form-control" value="<?= htmlspecialchars($_GET['month'] ?? '') ?>">
          </div>

          <div class="col-md-2">
            <label class="form-label">Lọc theo ngày</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
          </div>

          <div class="col-md-2">
            <label class="form-label">Lọc theo trạng thái</label>
            <select name="status" class="form-select">
              <option value="">-- Tất cả --</option>
              <option value="scheduled" <?= (isset($_GET['status']) && $_GET['status'] == 'scheduled') ? 'selected' : '' ?>>Sắp đi</option>
              <option value="in_progress" <?= (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'selected' : '' ?>>Đang chạy</option>
              <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>Đã kết thúc</option>
              <option value="paused" <?= (isset($_GET['status']) && $_GET['status'] == 'paused') ? 'selected' : '' ?>>Tạm hoãn</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">&nbsp;</label>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Lọc</button>
              <a href="index.php?act=hdv_schedule_list" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
          </div>
        </form>
      </div>

      <?php if(!empty($schedules)): ?>
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Tên tour</th>
              <th>Mã tour</th>
              <th>Ngày khởi hành - Ngày kết thúc</th>
              <th>Thời lượng</th>
              <th>Điểm đến</th>
              <th>Trạng thái</th>
              <th>Ghi chú phân công</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($schedules as $i => $sch): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td>
                <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $sch['departure_id'] ?>" class="text-primary fw-bold">
                  <?= htmlspecialchars($sch['tour_name'] ?? 'Chưa có') ?>
                </a>
              </td>
              <td><?= htmlspecialchars($sch['tour_code'] ?? 'N/A') ?></td>
              <td>
                <strong>Khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($sch['departure_time'] ?? '')) ?><br>
                <?php if(!empty($sch['end_date'])): ?>
                  <strong>Kết thúc:</strong> <?= date('d/m/Y', strtotime($sch['end_date'])) ?>
                  <?php if(!empty($sch['end_time'])): ?>
                    <?= date('H:i', strtotime($sch['end_time'])) ?>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="text-muted">Tour 1 ngày</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if(isset($sch['duration_days'])): ?>
                  <?= $sch['duration_days'] ?> ngày
                  <?php if($sch['duration_days'] > 1): ?>
                    <?= $sch['duration_days'] - 1 ?> đêm
                  <?php endif; ?>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($sch['tour_description'] ?? 'N/A') ?></td>
              <td>
                <?php 
                  $status = $sch['status'] ?? 'scheduled';
                  $badge = [
                    'scheduled' => 'badge-scheduled',
                    'in_progress' => 'badge-in_progress',
                    'completed' => 'badge-completed',
                    'paused' => 'badge-paused'
                  ][$status] ?? 'badge-scheduled';
                  $statusText = [
                    'scheduled' => 'Sắp đi',
                    'in_progress' => 'Đang chạy',
                    'completed' => 'Đã kết thúc',
                    'paused' => 'Tạm hoãn'
                  ][$status] ?? $status;
                ?>
                <span class="badge <?= $badge ?>"><?= $statusText ?></span>
              </td>
              <td><?= htmlspecialchars(substr($sch['note'] ?? '', 0, 50)) ?><?= strlen($sch['note'] ?? '') > 50 ? '...' : '' ?></td>
              <td>
                <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $sch['departure_id'] ?>" class="btn btn-sm btn-primary">
                  <i class="bi bi-eye"></i> Chi tiết
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle"></i> Chưa có lịch trình nào được phân công.
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



