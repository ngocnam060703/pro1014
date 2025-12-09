<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Khi sửa, gán mặc định để tránh lỗi
$guide = $guide ?? ['id'=>'', 'fullname'=>'', 'phone'=>'', 'email'=>'', 'certificate'=>'', 
                     'date_of_birth'=>'', 'photo'=>'', 'address'=>'', 'languages'=>'', 
                     'experience_years'=>0, 'experience_description'=>'', 'health_status'=>'good',
                     'health_notes'=>'', 'specializations'=>'', 'status'=>'active', 'notes'=>''];
$categories = $categories ?? [];
$selectedCategories = array_column($categories, 'category_type');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa Hướng dẫn viên</title>
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
.nav-tabs {
    border-bottom: 2px solid #e9ecef;
}
.nav-tabs .nav-link {
    border: none;
    color: #667eea;
    font-weight: 500;
    padding: 12px 24px;
    transition: all 0.3s;
}
.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #764ba2;
}
.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 10px 10px 0 0;
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
.btn-success {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
}
.btn-success:hover {
    background: linear-gradient(135deg, #20c997 0%, #198754 100%);
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
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-pencil-square"></i> Sửa Hướng dẫn viên</h3>
              <p class="text-muted mb-0">Cập nhật thông tin nhân viên</p>
          </div>
          <a href="index.php?act=guide" class="btn btn-secondary btn-modern">
              <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
          </a>
      </div>

      <div class="card-container">
      <form action="index.php?act=guide-update" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $guide['id'] ?>">

          <ul class="nav nav-tabs mb-4" id="guideTabs" role="tablist">
              <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                      <i class="bi bi-info-circle"></i> Thông tin cơ bản
                  </button>
              </li>
              <li class="nav-item" role="presentation">
                  <button class="nav-link" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button">
                      <i class="bi bi-file-text"></i> Thông tin chi tiết
                  </button>
              </li>
              <li class="nav-item" role="presentation">
                  <button class="nav-link" id="category-tab" data-bs-toggle="tab" data-bs-target="#category" type="button">
                      <i class="bi bi-tags"></i> Phân loại
                  </button>
              </li>
          </ul>

          <div class="tab-content" id="guideTabsContent">
              <!-- Tab Thông tin cơ bản -->
              <div class="tab-pane fade show active" id="basic" role="tabpanel">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="mb-3">
                              <label class="form-label">Tên đầy đủ <span class="text-danger">*</span></label>
                              <input type="text" name="fullname" class="form-control" 
                                     value="<?= htmlspecialchars($guide['fullname'] ?? '') ?>" required>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="mb-3">
                              <label class="form-label">Số điện thoại</label>
                              <input type="text" name="phone" class="form-control" 
                                     value="<?= htmlspecialchars($guide['phone'] ?? '') ?>">
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-6">
                          <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input type="email" name="email" class="form-control" 
                                     value="<?= htmlspecialchars($guide['email'] ?? '') ?>">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="mb-3">
                              <label class="form-label">Ngày sinh</label>
                              <input type="date" name="date_of_birth" class="form-control" 
                                     value="<?= htmlspecialchars($guide['date_of_birth'] ?? '') ?>">
                          </div>
                      </div>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Địa chỉ</label>
                      <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($guide['address'] ?? '') ?></textarea>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Ảnh đại diện (URL)</label>
                      <input type="text" name="photo" class="form-control" 
                             placeholder="https://example.com/photo.jpg"
                             value="<?= htmlspecialchars($guide['photo'] ?? '') ?>">
                      <?php if (!empty($guide['photo'])): ?>
                          <div class="mt-2">
                              <img src="<?= htmlspecialchars($guide['photo']) ?>" alt="Avatar" class="rounded" style="max-width: 150px; max-height: 150px;">
                          </div>
                      <?php endif; ?>
                  </div>
              </div>

              <!-- Tab Thông tin chi tiết -->
              <div class="tab-pane fade" id="detail" role="tabpanel">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="mb-3">
                              <label class="form-label">Số năm kinh nghiệm</label>
                              <input type="number" name="experience_years" class="form-control" min="0" 
                                     value="<?= htmlspecialchars($guide['experience_years'] ?? '0') ?>">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="mb-3">
                              <label class="form-label">Tình trạng sức khỏe</label>
                              <select name="health_status" class="form-select">
                                  <option value="excellent" <?= (($guide['health_status'] ?? '') == 'excellent') ? 'selected' : '' ?>>Tuyệt vời</option>
                                  <option value="good" <?= (($guide['health_status'] ?? '') == 'good' || empty($guide['health_status'])) ? 'selected' : '' ?>>Tốt</option>
                                  <option value="fair" <?= (($guide['health_status'] ?? '') == 'fair') ? 'selected' : '' ?>>Khá</option>
                                  <option value="poor" <?= (($guide['health_status'] ?? '') == 'poor') ? 'selected' : '' ?>>Yếu</option>
                              </select>
                          </div>
                      </div>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Ngôn ngữ sử dụng</label>
                      <input type="text" name="languages" class="form-control" 
                             placeholder="Ví dụ: Tiếng Việt, Tiếng Anh, Tiếng Trung"
                             value="<?= htmlspecialchars($guide['languages'] ?? '') ?>">
                      <small class="text-muted">Phân cách bằng dấu phẩy</small>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Mô tả kinh nghiệm</label>
                      <textarea name="experience_description" class="form-control" rows="4" 
                                placeholder="Mô tả chi tiết về kinh nghiệm dẫn tour..."><?= htmlspecialchars($guide['experience_description'] ?? '') ?></textarea>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Chuyên môn đặc biệt</label>
                      <textarea name="specializations" class="form-control" rows="3" 
                                placeholder="Ví dụ: Chuyên tour văn hóa, tour ẩm thực, tour mạo hiểm..."><?= htmlspecialchars($guide['specializations'] ?? '') ?></textarea>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Ghi chú về sức khỏe</label>
                      <textarea name="health_notes" class="form-control" rows="2"><?= htmlspecialchars($guide['health_notes'] ?? '') ?></textarea>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Chứng chỉ</label>
                      <input type="text" name="certificate" class="form-control" 
                             value="<?= htmlspecialchars($guide['certificate'] ?? '') ?>">
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Trạng thái</label>
                      <select name="status" class="form-select">
                          <option value="active" <?= (($guide['status'] ?? 'active') == 'active') ? 'selected' : '' ?>>Đang hoạt động</option>
                          <option value="inactive" <?= (($guide['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Tạm nghỉ</option>
                          <option value="on_leave" <?= (($guide['status'] ?? '') == 'on_leave') ? 'selected' : '' ?>>Nghỉ phép</option>
                      </select>
                  </div>

                  <div class="mb-3">
                      <label class="form-label">Ghi chú khác</label>
                      <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($guide['notes'] ?? '') ?></textarea>
                  </div>
              </div>

              <!-- Tab Phân loại -->
              <div class="tab-pane fade" id="category" role="tabpanel">
                  <div class="mb-3">
                      <label class="form-label">Phân loại HDV <span class="text-muted">(có thể chọn nhiều)</span></label>
                      <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" name="categories[]" value="domestic" id="cat_domestic" 
                                 <?= in_array('domestic', $selectedCategories) ? 'checked' : '' ?>>
                          <label class="form-check-label" for="cat_domestic">
                              <i class="bi bi-geo-alt"></i> Tour trong nước
                          </label>
                      </div>
                      <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" name="categories[]" value="international" id="cat_international"
                                 <?= in_array('international', $selectedCategories) ? 'checked' : '' ?>>
                          <label class="form-check-label" for="cat_international">
                              <i class="bi bi-globe"></i> Tour quốc tế
                          </label>
                      </div>
                      <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" name="categories[]" value="specialized_route" id="cat_specialized"
                                 <?= in_array('specialized_route', $selectedCategories) ? 'checked' : '' ?>>
                          <label class="form-check-label" for="cat_specialized">
                              <i class="bi bi-signpost"></i> Chuyên tuyến
                          </label>
                      </div>
                      <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" name="categories[]" value="group_tour" id="cat_group"
                                 <?= in_array('group_tour', $selectedCategories) ? 'checked' : '' ?>>
                          <label class="form-check-label" for="cat_group">
                              <i class="bi bi-people"></i> Chuyên khách đoàn
                          </label>
                      </div>
                      <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" name="categories[]" value="customized" id="cat_customized"
                                 <?= in_array('customized', $selectedCategories) ? 'checked' : '' ?>>
                          <label class="form-check-label" for="cat_customized">
                              <i class="bi bi-gear"></i> Tour theo yêu cầu
                          </label>
                      </div>
                  </div>
              </div>
          </div>

          <div class="mt-4 d-flex gap-2">
              <button type="submit" class="btn btn-success btn-modern">
                  <i class="bi bi-save"></i> Lưu thay đổi
              </button>
              <a href="index.php?act=guide" class="btn btn-secondary btn-modern">Hủy</a>
          </div>
      </form>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
