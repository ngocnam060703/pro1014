<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hàm hiển thị badge trạng thái
function getStatusBadge($status) {
    $badges = [
        'active' => '<span class="badge badge-modern badge-success">Hoạt động</span>',
        'inactive' => '<span class="badge badge-modern badge-secondary">Không hoạt động</span>'
    ];
    $status_lower = strtolower($status ?? 'active');
    return $badges[$status_lower] ?? '<span class="badge badge-modern badge-secondary">' . htmlspecialchars($status) . '</span>';
}

function getCategoryName($category) {
    $categories = [
        'domestic' => ['name' => 'Tour trong nước', 'badge' => 'primary'],
        'international' => ['name' => 'Tour quốc tế', 'badge' => 'success'],
        'customized' => ['name' => 'Tour theo yêu cầu', 'badge' => 'warning']
    ];
    return $categories[$category] ?? ['name' => 'Chưa phân loại', 'badge' => 'secondary'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết Tour - <?= htmlspecialchars($tour['title']) ?></title>

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
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 15px 15px 0 0 !important;
    padding: 15px 20px;
    font-weight: 600;
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
.timeline-item {
    border-left: 4px solid #667eea;
    padding-left: 25px;
    margin-bottom: 30px;
    position: relative;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -10px;
    top: 20px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
}
.badge-primary { background: linear-gradient(135deg, #0d6efd 0%, #084298 100%); }
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
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
.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
}
.btn-warning:hover {
    background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
}
.btn-success {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
}
.btn-success:hover {
    background: linear-gradient(135deg, #20c997 0%, #198754 100%);
}
.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}
.price-highlight {
    font-weight: bold;
    color: #198754;
    font-size: 1.2rem;
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
    <a href="index.php?act=tour" class="active"><i class="bi bi-card-list"></i> Quản lý Tour</a>
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
    <div class="card-container fade-in mb-4">
      <div class="d-flex justify-content-between align-items-center">
          <div>
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-eye"></i> Chi tiết Tour</h3>
              <p class="text-muted mb-0">Thông tin chi tiết về tour</p>
          </div>
          <div>
              <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="btn btn-warning btn-modern me-2">
                  <i class="bi bi-pencil"></i> Sửa tour
              </a>
              <a href="index.php?act=tour" class="btn btn-secondary btn-modern">
                  <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
              </a>
          </div>
      </div>
    </div>

    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Thông tin cơ bản -->
    <div class="card-container fade-in mb-4">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin cơ bản</h5>
      </div>
      <div class="p-4">
        <div class="row">
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Mã Tour</div>
              <div class="info-value">
                <span class="badge badge-modern badge-primary"><?= htmlspecialchars($tour['tour_code'] ?? '-') ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Tên Tour (Địa điểm)</div>
              <div class="info-value fw-bold text-primary"><?= htmlspecialchars($tour['title']) ?></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Danh mục</div>
              <div class="info-value">
                <?php $categoryInfo = getCategoryName($tour['category'] ?? 'domestic'); ?>
                <span class="badge badge-modern badge-<?= $categoryInfo['badge'] ?>"><?= $categoryInfo['name'] ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Trạng thái</div>
              <div class="info-value"><?= getStatusBadge($tour['status'] ?? 'active') ?></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Điểm khởi hành</div>
              <div class="info-value"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($tour['departure'] ?? '-') ?></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Số chỗ</div>
              <div class="info-value">
                <span class="badge badge-modern badge-secondary"><i class="bi bi-people"></i> <?= $tour['slots'] ?> chỗ</span>
              </div>
            </div>
          </div>
        </div>
        
        <?php if (!empty($tour['description'])): ?>
        <div class="info-section mt-3">
          <div class="info-label">Mô tả</div>
          <div class="info-value"><?= nl2br(htmlspecialchars($tour['description'])) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($tour['itinerary'])): ?>
        <div class="info-section mt-3">
          <div class="info-label">Lịch trình tổng quan</div>
          <div class="info-value"><?= nl2br(htmlspecialchars($tour['itinerary'])) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Giá tour -->
    <div class="card-container fade-in mb-4">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> Giá tour</h5>
      </div>
      <div class="p-4">
        <div class="row">
          <div class="col-md-3">
            <div class="info-section">
              <div class="info-label">Giá người lớn</div>
              <div class="info-value price-highlight">
                <i class="bi bi-currency-dollar"></i> <?= number_format($tour['adult_price'] ?? $tour['price'] ?? 0) ?> đ
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-section">
              <div class="info-label">Giá trẻ em</div>
              <div class="info-value fw-bold text-info">
                <i class="bi bi-currency-dollar"></i> <?= number_format($tour['child_price'] ?? 0) ?> đ
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-section">
              <div class="info-label">Giá trẻ nhỏ</div>
              <div class="info-value fw-bold text-info">
                <i class="bi bi-currency-dollar"></i> <?= number_format($tour['infant_price'] ?? 0) ?> đ
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-section">
              <div class="info-label">Phụ phí</div>
              <div class="info-value text-muted">
                <i class="bi bi-currency-dollar"></i> <?= number_format($tour['surcharge'] ?? 0) ?> đ
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Lịch trình chi tiết theo ngày -->
    <?php if (!empty($itineraryDays)): ?>
    <div class="card-container fade-in mb-4">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Lịch trình chi tiết theo ngày</h5>
      </div>
      <div class="p-4">
        <?php foreach ($itineraryDays as $day): ?>
        <div class="timeline-item">
          <h6 class="text-primary mb-3">
            <i class="bi bi-calendar-day"></i> Ngày <?= $day['day_number'] ?>
            <?php if (!empty($day['title'])): ?>
              - <?= htmlspecialchars($day['title']) ?>
            <?php endif; ?>
          </h6>
          
          <div class="row mb-3">
            <?php if (!empty($day['schedule'])): ?>
            <div class="col-md-6 mb-3">
              <strong><i class="bi bi-list-check"></i> Lịch trình:</strong>
              <p class="mt-1"><?= nl2br(htmlspecialchars($day['schedule'])) ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($day['destinations'])): ?>
            <div class="col-md-6 mb-3">
              <strong><i class="bi bi-geo-alt"></i> Đi đâu:</strong>
              <p class="mt-1"><?= nl2br(htmlspecialchars($day['destinations'])) ?></p>
            </div>
            <?php endif; ?>
          </div>
          
          <?php if (!empty($day['attractions'])): ?>
          <div class="mb-3">
            <strong><i class="bi bi-camera"></i> Các điểm tham quan:</strong>
            <p class="mt-1"><?= nl2br(htmlspecialchars($day['attractions'])) ?></p>
          </div>
          <?php endif; ?>
          
          <div class="row">
            <?php if (!empty($day['travel_time'])): ?>
            <div class="col-md-4 mb-2">
              <small class="text-muted">
                <i class="bi bi-clock"></i> <strong>Di chuyển:</strong> <?= htmlspecialchars($day['travel_time']) ?>
              </small>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($day['rest_time'])): ?>
            <div class="col-md-4 mb-2">
              <small class="text-muted">
                <i class="bi bi-moon"></i> <strong>Nghỉ ngơi:</strong> <?= htmlspecialchars($day['rest_time']) ?>
              </small>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($day['meal_time'])): ?>
            <div class="col-md-4 mb-2">
              <small class="text-muted">
                <i class="bi bi-fork-knife"></i> <strong>Ăn uống:</strong> <?= htmlspecialchars($day['meal_time']) ?>
              </small>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php else: ?>
    <div class="card-container fade-in mb-4">
      <div class="text-center text-muted py-5">
        <i class="bi bi-info-circle" style="font-size: 3rem; opacity: 0.3;"></i>
        <p class="mt-3">Chưa có lịch trình chi tiết theo ngày</p>
      </div>
    </div>
    <?php endif; ?>

    <div class="d-flex gap-2">
      <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="btn btn-warning btn-modern">
        <i class="bi bi-pencil"></i> Sửa tour
      </a>
      <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-success btn-modern">
        <i class="bi bi-calendar-check"></i> Xem lịch khởi hành
      </a>
      <a href="index.php?act=tour" class="btn btn-secondary btn-modern">
        <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
      </a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



