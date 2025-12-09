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
.filter-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
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
    <a href="index.php?act=schedule" class="active"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
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
          <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-calendar-week"></i> Danh sách lịch trình</h3>
          <p class="text-muted mb-0">Tổng số: <strong><?= count($listSchedule ?? []) ?></strong> lịch trình</p>
        </div>
        <div>
          <a href="index.php?act=schedule-create" class="btn btn-primary btn-modern me-2">
            <i class="bi bi-plus-circle"></i> Thêm lịch trình
          </a>
          <a href="index.php?act=dashboard" class="btn btn-secondary btn-modern">
            <i class="bi bi-arrow-left-circle"></i> Quay lại
          </a>
        </div>
      </div>

      <?php if (!empty($_SESSION['message'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
              <i class="bi bi-check-circle"></i> <?= $_SESSION['message'] ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error'] ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <!-- Tìm kiếm -->
      <div class="filter-section fade-in">
        <h5 class="mb-3"><i class="bi bi-search"></i> Tìm kiếm</h5>
        <form method="GET" action="index.php">
          <input type="hidden" name="act" value="schedule">
          
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Tìm kiếm theo mã lịch trình</label>
              <input type="text" name="search_id" class="form-control" 
                     placeholder="Nhập mã lịch trình (ID)..." 
                     value="<?= htmlspecialchars($_GET['search_id'] ?? '') ?>">
            </div>
            <div class="col-md-6 d-flex align-items-end">
              <button type="submit" class="btn btn-primary btn-modern me-2">
                <i class="bi bi-search"></i> Tìm kiếm
              </button>
              <?php if (!empty($_GET['search_id'])): ?>
                <a href="index.php?act=schedule" class="btn btn-secondary btn-modern">
                  <i class="bi bi-x-circle"></i> Xóa bộ lọc
                </a>
              <?php endif; ?>
            </div>
          </div>
        </form>
      </div>

      <!-- Bảng danh sách -->
      <div class="table-container fade-in">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Mã lịch trình</th>
                <th>Địa điểm</th>
                <th>Ngày khởi hành</th>
                <th>Ngày kết thúc</th>
                <th>Số ngày - Số đêm</th>
                <th>Số khách đã đặt / Tối đa</th>
                <th>Hướng dẫn viên</th>
                <th>Phương tiện</th>
                <th>Trạng thái</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            // Hàm hiển thị trạng thái
            function getStatusBadge($status) {
                $statuses = [
                    'open' => ['label' => 'Đang mở bán', 'class' => 'badge-success'],
                    'upcoming' => ['label' => 'Sắp khởi hành', 'class' => 'badge-info'],
                    'in_progress' => ['label' => 'Đang chạy', 'class' => 'badge-primary'],
                    'completed' => ['label' => 'Đã hoàn thành', 'class' => 'badge-secondary'],
                    'cancelled' => ['label' => 'Đã hủy', 'class' => 'badge-danger'],
                    'scheduled' => ['label' => 'Đang mở bán', 'class' => 'badge-success'],
                    'confirmed' => ['label' => 'Sắp khởi hành', 'class' => 'badge-info']
                ];
                $statusInfo = $statuses[$status] ?? ['label' => $status, 'class' => 'badge-secondary'];
                return '<span class="badge ' . $statusInfo['class'] . ' badge-modern">' . $statusInfo['label'] . '</span>';
            }
            ?>
            <?php if (!empty($listSchedule)): ?>
              <?php foreach ($listSchedule as $schedule): ?>
              <tr>
                <td class="fw-bold text-primary">#<?= $schedule['id'] ?></td>
                <td class="fw-semibold text-primary">
                  <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($schedule['tour_name'] ?? '—') ?>
                </td>
                <td>
                  <?php if (!empty($schedule['departure_time'])): ?>
                    <i class="bi bi-calendar-event"></i> <?= date('d/m/Y', strtotime($schedule['departure_time'])) ?><br>
                    <i class="bi bi-clock"></i> <?= date('H:i', strtotime($schedule['departure_time'])) ?>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($schedule['end_date'])): ?>
                    <i class="bi bi-calendar-check"></i> <?= date('d/m/Y', strtotime($schedule['end_date'])) ?>
                  <?php elseif (!empty($schedule['departure_time'])): ?>
                    <?= date('d/m/Y', strtotime($schedule['departure_time'])) ?>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php 
                  $days = $schedule['days_count'] ?? 0;
                  $nights = $schedule['nights_count'] ?? 0;
                  if ($days > 0 || $nights > 0):
                  ?>
                    <span class="badge badge-info badge-modern"><?= $days ?> ngày</span>
                    <?php if ($nights > 0): ?>
                      <span class="badge badge-secondary badge-modern"><?= $nights ?> đêm</span>
                    <?php endif; ?>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <span class="fw-bold text-warning"><?= $schedule['seats_booked'] ?? 0 ?></span>
                  <span class="text-muted">/</span>
                  <span class="fw-bold text-success"><?= $schedule['total_seats'] ?? 0 ?></span>
                </td>
                <td>
                  <?php if (!empty($schedule['guide_names'])): ?>
                    <small><i class="bi bi-person-badge"></i> <?= htmlspecialchars($schedule['guide_names']) ?></small>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($schedule['vehicles'])): ?>
                    <small><i class="bi bi-truck"></i> <?= htmlspecialchars($schedule['vehicles']) ?></small>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?= getStatusBadge($schedule['status'] ?? 'scheduled') ?>
                </td>
                <td class="text-center">
                  <a href="index.php?act=schedule-detail&id=<?= $schedule['id'] ?>" 
                     class="btn btn-info btn-sm me-1" title="Chi tiết">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="index.php?act=schedule-edit&id=<?= $schedule['id'] ?>" 
                     class="btn btn-warning btn-sm me-1" title="Sửa">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="index.php?act=schedule-delete&id=<?= $schedule['id'] ?>" 
                     onclick="return confirm('Bạn có chắc chắn muốn xóa lịch trình ID <?= $schedule['id'] ?> không?')" 
                     class="btn btn-danger btn-sm" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center">
                  <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h5 class="mt-3">
                      <?php if (!empty($_GET['search_id'])): ?>
                        Không tìm thấy lịch trình với mã <?= htmlspecialchars($_GET['search_id']) ?>
                      <?php else: ?>
                        Hiện chưa có lịch trình nào
                      <?php endif; ?>
                    </h5>
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
