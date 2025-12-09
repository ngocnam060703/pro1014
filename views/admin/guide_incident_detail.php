<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết sự cố HDV</title>

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
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.info-value {
    color: #212529;
    font-size: 16px;
    font-weight: 500;
    line-height: 1.6;
}
.info-value.readonly {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.9rem;
}
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.photo-display {
    max-width: 100%;
    max-height: 400px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    object-fit: cover;
    margin-top: 10px;
}
.photo-container {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    border: 2px dashed #dee2e6;
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
.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
}
.btn-warning:hover {
    background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
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
        <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
        <a href="index.php?act=guide-incident" class="active">
            <i class="bi bi-exclamation-triangle"></i> Danh sách sự cố
        </a>

        <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>

    <!-- CONTENT -->
    <div class="col-10 content">

        <div class="card-container fade-in mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold text-primary">
                        <i class="bi bi-eye-fill"></i> Chi tiết sự cố HDV
                    </h3>
                    <p class="text-muted mb-0">Thông tin chi tiết về sự cố</p>
                </div>
                <div>
                    <a href="index.php?act=guide-incident-edit&id=<?= $incident['id'] ?>" class="btn btn-warning btn-modern me-2">
                        <i class="bi bi-pencil-square"></i> Sửa sự cố
                    </a>
                    <a href="index.php?act=guide-incident" class="btn btn-secondary btn-modern">
                        <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>

        <!-- Thông tin cơ bản -->
        <div class="card-container fade-in mb-4">
            <h5 class="mb-4"><i class="bi bi-info-circle"></i> Thông tin cơ bản</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="info-section">
                        <div class="info-label">Hướng dẫn viên</div>
                        <div class="info-value">
                            <i class="bi bi-person-badge"></i> <?= htmlspecialchars($incident['guide_name']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-section">
                        <div class="info-label">Chuyến đi</div>
                        <div class="info-value">
                            <i class="bi bi-calendar-event"></i> <?= htmlspecialchars($incident['tour_title'] ?? $incident['departure_name'] ?? 'N/A') ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-section">
                        <div class="info-label">Loại sự cố</div>
                        <div class="info-value">
                            <i class="bi bi-tag"></i> <?= htmlspecialchars($incident['incident_type']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-section">
                        <div class="info-label">Mức độ</div>
                        <div class="info-value">
                            <?php
                              $severity = $incident['severity'] ?? 'low';
                              $badgeClass = [
                                'low' => 'badge-modern badge-info',
                                'medium' => 'badge-modern badge-warning',
                                'high' => 'badge-modern badge-danger'
                              ];
                              $badge = $badgeClass[$severity] ?? 'badge-modern badge-secondary';
                              $severityText = [
                                'low' => 'Thấp',
                                'medium' => 'Trung bình',
                                'high' => 'Cao'
                              ];
                            ?>
                            <span class="badge <?= $badge ?>">
                                <i class="bi bi-<?= $severity == 'high' ? 'exclamation-triangle-fill' : ($severity == 'medium' ? 'exclamation-circle' : 'info-circle') ?>"></i>
                                <?= $severityText[$severity] ?? ucfirst($severity) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chi tiết sự cố -->
        <div class="card-container fade-in mb-4">
            <h5 class="mb-4"><i class="bi bi-file-text"></i> Chi tiết sự cố</h5>
            
            <div class="info-section">
                <div class="info-label">Mô tả sự cố</div>
                <div class="info-value readonly">
                    <?= nl2br(htmlspecialchars($incident['description'])) ?>
                </div>
            </div>

            <?php if (!empty($incident['solution'])): ?>
            <div class="info-section">
                <div class="info-label">Giải pháp xử lý</div>
                <div class="info-value readonly">
                    <?= nl2br(htmlspecialchars($incident['solution'])) ?>
                </div>
            </div>
            <?php else: ?>
            <div class="info-section">
                <div class="info-label">Giải pháp xử lý</div>
                <div class="info-value readonly text-muted">
                    <i class="bi bi-dash-circle"></i> Chưa có giải pháp
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Hình ảnh -->
        <div class="card-container fade-in mb-4">
            <h5 class="mb-4"><i class="bi bi-image"></i> Hình ảnh minh chứng</h5>
            
            <?php if (!empty($incident['photos'])): ?>
                <div class="photo-container">
                    <img src="uploads/incidents/<?= htmlspecialchars($incident['photos']) ?>" 
                         alt="Ảnh sự cố" 
                         class="photo-display"
                         onclick="window.open(this.src, '_blank')"
                         style="cursor: pointer;">
                    <p class="text-muted mt-3 mb-0">
                        <small><i class="bi bi-info-circle"></i> Click vào ảnh để xem kích thước đầy đủ</small>
                    </p>
                </div>
            <?php else: ?>
                <div class="photo-container">
                    <i class="bi bi-image" style="font-size: 4rem; color: #dee2e6;"></i>
                    <p class="text-muted mt-3 mb-0">Không có ảnh minh chứng</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action buttons -->
        <div class="d-flex gap-2">
            <a href="index.php?act=guide-incident-edit&id=<?= $incident['id'] ?>" class="btn btn-warning btn-modern">
                <i class="bi bi-pencil-square"></i> Sửa sự cố
            </a>
            <a href="index.php?act=guide-incident" class="btn btn-secondary btn-modern">
                <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
            </a>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
