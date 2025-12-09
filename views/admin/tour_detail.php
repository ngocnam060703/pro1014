<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hàm hiển thị badge trạng thái
function getStatusBadge($status) {
    $badges = [
        'active' => ['class' => 'success', 'text' => 'Hoạt động'],
        'inactive' => ['class' => 'secondary', 'text' => 'Không hoạt động']
    ];
    $status_lower = strtolower($status ?? 'active');
    $badge = $badges[$status_lower] ?? ['class' => 'secondary', 'text' => $status];
    return '<span class="badge bg-' . $badge['class'] . '">' . $badge['text'] . '</span>';
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
}
.timeline-item {
    border-left: 3px solid #0d6efd;
    padding-left: 20px;
    margin-bottom: 30px;
    position: relative;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #0d6efd;
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
    <a href="index.php?act=tour" class="active"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-eye"></i> Chi tiết Tour
      </h3>
      <div>
        <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa tour
        </a>
        <a href="index.php?act=tour" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
        </a>
      </div>
    </div>

    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Thông tin cơ bản -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin cơ bản</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Mã Tour:</strong> 
            <span class="badge bg-info"><?= htmlspecialchars($tour['tour_code'] ?? '-') ?></span>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Tên Tour (Địa điểm):</strong> 
            <span class="fw-bold text-primary"><?= htmlspecialchars($tour['title']) ?></span>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Danh mục:</strong> 
            <?php $categoryInfo = getCategoryName($tour['category'] ?? 'domestic'); ?>
            <span class="badge bg-<?= $categoryInfo['badge'] ?>"><?= $categoryInfo['name'] ?></span>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Trạng thái:</strong> 
            <?= getStatusBadge($tour['status'] ?? 'active') ?>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Điểm khởi hành:</strong> 
            <?= htmlspecialchars($tour['departure'] ?? '-') ?>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Số chỗ:</strong> 
            <span class="badge bg-secondary"><?= $tour['slots'] ?> chỗ</span>
          </div>
        </div>
        
        <?php if (!empty($tour['description'])): ?>
        <div class="mb-3">
          <strong>Mô tả:</strong>
          <p class="mt-2"><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($tour['itinerary'])): ?>
        <div class="mb-3">
          <strong>Lịch trình tổng quan:</strong>
          <p class="mt-2"><?= nl2br(htmlspecialchars($tour['itinerary'])) ?></p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Giá tour -->
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> Giá tour</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 mb-3">
            <strong>Giá người lớn:</strong>
            <p class="text-success fw-bold fs-5"><?= number_format($tour['adult_price'] ?? $tour['price'] ?? 0) ?> đ</p>
          </div>
          <div class="col-md-3 mb-3">
            <strong>Giá trẻ em:</strong>
            <p class="text-info fw-bold"><?= number_format($tour['child_price'] ?? 0) ?> đ</p>
          </div>
          <div class="col-md-3 mb-3">
            <strong>Giá trẻ nhỏ:</strong>
            <p class="text-info fw-bold"><?= number_format($tour['infant_price'] ?? 0) ?> đ</p>
          </div>
          <div class="col-md-3 mb-3">
            <strong>Phụ phí:</strong>
            <p class="text-muted"><?= number_format($tour['surcharge'] ?? 0) ?> đ</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Lịch trình chi tiết theo ngày -->
    <?php if (!empty($itineraryDays)): ?>
    <div class="card mb-4">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Lịch trình chi tiết theo ngày</h5>
      </div>
      <div class="card-body">
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
    <div class="card mb-4">
      <div class="card-body text-center text-muted">
        <i class="bi bi-info-circle"></i> Chưa có lịch trình chi tiết theo ngày
      </div>
    </div>
    <?php endif; ?>

    <div class="d-flex gap-2">
      <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Sửa tour
      </a>
      <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-success">
        <i class="bi bi-calendar-check"></i> Xem lịch khởi hành
      </a>
      <a href="index.php?act=tour" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
      </a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




