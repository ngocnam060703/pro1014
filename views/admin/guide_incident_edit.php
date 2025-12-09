<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa sự cố HDV</title>

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
.form-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-left: 4px solid #667eea;
}
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 10px 15px;
    transition: all 0.3s;
}
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
    background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
}
.btn-primary:hover {
    background: linear-gradient(135deg, #084298 0%, #0d6efd 100%);
}
.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}
.photo-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    margin-top: 10px;
    object-fit: cover;
}
.photo-preview-container {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    margin-top: 10px;
    border: 2px dashed #dee2e6;
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

        <div class="card-container fade-in">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1 fw-bold text-primary">
                        <i class="bi bi-pencil-square"></i> Sửa sự cố HDV
                    </h3>
                    <p class="text-muted mb-0">Cập nhật thông tin sự cố</p>
                </div>
                <a href="index.php?act=guide-incident" class="btn btn-secondary btn-modern">
                    <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
                </a>
            </div>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="index.php?act=guide-incident-update" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= $incident['id'] ?>">

                <div class="form-section fade-in">
                    <h5 class="mb-3"><i class="bi bi-info-circle"></i> Thông tin cơ bản</h5>
                    
                    <!-- HDV -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-person-badge"></i> Hướng dẫn viên <span class="text-danger">*</span></label>
                        <select name="guide_id" class="form-select" required>
                            <?php foreach($guides as $g): ?>
                                <option value="<?= $g['id'] ?>" 
                                    <?= $incident['guide_id'] == $g['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($g['fullname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Lịch trình -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-calendar-event"></i> Chuyến đi <span class="text-danger">*</span></label>
                        <select name="departure_id" class="form-select" required>
                            <?php foreach($departures as $d): ?>
                                <option value="<?= $d['id'] ?>"
                                    <?= $incident['departure_id'] == $d['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['tour_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Loại sự cố -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-tag"></i> Loại sự cố <span class="text-danger">*</span></label>
                        <input type="text" name="incident_type" class="form-control"
                               value="<?= htmlspecialchars($incident['incident_type']) ?>" required
                               placeholder="Nhập loại sự cố">
                    </div>

                    <!-- Mức độ -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-exclamation-triangle"></i> Mức độ <span class="text-danger">*</span></label>
                        <select name="severity" class="form-select" required>
                            <option value="low"    <?= $incident['severity']=='low'?'selected':'' ?>>Thấp</option>
                            <option value="medium" <?= $incident['severity']=='medium'?'selected':'' ?>>Trung bình</option>
                            <option value="high"   <?= $incident['severity']=='high'?'selected':'' ?>>Cao</option>
                        </select>
                    </div>
                </div>

                <div class="form-section fade-in">
                    <h5 class="mb-3"><i class="bi bi-file-text"></i> Chi tiết sự cố</h5>
                    
                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-card-text"></i> Mô tả <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required
                                  placeholder="Mô tả chi tiết về sự cố"><?= htmlspecialchars($incident['description']) ?></textarea>
                    </div>

                    <!-- Giải pháp -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-lightbulb"></i> Giải pháp</label>
                        <textarea name="solution" class="form-control" rows="3"
                                  placeholder="Giải pháp xử lý sự cố (nếu có)"><?= htmlspecialchars($incident['solution'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-section fade-in">
                    <h5 class="mb-3"><i class="bi bi-image"></i> Hình ảnh</h5>
                    
                    <!-- File hiện tại -->
                    <?php if (!empty($incident['photos'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Ảnh hiện tại</label>
                            <div class="photo-preview-container">
                                <img src="uploads/incidents/<?= htmlspecialchars($incident['photos']) ?>" 
                                     alt="Ảnh sự cố hiện tại" 
                                     class="photo-preview">
                                <p class="text-muted mt-2 mb-0"><small>Ảnh đang được sử dụng</small></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Upload mới -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-upload"></i> Tải ảnh mới (nếu muốn thay đổi)</label>
                        <input type="file" name="photos" class="form-control" accept="image/*" 
                               onchange="previewImage(this)">
                        <small class="text-muted">Chỉ chấp nhận file ảnh (JPG, PNG, GIF)</small>
                        <div id="newImagePreview" class="mt-3" style="display: none;">
                            <label class="form-label">Ảnh mới:</label>
                            <div class="photo-preview-container">
                                <img id="previewImg" src="" alt="Preview" class="photo-preview">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-modern">
                        <i class="bi bi-save"></i> Cập nhật
                    </button>
                    <a href="index.php?act=guide-incident" class="btn btn-secondary btn-modern">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewImage(input) {
    const preview = document.getElementById('newImagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>

</body>
</html>
