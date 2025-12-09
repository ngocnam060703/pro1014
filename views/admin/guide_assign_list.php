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
    text-align: center;
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
    text-align: center;
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
.badge-scheduled { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-in_progress { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-completed { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-paused { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.badge-cancelled { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.action-btn {
    margin: 0 3px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}
.action-btn.edit { background: linear-gradient(135deg, #0d6efd 0%, #084298 100%); color: #fff; }
.action-btn.delete { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff; }
.action-btn.detail { background: linear-gradient(135deg, #198754 0%, #20c997 100%); color: #fff; }
.filter-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 8px 15px;
    transition: all 0.3s;
}
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-list-check"></i> Danh sách phân công HDV</h3>
              <p class="text-muted mb-0">Tổng số: <strong><?= count($data ?? []) ?></strong> phân công</p>
          </div>
          <a href="index.php?act=guide-assign-create" class="btn btn-primary btn-modern">
              <i class="bi bi-plus-circle"></i> Thêm phân công
          </a>
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

      <!-- Bảng danh sách -->
      <div class="table-container fade-in">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
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
                <td class="fw-bold">#<?= $row['id'] ?></td>
                <td><i class="bi bi-map"></i> <?= htmlspecialchars($row['tour_title'] ?? 'Chưa có') ?></td>
                <td>
                  <?php if(!empty($row['departure_time'])): ?>
                    <strong><i class="bi bi-calendar-event"></i> Khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($row['departure_time'])) ?><br>
                  <?php endif; ?>
                  <?php if(!empty($row['end_date'])): ?>
                    <strong><i class="bi bi-calendar-check"></i> Kết thúc:</strong> <?= date('d/m/Y', strtotime($row['end_date'])) ?>
                    <?php if(!empty($row['end_time'])): ?>
                      <?= date('H:i', strtotime($row['end_time'])) ?>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted">Tour 1 ngày</span>
                  <?php endif; ?>
                </td>
                <td><i class="bi bi-person-badge"></i> <?= htmlspecialchars($row['guide_name'] ?? 'Chưa có') ?></td>
                <td>
                  <?php 
                    $status = $row['status'] ?? 'scheduled';
                    $badge = [
                      'scheduled'=>'badge-modern badge-scheduled',
                      'in_progress'=>'badge-modern badge-in_progress',
                      'completed'=>'badge-modern badge-completed',
                      'paused'=>'badge-modern badge-paused',
                      'cancelled'=>'badge-modern badge-cancelled'
                    ][$status] ?? 'badge-modern badge-scheduled';
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
                <td><small><?= htmlspecialchars(substr($row['note'] ?? '', 0, 50)) ?><?= strlen($row['note'] ?? '') > 50 ? '...' : '' ?></small></td>
                <td><i class="bi bi-clock"></i> <?= !empty($row['assigned_at']) ? date('d/m/Y H:i', strtotime($row['assigned_at'])) : 'N/A' ?></td>
                <td><i class="bi bi-person"></i> <?= htmlspecialchars($row['assigned_by_name'] ?? 'N/A') ?></td>
                <td>
                  <?php if(isset($row['booked_guests'])): ?>
                    <span class="badge bg-info"><?= $row['booked_guests'] ?> / <?= $row['max_people'] ?? 'N/A' ?></span>
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
              <tr>
                <td colspan="10" class="text-center">
                  <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="mt-3">Chưa có phân công HDV nào!</h5>
                    <p class="text-muted">Hãy tạo phân công mới để bắt đầu</p>
                  </div>
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
