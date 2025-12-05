<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Hàm hiển thị badge trạng thái
function getStatusBadge($status) {
    $badges = [
        'active' => '<span class="badge bg-success">Đang hoạt động</span>',
        'inactive' => '<span class="badge bg-secondary">Tạm nghỉ</span>',
        'on_leave' => '<span class="badge bg-warning">Nghỉ phép</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">N/A</span>';
}

// Hàm hiển thị badge phân loại
function getCategoryBadge($categoryType) {
    $badges = [
        'domestic' => '<span class="badge bg-primary">Tour trong nước</span>',
        'international' => '<span class="badge bg-info">Tour quốc tế</span>',
        'specialized_route' => '<span class="badge bg-warning">Chuyên tuyến</span>',
        'group_tour' => '<span class="badge bg-success">Chuyên khách đoàn</span>',
        'customized' => '<span class="badge bg-secondary">Tour theo yêu cầu</span>'
    ];
    return $badges[$categoryType] ?? '<span class="badge bg-secondary">N/A</span>';
}

// Hàm hiển thị badge tình trạng sức khỏe
function getHealthBadge($healthStatus) {
    $badges = [
        'excellent' => '<span class="badge bg-success">Tuyệt vời</span>',
        'good' => '<span class="badge bg-info">Tốt</span>',
        'fair' => '<span class="badge bg-warning">Khá</span>',
        'poor' => '<span class="badge bg-danger">Yếu</span>'
    ];
    return $badges[$healthStatus] ?? '<span class="badge bg-secondary">N/A</span>';
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
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
        .info-section {
            border-left: 3px solid #0d6efd;
            padding-left: 15px;
            margin-bottom: 20px;
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
    <a href="index.php?act=guide" class="active"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary"><i class="bi bi-person-badge"></i> Chi tiết Hướng dẫn viên</h3>
        <div>
            <a href="index.php?act=guide-edit&id=<?= $guide['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Sửa
            </a>
            <a href="index.php?act=guide" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Thông tin cơ bản -->
    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-md-3 text-center">
                <?php if (!empty($guide['photo'])): ?>
                    <img src="<?= htmlspecialchars($guide['photo']) ?>" alt="Avatar" class="profile-img mb-3">
                <?php else: ?>
                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mx-auto mb-3" 
                         style="width: 150px; height: 150px; font-size: 60px;">
                        <?= strtoupper(substr($guide['fullname'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <h4 class="mt-2"><?= htmlspecialchars($guide['fullname']) ?></h4>
                <?= getStatusBadge($guide['status'] ?? 'active') ?>
            </div>

            <div class="col-md-9">
                <h5 class="mb-3"><i class="bi bi-info-circle"></i> Thông tin cá nhân</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-telephone"></i> Số điện thoại:</strong><br>
                        <?= htmlspecialchars($guide['phone'] ?? 'N/A') ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-envelope"></i> Email:</strong><br>
                        <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-calendar"></i> Ngày sinh:</strong><br>
                        <?= !empty($guide['date_of_birth']) ? date('d/m/Y', strtotime($guide['date_of_birth'])) : 'N/A' ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-geo-alt"></i> Địa chỉ:</strong><br>
                        <?= htmlspecialchars($guide['address'] ?? 'N/A') ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-translate"></i> Ngôn ngữ:</strong><br>
                        <?= htmlspecialchars($guide['languages'] ?? 'N/A') ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-award"></i> Chứng chỉ:</strong><br>
                        <?= htmlspecialchars($guide['certificate'] ?? 'N/A') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin chuyên môn -->
        <div class="col-md-6 mb-4">
            <div class="card p-4">
                <h5 class="mb-3"><i class="bi bi-briefcase"></i> Thông tin chuyên môn</h5>
                <div class="info-section">
                    <strong>Kinh nghiệm:</strong><br>
                    <?= isset($guide['experience_years']) && $guide['experience_years'] > 0 ? $guide['experience_years'] . ' năm' : 'Mới' ?>
                </div>
                <?php if (!empty($guide['experience_description'])): ?>
                    <div class="info-section">
                        <strong>Mô tả kinh nghiệm:</strong><br>
                        <?= nl2br(htmlspecialchars($guide['experience_description'])) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($guide['specializations'])): ?>
                    <div class="info-section">
                        <strong>Chuyên môn đặc biệt:</strong><br>
                        <?= nl2br(htmlspecialchars($guide['specializations'])) ?>
                    </div>
                <?php endif; ?>
                <div class="info-section">
                    <strong>Tình trạng sức khỏe:</strong><br>
                    <?= getHealthBadge($guide['health_status'] ?? 'good') ?>
                    <?php if (!empty($guide['health_notes'])): ?>
                        <br><small class="text-muted"><?= htmlspecialchars($guide['health_notes']) ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Phân loại -->
        <div class="col-md-6 mb-4">
            <div class="card p-4">
                <h5 class="mb-3"><i class="bi bi-tags"></i> Phân loại</h5>
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
    <div class="card p-4 mb-4">
        <h5 class="mb-3"><i class="bi bi-clock-history"></i> Lịch sử dẫn tour</h5>
        <?php if (!empty($tourHistory)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
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
                                        <span class="badge bg-info"><?= number_format($history['rating'], 1) ?>/5.0</span>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa có</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">Chưa có lịch sử dẫn tour</p>
        <?php endif; ?>
    </div>

    <!-- Chứng chỉ -->
    <?php if (!empty($certificates)): ?>
        <div class="card p-4 mb-4">
            <h5 class="mb-3"><i class="bi bi-award"></i> Chứng chỉ chuyên môn</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
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
    <?php endif; ?>

    <!-- Ghi chú -->
    <?php if (!empty($guide['notes'])): ?>
        <div class="card p-4">
            <h5 class="mb-3"><i class="bi bi-sticky"></i> Ghi chú</h5>
            <p><?= nl2br(htmlspecialchars($guide['notes'])) ?></p>
        </div>
    <?php endif; ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
