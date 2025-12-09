<?php
if (session_status() == PHP_SESSION_NONE) session_start();

function getStatusBadge($status) {
    $badges = [
        'scheduled' => '<span class="badge badge-modern badge-warning">Chưa bắt đầu</span>',
        'in_progress' => '<span class="badge badge-modern badge-info">Đang chạy</span>',
        'completed' => '<span class="badge badge-modern badge-success">Đã kết thúc</span>',
        'paused' => '<span class="badge badge-modern badge-secondary">Tạm dừng</span>',
        'cancelled' => '<span class="badge badge-modern badge-danger">Đã hủy</span>'
    ];
    return $badges[$status] ?? '<span class="badge badge-modern badge-secondary">N/A</span>';
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
.info-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    border-left: 4px solid #667eea;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}
.info-label {
    font-weight: 600;
    color: #667eea;
    margin-bottom: 8px;
}
.info-value {
    color: #212529;
    font-size: 16px;
    font-weight: 500;
}
.log-entry {
    border-left: 4px solid #667eea;
    padding: 20px;
    margin-bottom: 15px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
}
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
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
}
.table tbody tr {
    transition: all 0.3s;
}
.table tbody tr:hover {
    background: linear-gradient(to right, #f8f9ff 0%, #fff 50%);
}
.btn-modern {
    border-radius: 25px;
    padding: 10px 25px;
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
.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-in {
    animation: fadeIn 0.6s ease-out;
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
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign" class="active"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card-container fade-in mb-4">
      <div class="d-flex justify-content-between align-items-center">
          <div>
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-info-circle"></i> Chi tiết phân công HDV</h3>
              <p class="text-muted mb-0">Thông tin chi tiết về phân công</p>
          </div>
          <div>
              <a href="index.php?act=guide-assign-edit&id=<?= $assignment['id'] ?>" class="btn btn-primary btn-modern me-2">
                  <i class="bi bi-pencil-square"></i> Chỉnh sửa
              </a>
              <a href="index.php?act=guide-assign" class="btn btn-secondary btn-modern">
                  <i class="bi bi-arrow-left-circle"></i> Quay lại
              </a>
          </div>
      </div>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Thông tin Tour -->
    <div class="card-container fade-in">
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
    <div class="card-container fade-in">
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
    <div class="card-container fade-in">
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
    <div class="card-container fade-in">
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
    <div class="card-container fade-in">
      <h5 class="mb-4"><i class="bi bi-arrow-left-right"></i> Các lần thay hướng dẫn viên</h5>
      <div class="table-container">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
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
    </div>
    <?php endif; ?>

    <!-- Nhật ký thay đổi -->
    <div class="card-container fade-in">
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

