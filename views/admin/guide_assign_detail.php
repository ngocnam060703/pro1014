<?php
if (session_status() == PHP_SESSION_NONE) session_start();

function getStatusBadge($status) {
    $badges = [
        'scheduled' => '<span class="badge bg-warning text-dark">Chưa bắt đầu</span>',
        'in_progress' => '<span class="badge bg-info">Đang chạy</span>',
        'completed' => '<span class="badge bg-success">Đã kết thúc</span>',
        'paused' => '<span class="badge bg-secondary">Tạm dừng</span>',
        'cancelled' => '<span class="badge bg-danger">Đã hủy</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">N/A</span>';
}

function getChangeTypeName($type) {
    $names = [
        'created' => 'Tạo mới',
        'guide_changed' => 'Thay đổi HDV',
        'status_changed' => 'Thay đổi trạng thái',
        'note_changed' => 'Thay đổi ghi chú',
        'deleted' => 'Xóa'
    ];
    return $names[$type] ?? $type;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết phân công HDV</title>
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
    margin-bottom: 20px;
}
.info-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 15px;
}
.info-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}
.info-value {
    color: #212529;
    font-size: 16px;
}
.log-entry {
    border-left: 4px solid #0d6efd;
    padding: 15px;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-radius: 5px;
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
    <a href="index.php?act=guide-assign" class="active"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold"><i class="bi bi-info-circle"></i> Chi tiết phân công HDV</h3>
      <div>
        <a href="index.php?act=guide-assign-edit&id=<?= $assignment['id'] ?>" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Chỉnh sửa</a>
        <a href="index.php?act=guide-assign" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Quay lại</a>
      </div>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Thông tin Tour -->
    <div class="card p-4">
      <h5 class="mb-4"><i class="bi bi-map"></i> Thông tin Tour</h5>
      <div class="row">
        <div class="col-md-6 info-section">
          <div class="info-label">Tên Tour</div>
          <div class="info-value"><?= htmlspecialchars($assignment['tour_title'] ?? 'N/A') ?></div>
        </div>
        <div class="col-md-6 info-section">
          <div class="info-label">Mô tả Tour</div>
          <div class="info-value"><?= htmlspecialchars($assignment['tour_description'] ?? 'N/A') ?></div>
        </div>
      </div>
    </div>

    <!-- Lịch khởi hành -->
    <div class="card p-4">
      <h5 class="mb-4"><i class="bi bi-calendar-event"></i> Lịch khởi hành</h5>
      <div class="row">
        <div class="col-md-4 info-section">
          <div class="info-label">📅 Ngày khởi hành</div>
          <div class="info-value">
            <?php if(!empty($assignment['departure_time'])): ?>
              <?= date('d/m/Y', strtotime($assignment['departure_time'])) ?>
              (<?= date('l', strtotime($assignment['departure_time'])) === 'Monday' ? 'Thứ 2' : 
                  (date('l', strtotime($assignment['departure_time'])) === 'Tuesday' ? 'Thứ 3' :
                  (date('l', strtotime($assignment['departure_time'])) === 'Wednesday' ? 'Thứ 4' :
                  (date('l', strtotime($assignment['departure_time'])) === 'Thursday' ? 'Thứ 5' :
                  (date('l', strtotime($assignment['departure_time'])) === 'Friday' ? 'Thứ 6' :
                  (date('l', strtotime($assignment['departure_time'])) === 'Saturday' ? 'Thứ 7' : 'Chủ nhật'))))) ?>)
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">⏰ Giờ khởi hành</div>
          <div class="info-value">
            <?php if(!empty($assignment['departure_time'])): ?>
              <?= date('H:i', strtotime($assignment['departure_time'])) ?>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">📍 Điểm tập trung</div>
          <div class="info-value"><?= htmlspecialchars($assignment['departure_meeting_point'] ?? $assignment['meeting_point'] ?? 'N/A') ?></div>
        </div>
        <?php if(!empty($assignment['end_date'])): ?>
        <div class="col-md-4 info-section">
          <div class="info-label">📅 Ngày kết thúc</div>
          <div class="info-value">
            <?= date('d/m/Y', strtotime($assignment['end_date'])) ?>
            (<?= date('l', strtotime($assignment['end_date'])) === 'Monday' ? 'Thứ 2' : 
                (date('l', strtotime($assignment['end_date'])) === 'Tuesday' ? 'Thứ 3' :
                (date('l', strtotime($assignment['end_date'])) === 'Wednesday' ? 'Thứ 4' :
                (date('l', strtotime($assignment['end_date'])) === 'Thursday' ? 'Thứ 5' :
                (date('l', strtotime($assignment['end_date'])) === 'Friday' ? 'Thứ 6' :
                (date('l', strtotime($assignment['end_date'])) === 'Saturday' ? 'Thứ 7' : 'Chủ nhật'))))) ?>)
          </div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">⏰ Giờ kết thúc</div>
          <div class="info-value">
            <?php if(!empty($assignment['end_time'])): ?>
              <?= date('H:i', strtotime($assignment['end_time'])) ?>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
        <div class="col-md-4 info-section">
          <div class="info-label">Trạng thái lịch trình</div>
          <div class="info-value"><?= getStatusBadge($assignment['departure_status'] ?? 'N/A') ?></div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">Số chỗ</div>
          <div class="info-value">
            <?= $assignment['booked_guests'] ?? 0 ?> / <?= $assignment['total_seats'] ?? $assignment['max_people'] ?? 'N/A' ?>
            (Còn lại: <?= ($assignment['seats_available'] ?? (($assignment['total_seats'] ?? $assignment['max_people'] ?? 0) - ($assignment['booked_guests'] ?? 0))) ?>)
          </div>
        </div>
      </div>
    </div>

    <!-- Thông tin HDV -->
    <div class="card p-4">
      <h5 class="mb-4"><i class="bi bi-person-badge"></i> Thông tin HDV</h5>
      <div class="row">
        <div class="col-md-4 info-section">
          <div class="info-label">Họ tên</div>
          <div class="info-value"><?= htmlspecialchars($assignment['guide_name'] ?? 'N/A') ?></div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">Số điện thoại</div>
          <div class="info-value"><?= htmlspecialchars($assignment['guide_phone'] ?? 'N/A') ?></div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">Email</div>
          <div class="info-value"><?= htmlspecialchars($assignment['guide_email'] ?? 'N/A') ?></div>
        </div>
      </div>
    </div>

    <!-- Thông tin phân công -->
    <div class="card p-4">
      <h5 class="mb-4"><i class="bi bi-clipboard-check"></i> Thông tin phân công</h5>
      <div class="row">
        <div class="col-md-4 info-section">
          <div class="info-label">Trạng thái phân công</div>
          <div class="info-value"><?= getStatusBadge($assignment['status'] ?? 'scheduled') ?></div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">Lý do phân công</div>
          <div class="info-value"><?= htmlspecialchars($assignment['reason'] ?? 'N/A') ?></div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">Ghi chú</div>
          <div class="info-value"><?= htmlspecialchars($assignment['note'] ?? 'N/A') ?></div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">Người thực hiện phân công</div>
          <div class="info-value"><?= htmlspecialchars($assignment['assigned_by_name'] ?? 'N/A') ?></div>
        </div>
        <div class="col-md-4 info-section">
          <div class="info-label">Thời gian phân công</div>
          <div class="info-value">
            <?php if(!empty($assignment['assigned_at'])): ?>
              <?= date('d/m/Y H:i:s', strtotime($assignment['assigned_at'])) ?>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Các lần thay hướng dẫn viên -->
    <?php if(!empty($guideChanges)): ?>
    <div class="card p-4">
      <h5 class="mb-4"><i class="bi bi-arrow-left-right"></i> Các lần thay hướng dẫn viên</h5>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>STT</th>
              <th>HDV cũ</th>
              <th>HDV mới</th>
              <th>Thời gian thay đổi</th>
              <th>Người thay đổi</th>
              <th>Lý do</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($guideChanges as $index => $change): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($change['old_guide_name'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($change['new_guide_name'] ?? 'N/A') ?></td>
              <td><?= date('d/m/Y H:i:s', strtotime($change['created_at'])) ?></td>
              <td><?= htmlspecialchars($change['changed_by_name'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($change['change_reason'] ?? 'N/A') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- Nhật ký thay đổi -->
    <div class="card p-4">
      <h5 class="mb-4"><i class="bi bi-clock-history"></i> Nhật ký thay đổi</h5>
      <?php if(!empty($logs)): ?>
        <?php foreach($logs as $log): ?>
        <div class="log-entry">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong><?= getChangeTypeName($log['change_type']) ?></strong>
              <span class="badge bg-secondary ms-2"><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></span>
            </div>
            <div class="text-muted">
              <?= htmlspecialchars($log['changed_by_name'] ?? 'Hệ thống') ?>
            </div>
          </div>
          
          <?php if($log['change_type'] == 'guide_changed'): ?>
            <div class="mb-2">
              <strong>HDV cũ:</strong> <?= htmlspecialchars($log['old_guide_name'] ?? 'N/A') ?> 
              <i class="bi bi-arrow-right"></i> 
              <strong>HDV mới:</strong> <?= htmlspecialchars($log['new_guide_name'] ?? 'N/A') ?>
            </div>
          <?php endif; ?>
          
          <?php if($log['change_type'] == 'status_changed'): ?>
            <div class="mb-2">
              <strong>Trạng thái cũ:</strong> <?= getStatusBadge($log['old_status']) ?> 
              <i class="bi bi-arrow-right"></i> 
              <strong>Trạng thái mới:</strong> <?= getStatusBadge($log['new_status']) ?>
            </div>
          <?php endif; ?>
          
          <?php if($log['change_type'] == 'note_changed'): ?>
            <div class="mb-2">
              <strong>Ghi chú cũ:</strong> <?= htmlspecialchars($log['old_note'] ?? 'N/A') ?><br>
              <strong>Ghi chú mới:</strong> <?= htmlspecialchars($log['new_note'] ?? 'N/A') ?>
            </div>
          <?php endif; ?>
          
          <?php if(!empty($log['change_reason'])): ?>
            <div class="text-muted">
              <strong>Lý do:</strong> <?= htmlspecialchars($log['change_reason']) ?>
            </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-center text-muted py-4">
          <i class="bi bi-inbox" style="font-size: 48px;"></i>
          <p class="mt-2">Chưa có nhật ký thay đổi</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

