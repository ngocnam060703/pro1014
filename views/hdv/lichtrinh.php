<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lịch trình HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#343a40; padding-top:20px; }
.sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
.sidebar a:hover{ background:#495057; color:#fff; border-left:3px solid #0d6efd; }
.content{ padding:30px; }
.card-container{ background:#fff; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,0.1); max-width:1000px; margin:auto; }
.table thead th{ background:#0d6efd; color:#fff; text-align:center; }
.table tbody td{ text-align:center; vertical-align:middle; }
.table tbody tr:hover{ background:#e7f1ff; transition:0.3s; }
</style>
</head>
<body>
<div class="row g-0">
  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">HDV</h4>
    <a href="index.php?act=hdv_dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_lichtrinh"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    </a>
    <a href="index.php?act=hdv_journal"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="?act=logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card-container">
      <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-calendar-event"></i> Lịch trình của bạn</h3>

      <?php if(!empty($schedules)): ?>
      <table class="table table-hover table-bordered align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Tour</th>
            <th>Ngày khởi hành</th>
            <th>Điểm tập trung</th>
            <th>Số khách tối đa</th>
            <th>Ghi chú</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($schedules as $i => $sch): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($sch['tour_name'] ?? 'Chưa có') ?></td>
            <td><?= htmlspecialchars($sch['departure_time'] ?? '') ?></td>
            <td><?= htmlspecialchars($sch['meeting_point'] ?? '') ?></td>
            <td><?= $sch['max_people'] ?? '' ?></td>
            <td><?= htmlspecialchars($sch['note'] ?? '') ?></td>
            <td>
              <?php
                $status = $sch['status'] ?? 'scheduled';
                $badge = [
                  'scheduled'=>'badge bg-warning text-dark',
                  'in_progress'=>'badge bg-info text-white',
                  'completed'=>'badge bg-success text-white'
                ][$status];
              ?>
              <span class="<?= $badge ?>"><?= ucfirst(str_replace('_',' ',$status)) ?></span>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p class="text-center text-muted">Chưa có lịch trình nào được phân công.</p>
      <?php endif; ?>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
