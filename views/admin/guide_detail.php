<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Hàm hiển thị badge trạng thái
function getStatusBadge($status) {
    $badges = [
        'active' => '<span class="badge badge-modern badge-success">Đang hoạt động</span>',
        'inactive' => '<span class="badge badge-modern badge-secondary">Tạm nghỉ</span>',
        'on_leave' => '<span class="badge badge-modern badge-warning">Nghỉ phép</span>'
    ];
    return $badges[$status] ?? '<span class="badge badge-modern badge-secondary">N/A</span>';
}

// Hàm hiển thị badge phân loại
function getCategoryBadge($categoryType) {
    $badges = [
        'domestic' => '<span class="badge badge-modern badge-primary">Tour trong nước</span>',
        'international' => '<span class="badge badge-modern badge-info">Tour quốc tế</span>',
        'specialized_route' => '<span class="badge badge-modern badge-warning">Chuyên tuyến</span>',
        'group_tour' => '<span class="badge badge-modern badge-success">Chuyên khách đoàn</span>',
        'customized' => '<span class="badge badge-modern badge-secondary">Tour theo yêu cầu</span>'
    ];
    return $badges[$categoryType] ?? '<span class="badge badge-modern badge-secondary">N/A</span>';
}

// Hàm hiển thị badge tình trạng sức khỏe
function getHealthBadge($healthStatus) {
    $badges = [
        'excellent' => '<span class="badge badge-modern badge-success">Tuyệt vời</span>',
        'good' => '<span class="badge badge-modern badge-info">Tốt</span>',
        'fair' => '<span class="badge badge-modern badge-warning">Khá</span>',
        'poor' => '<span class="badge badge-modern badge-danger">Yếu</span>'
    ];
    return $badges[$healthStatus] ?? '<span class="badge badge-modern badge-secondary">N/A</span>';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Hướng dẫn viên</title>
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
        .profile-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #667eea;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }
        .profile-initial {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            font-weight: bold;
            font-size: 60px;
            border: 5px solid #fff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .info-section {
            border-left: 4px solid #667eea;
            padding-left: 20px;
            margin-bottom: 25px;
            transition: all 0.3s;
        }
        .info-section:hover {
            border-left-color: #764ba2;
            padding-left: 25px;
        }
        .info-section strong {
            color: #667eea;
            display: block;
            margin-bottom: 8px;
        }
        .badge-modern {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        .badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
        .badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
        .badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
        .badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .badge-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
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
    <a href="index.php?act=guide" class="active"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
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
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-person-badge"></i> Chi tiết Hướng dẫn viên</h3>
              <p class="text-muted mb-0">Thông tin chi tiết về nhân viên</p>
          </div>
          <div>
              <a href="index.php?act=guide-edit&id=<?= $guide['id'] ?>" class="btn btn-warning btn-modern me-2">
                  <i class="bi bi-pencil-square"></i> Sửa
              </a>
              <a href="index.php?act=guide" class="btn btn-secondary btn-modern">
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

      <!-- Thông tin cơ bản -->
      <div class="card-container fade-in mb-4">
          <div class="row">
              <div class="col-md-3 text-center">
                  <?php if (!empty($guide['photo'])): ?>
                      <img src="<?= htmlspecialchars($guide['photo']) ?>" alt="Avatar" class="profile-img mb-3">
                  <?php else: ?>
                      <div class="profile-initial mx-auto mb-3">
                          <?= strtoupper(substr($guide['fullname'], 0, 1)) ?>
                      </div>
                  <?php endif; ?>
                  <h4 class="mt-3 fw-bold"><?= htmlspecialchars($guide['fullname']) ?></h4>
                  <?= getStatusBadge($guide['status'] ?? 'active') ?>
              </div>

              <div class="col-md-9">
                  <h5 class="mb-4"><i class="bi bi-info-circle"></i> Thông tin cá nhân</h5>
                  <div class="row">
                      <div class="col-md-6 mb-3">
                          <div class="info-section">
                              <strong><i class="bi bi-telephone"></i> Số điện thoại</strong>
                              <?= htmlspecialchars($guide['phone'] ?? 'N/A') ?>
                          </div>
                      </div>
                      <div class="col-md-6 mb-3">
                          <div class="info-section">
                              <strong><i class="bi bi-envelope"></i> Email</strong>
                              <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
                          </div>
                      </div>
                      <div class="col-md-6 mb-3">
                          <div class="info-section">
                              <strong><i class="bi bi-calendar"></i> Ngày sinh</strong>
                              <?= !empty($guide['date_of_birth']) ? date('d/m/Y', strtotime($guide['date_of_birth'])) : 'N/A' ?>
                          </div>
                      </div>
                      <div class="col-md-6 mb-3">
                          <div class="info-section">
                              <strong><i class="bi bi-geo-alt"></i> Địa chỉ</strong>
                              <?= htmlspecialchars($guide['address'] ?? 'N/A') ?>
                          </div>
                      </div>
                      <div class="col-md-6 mb-3">
                          <div class="info-section">
                              <strong><i class="bi bi-translate"></i> Ngôn ngữ</strong>
                              <?= htmlspecialchars($guide['languages'] ?? 'N/A') ?>
                          </div>
                      </div>
                      <div class="col-md-6 mb-3">
                          <div class="info-section">
                              <strong><i class="bi bi-award"></i> Chứng chỉ</strong>
                              <?= htmlspecialchars($guide['certificate'] ?? 'N/A') ?>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="row">
          <!-- Thông tin chuyên môn -->
          <div class="col-md-6 mb-4">
              <div class="card-container fade-in">
                  <h5 class="mb-4"><i class="bi bi-briefcase"></i> Thông tin chuyên môn</h5>
                  <div class="info-section">
                      <strong>Kinh nghiệm</strong>
                      <?= isset($guide['experience_years']) && $guide['experience_years'] > 0 ? $guide['experience_years'] . ' năm' : 'Mới' ?>
                  </div>
                  <?php if (!empty($guide['experience_description'])): ?>
                      <div class="info-section">
                          <strong>Mô tả kinh nghiệm</strong>
                          <?= nl2br(htmlspecialchars($guide['experience_description'])) ?>
                      </div>
                  <?php endif; ?>
                  <?php if (!empty($guide['specializations'])): ?>
                      <div class="info-section">
                          <strong>Chuyên môn đặc biệt</strong>
                          <?= nl2br(htmlspecialchars($guide['specializations'])) ?>
                      </div>
                  <?php endif; ?>
                  <div class="info-section">
                      <strong>Tình trạng sức khỏe</strong>
                      <?= getHealthBadge($guide['health_status'] ?? 'good') ?>
                      <?php if (!empty($guide['health_notes'])): ?>
                          <br><small class="text-muted mt-2 d-block"><?= htmlspecialchars($guide['health_notes']) ?></small>
                      <?php endif; ?>
                  </div>
              </div>
          </div>

          <!-- Phân loại -->
          <div class="col-md-6 mb-4">
              <div class="card-container fade-in">
                  <h5 class="mb-4"><i class="bi bi-tags"></i> Phân loại</h5>
                  <?php if (!empty($categories)): ?>
                      <div class="d-flex flex-wrap gap-2">
                          <?php foreach ($categories as $cat): ?>
                              <?= getCategoryBadge($cat['category_type']) ?>
                          <?php endforeach; ?>
                      </div>
                  <?php else: ?>
                      <p class="text-muted">Chưa có phân loại</p>
                  <?php endif; ?>
              </div>
          </div>
      </div>

      <!-- Lịch sử dẫn tour -->
      <div class="card-container fade-in mb-4">
          <h5 class="mb-4"><i class="bi bi-clock-history"></i> Lịch sử dẫn tour</h5>
          <?php if (!empty($tourHistory)): ?>
              <div class="table-container">
                  <div class="table-responsive">
                      <table class="table table-hover mb-0">
                          <thead>
                              <tr>
                                  <th>Tour</th>
                                  <th>Ngày khởi hành</th>
                                  <th>Ngày kết thúc</th>
                                  <th>Số khách</th>
                                  <th>Loại tour</th>
                                  <th>Đánh giá</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php foreach ($tourHistory as $history): ?>
                                  <tr>
                                      <td><?= htmlspecialchars($history['tour_name'] ?? $history['tour_title'] ?? 'N/A') ?></td>
                                      <td><?= !empty($history['departure_date']) ? date('d/m/Y', strtotime($history['departure_date'])) : 'N/A' ?></td>
                                      <td><?= !empty($history['end_date']) ? date('d/m/Y', strtotime($history['end_date'])) : 'N/A' ?></td>
                                      <td><?= $history['num_guests'] ?? 0 ?></td>
                                      <td>
                                          <?php
                                          $tourType = $history['tour_type'] ?? '';
                                          $typeNames = [
                                              'domestic' => 'Trong nước',
                                              'international' => 'Quốc tế',
                                              'customized' => 'Theo yêu cầu'
                                          ];
                                          echo $typeNames[$tourType] ?? 'N/A';
                                          ?>
                                      </td>
                                      <td>
                                          <?php if (!empty($history['rating'])): ?>
                                              <span class="badge badge-modern badge-info"><?= number_format($history['rating'], 1) ?>/5.0</span>
                                          <?php else: ?>
                                              <span class="text-muted">Chưa có</span>
                                          <?php endif; ?>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          <?php else: ?>
              <p class="text-muted">Chưa có lịch sử dẫn tour</p>
          <?php endif; ?>
      </div>

      <!-- Chứng chỉ -->
      <?php if (!empty($certificates)): ?>
          <div class="card-container fade-in mb-4">
              <h5 class="mb-4"><i class="bi bi-award"></i> Chứng chỉ chuyên môn</h5>
              <div class="table-container">
                  <div class="table-responsive">
                      <table class="table table-hover mb-0">
                          <thead>
                              <tr>
                                  <th>Tên chứng chỉ</th>
                                  <th>Số chứng chỉ</th>
                                  <th>Tổ chức cấp</th>
                                  <th>Ngày cấp</th>
                                  <th>Ngày hết hạn</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php foreach ($certificates as $cert): ?>
                                  <tr>
                                      <td><?= htmlspecialchars($cert['certificate_name']) ?></td>
                                      <td><?= htmlspecialchars($cert['certificate_number'] ?? 'N/A') ?></td>
                                      <td><?= htmlspecialchars($cert['issuing_organization'] ?? 'N/A') ?></td>
                                      <td><?= !empty($cert['issue_date']) ? date('d/m/Y', strtotime($cert['issue_date'])) : 'N/A' ?></td>
                                      <td><?= !empty($cert['expiry_date']) ? date('d/m/Y', strtotime($cert['expiry_date'])) : 'N/A' ?></td>
                                  </tr>
                              <?php endforeach; ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      <?php endif; ?>

      <!-- Ghi chú -->
      <?php if (!empty($guide['notes'])): ?>
          <div class="card-container fade-in">
              <h5 class="mb-3"><i class="bi bi-sticky"></i> Ghi chú</h5>
              <p><?= nl2br(htmlspecialchars($guide['notes'])) ?></p>
          </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
