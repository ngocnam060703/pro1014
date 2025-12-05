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
        background: linear-gradient(to right, #dfe9f3, #ffffff); 
        font-family: 'Segoe UI', sans-serif; 
    }
    .sidebar { height: 100vh; background: #343a40; padding-top: 20px; }
    .sidebar a { 
        color: #ccc; padding: 12px; display: block; 
        text-decoration: none; border-left: 3px solid transparent; 
    }
    .sidebar a:hover { background: #495057; color: #fff; border-left: 3px solid #0d6efd; }
    .sidebar a.active { background:#495057; color:#fff; border-left:3px solid #0d6efd; }

    .content { padding: 30px; }
    .card { 
        border-radius: 18px; 
        box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
    }

    .btn-primary { 
        background: linear-gradient(45deg,#5a5afc,#fc5a8d); 
        border: none; 
    }
    .btn-primary:hover { 
        background: linear-gradient(45deg,#fc5a8d,#5a5afc); 
    }
</style>
</head>

<body>

<div class="row g-0">

    <!-- SIDEBAR -->
    <div class="col-2 sidebar">
        <h4 class="text-center text-light mb-4">ADMIN</h4>

        <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
        <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
        <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
        <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
        <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
        <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>

        <a href="index.php?act=guide-incident" class="active">
            <i class="bi bi-exclamation-triangle"></i> Danh sách sự cố
        </a>

        <a href="index.php?act=guide-assign"><i class="bi bi-people"></i> Phân công HDV</a>

        <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>

    <!-- CONTENT -->
    <div class="col-10 content">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary">
                <i class="bi bi-eye-fill"></i> Chi tiết sự cố HDV
            </h3>

            <a href="index.php?act=guide-incident" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
            </a>
        </div>

        <div class="card p-4">

            <!-- Form giống hệt THÊM nhưng tất cả readonly -->
            
            <!-- Hướng dẫn viên -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Hướng dẫn viên</label>
                <input type="text" class="form-control" value="<?= $incident['guide_name'] ?>" readonly>
            </div>

            <!-- Chuyến đi -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Chuyến đi</label>
                <input type="text" class="form-control"
                    value="<?= $incident['tour_title'] . ' - ' . $incident['departure_time'] ?>" readonly>
            </div>

            <!-- Loại sự cố -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Loại sự cố</label>
                <input type="text" class="form-control" value="<?= $incident['incident_type'] ?>" readonly>
            </div>

            <!-- Mức độ -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Mức độ</label>
                <input type="text" class="form-control" value="<?= $incident['severity'] ?>" readonly>
            </div>

            <!-- Mô tả -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả sự cố</label>
                <textarea class="form-control" rows="4" readonly><?= $incident['description'] ?></textarea>
            </div>

            <!-- Giải pháp -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Giải pháp xử lý</label>
                <textarea class="form-control" rows="3" readonly><?= $incident['solution'] ?></textarea>
            </div>

            <!-- Ảnh -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Ảnh minh chứng</label><br>

                <?php if (!empty($incident['photos'])): ?>
                    <img src="uploads/incidents/<?= $incident['photos'] ?>" 
                         alt="Ảnh sự cố" 
                         style="max-width: 300px; border-radius: 10px;">
                <?php else: ?>
                    <p class="text-muted fst-italic">Không có ảnh</p>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
