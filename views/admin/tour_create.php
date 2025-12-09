<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($tour) ? 'Sửa Tour' : 'Thêm Tour' ?></title>

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
.itinerary-day {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    margin-bottom: 15px;
    border-left: 4px solid #667eea;
}
.itinerary-day .card-header {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 12px 12px 0 0;
    border-bottom: 2px solid #e9ecef;
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
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
              <h3 class="mb-1 fw-bold text-primary">
                  <i class="bi bi-<?= isset($tour) ? 'pencil-square' : 'plus-circle' ?>"></i> <?= isset($tour) ? 'Sửa Tour' : 'Thêm Tour' ?>
              </h3>
              <p class="text-muted mb-0"><?= isset($tour) ? 'Cập nhật thông tin tour' : 'Điền thông tin để tạo tour mới' ?></p>
          </div>
          <a href="index.php?act=tour" class="btn btn-secondary btn-modern">
              <i class="bi bi-arrow-left-circle"></i> Quay lại
          </a>
      </div>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $_SESSION['message']; unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form action="index.php?act=<?= isset($tour) ? 'tour-update' : 'tour-store' ?>" method="POST">
        <?php if(isset($tour)) { ?>
            <input type="hidden" name="id" value="<?= $tour['id'] ?>">
        <?php } ?>

        <div class="form-section fade-in">
          <h5 class="mb-3"><i class="bi bi-info-circle"></i> Thông tin cơ bản</h5>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-tag"></i> Mã Tour <span class="text-danger">*</span></label>
              <input type="text" name="tour_code" id="tour_code" class="form-control" 
                     value="<?= $tour['tour_code'] ?? '' ?>" 
                     placeholder="Ví dụ: HN001, DN001, PQ001..." 
                     required
                     onchange="validateTourCode(this.value)">
              <small class="form-text text-muted">
                <span id="code-hint">Mã tour phải duy nhất, không được trùng với tour khác</span>
              </small>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-map"></i> Tên Tour (Chỉ địa điểm) <span class="text-danger">*</span></label>
              <input type="text" name="title" id="tour_title" class="form-control" 
                     value="<?= $tour['title'] ?? '' ?>" 
                     placeholder="Ví dụ: Hà Nội, Đà Nẵng, Phú Quốc, Hạ Long..." 
                     required
                     onchange="validateTourTitle(this.value)">
              <small class="form-text text-muted">
                <span id="title-hint">⚠️ Mỗi địa điểm chỉ được có 1 tour duy nhất. Chỉ nhập tên địa điểm, không ghi "1 ngày 2 đêm" hay thông tin khác.</span>
              </small>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label"><i class="bi bi-file-text"></i> Mô tả <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="3" required><?= $tour['description'] ?? '' ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label"><i class="bi bi-calendar3"></i> Lịch trình tổng quan <span class="text-danger">*</span></label>
            <textarea name="itinerary" class="form-control" rows="3" required><?= $tour['itinerary'] ?? '' ?></textarea>
          </div>
        </div>

        <!-- Lịch trình chi tiết theo ngày -->
        <div class="form-section fade-in mb-4">
          <h5 class="mb-3"><i class="bi bi-calendar-week"></i> Lịch trình chi tiết theo ngày</h5>
          <div id="itinerary-days-container">
            <!-- Các ngày sẽ được thêm vào đây bằng JavaScript -->
          </div>
          <button type="button" class="btn btn-primary btn-modern" onclick="addItineraryDay()">
            <i class="bi bi-plus-circle"></i> Thêm ngày
          </button>
          <small class="form-text text-muted d-block mt-2">
            <i class="bi bi-info-circle"></i> Phải có tối thiểu 1 ngày lịch trình. Các ngày phải theo đúng thứ tự.
          </small>
        </div>

        <!-- Giá tour -->
        <div class="form-section fade-in mb-4">
          <h5 class="mb-3"><i class="bi bi-currency-dollar"></i> Giá tour</h5>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Giá người lớn <span class="text-danger">*</span></label>
              <input type="number" name="adult_price" id="adult_price" class="form-control" 
                     value="<?= $tour['adult_price'] ?? '' ?>" 
                     min="0" step="1000" required
                     onchange="validatePrice(this, 'adult_price')">
              <small class="form-text text-muted">
                <span id="adult_price-hint">Giá cho người lớn (VNĐ)</span>
              </small>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Giá trẻ em <span class="text-danger">*</span></label>
              <input type="number" name="child_price" id="child_price" class="form-control" 
                     value="<?= $tour['child_price'] ?? '' ?>" 
                     min="0" step="1000" required
                     onchange="validatePrice(this, 'child_price')">
              <small class="form-text text-muted">
                <span id="child_price-hint">Giá cho trẻ em (VNĐ)</span>
              </small>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Giá trẻ nhỏ <span class="text-danger">*</span></label>
              <input type="number" name="infant_price" id="infant_price" class="form-control" 
                     value="<?= $tour['infant_price'] ?? '' ?>" 
                     min="0" step="1000" required
                     onchange="validatePrice(this, 'infant_price')">
              <small class="form-text text-muted">
                <span id="infant_price-hint">Giá cho trẻ nhỏ (VNĐ)</span>
              </small>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Phụ phí</label>
              <input type="number" name="surcharge" id="surcharge" class="form-control" 
                     value="<?= $tour['surcharge'] ?? '0' ?>" 
                     min="0" step="1000"
                     onchange="validatePrice(this, 'surcharge')">
              <small class="form-text text-muted">
                <span id="surcharge-hint">Phụ phí (nếu có, VNĐ)</span>
              </small>
            </div>
          </div>
        </div>

        <div class="form-section fade-in mb-4">
          <h5 class="mb-3"><i class="bi bi-gear"></i> Thông tin khác</h5>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-people"></i> Số chỗ <span class="text-muted">(7-50 chỗ)</span> <span class="text-danger">*</span></label>
              <input type="number" name="slots" class="form-control" value="<?= $tour['slots'] ?? '' ?>" 
                     min="7" max="50" required 
                     onchange="updateSeatsHint(this.value)">
              <small class="form-text text-muted">
                <span id="seats-hint">Gợi ý: Xe 7 chỗ, 16 chỗ, 29 chỗ, 35 chỗ, 45 chỗ, 50 chỗ</span>
              </small>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-geo-alt"></i> Điểm khởi hành <span class="text-danger">*</span></label>
              <input type="text" name="departure" class="form-control" value="<?= $tour['departure'] ?? '' ?>" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-tags"></i> Danh mục tour <span class="text-danger">*</span></label>
              <select name="category" class="form-select" required>
                <option value="domestic" <?= (isset($tour) && ($tour['category'] ?? '')=='domestic')?'selected':'' ?>>Tour trong nước</option>
                <option value="international" <?= (isset($tour) && ($tour['category'] ?? '')=='international')?'selected':'' ?>>Tour quốc tế</option>
                <option value="customized" <?= (isset($tour) && ($tour['category'] ?? '')=='customized')?'selected':'' ?>>Tour theo yêu cầu</option>
              </select>
              <small class="form-text text-muted">
                <strong>Tour trong nước:</strong> Tour tham quan, du lịch các địa điểm trong nước.<br>
                <strong>Tour quốc tế:</strong> Tour tham quan, du lịch các nước ngoài.<br>
                <strong>Tour theo yêu cầu:</strong> Tour thiết kế riêng dựa trên yêu cầu cụ thể của từng khách hàng/đoàn khách.
              </small>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-flag"></i> Trạng thái <span class="text-danger">*</span></label>
              <select name="status" class="form-select">
                <option value="active" <?= (isset($tour) && $tour['status']=='active')?'selected':'' ?>>Active</option>
                <option value="inactive" <?= (isset($tour) && $tour['status']=='inactive')?'selected':'' ?>>Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-success btn-modern">
              <i class="bi bi-save"></i> <?= isset($tour) ? 'Cập nhật' : 'Lưu' ?>
          </button>
          <a href="index.php?act=tour" class="btn btn-secondary btn-modern">
              <i class="bi bi-arrow-left"></i> Quay lại
          </a>
        </div>
      </form>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validateTourCode(value) {
    const hint = document.getElementById('code-hint');
    if (!hint) return;
    
    const code = value.trim();
    
    if (code.length === 0) {
        hint.textContent = 'Mã tour phải duy nhất, không được trùng với tour khác';
        hint.className = 'text-muted';
        return false;
    } else if (code.length < 3) {
        hint.textContent = '⚠️ Mã tour quá ngắn, nên có ít nhất 3 ký tự';
        hint.className = 'text-warning';
        return false;
    } else {
        hint.textContent = '✓ Mã tour hợp lệ';
        hint.className = 'text-success';
        return true;
    }
}

function validateTourTitle(value) {
    const hint = document.getElementById('title-hint');
    if (!hint) return;
    
    const title = value.trim().toLowerCase();
    
    // Kiểm tra các từ khóa không được phép
    const forbiddenPatterns = [
        /\d+\s*ngày/i,
        /\d+\s*đêm/i,
        /ngày\s*\d+/i,
        /đêm\s*\d+/i,
        /\d+\s*ngày\s*\d+\s*đêm/i,
        /\d+\s*đêm\s*\d+\s*ngày/i,
        /ngày\s*đêm/i,
        /đêm\s*ngày/i
    ];
    
    let hasForbidden = false;
    for (let pattern of forbiddenPatterns) {
        if (pattern.test(title)) {
            hasForbidden = true;
            break;
        }
    }
    
    if (hasForbidden) {
        hint.textContent = '⚠️ Tên tour chỉ nên là địa điểm, không ghi "ngày", "đêm" hay số ngày/đêm';
        hint.className = 'text-danger';
        return false;
    } else if (title.length > 0) {
        hint.textContent = '✓ Tên tour hợp lệ (chỉ địa điểm)';
        hint.className = 'text-success';
        return true;
    } else {
        hint.textContent = 'Chỉ nhập tên địa điểm, không ghi "1 ngày 2 đêm" hay thông tin khác';
        hint.className = 'text-muted';
        return true;
    }
}

function updateSeatsHint(value) {
    const hint = document.getElementById('seats-hint');
    if (!hint) return;
    
    const seats = parseInt(value);
    let vehicleType = '';
    
    if (seats >= 7 && seats <= 9) {
        vehicleType = 'Xe 7-9 chỗ (xe gia đình)';
    } else if (seats >= 10 && seats <= 19) {
        vehicleType = 'Xe 16 chỗ';
    } else if (seats >= 20 && seats <= 32) {
        vehicleType = 'Xe 29 chỗ';
    } else if (seats >= 33 && seats <= 40) {
        vehicleType = 'Xe 35 chỗ';
    } else if (seats >= 41 && seats <= 47) {
        vehicleType = 'Xe 45 chỗ';
    } else if (seats >= 48 && seats <= 50) {
        vehicleType = 'Xe 50 chỗ';
    } else if (seats > 50) {
        vehicleType = 'Vượt quá giới hạn xe khách thông thường';
    } else if (seats < 7) {
        vehicleType = 'Số chỗ quá ít, tối thiểu 7 chỗ';
    }
    
    if (vehicleType) {
        hint.textContent = vehicleType;
        hint.className = seats >= 7 && seats <= 50 ? 'text-success' : 'text-danger';
    } else {
        hint.textContent = 'Gợi ý: Xe 7 chỗ, 16 chỗ, 29 chỗ, 35 chỗ, 45 chỗ, 50 chỗ';
        hint.className = 'text-muted';
    }
}

function validatePrice(input, fieldName) {
    const hint = document.getElementById(fieldName + '-hint');
    if (!hint) return;
    
    const value = parseFloat(input.value);
    
    if (isNaN(value) || value === '') {
        hint.textContent = '⚠️ Giá không được để trống';
        hint.className = 'text-danger';
        input.setCustomValidity('Giá không được để trống');
        return false;
    } else if (value < 0) {
        hint.textContent = '⚠️ Giá không được nhỏ hơn 0';
        hint.className = 'text-danger';
        input.setCustomValidity('Giá không được nhỏ hơn 0');
        return false;
    } else {
        const formatted = new Intl.NumberFormat('vi-VN').format(value);
        hint.textContent = '✓ ' + formatted + ' VNĐ';
        hint.className = 'text-success';
        input.setCustomValidity('');
        return true;
    }
}

// Quản lý lịch trình theo ngày
let itineraryDayCount = 0;

function addItineraryDay(dayData = null) {
    itineraryDayCount++;
    const dayNumber = dayData ? dayData.day_number : itineraryDayCount;
    const container = document.getElementById('itinerary-days-container');
    
    const dayHtml = `
        <div class="card mb-3 itinerary-day" data-day="${dayNumber}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-day"></i> Ngày ${dayNumber}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItineraryDay(this)">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </div>
            <div class="card-body">
                <input type="hidden" name="itinerary_days[${dayNumber}][day_number]" value="${dayNumber}">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lịch trình <span class="text-danger">*</span></label>
                        <textarea name="itinerary_days[${dayNumber}][schedule]" class="form-control" rows="2" required>${dayData ? (dayData.schedule || '') : ''}</textarea>
                        <small class="text-muted">Lịch trình trong ngày</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Đi đâu <span class="text-danger">*</span></label>
                        <textarea name="itinerary_days[${dayNumber}][destinations]" class="form-control" rows="2" required>${dayData ? (dayData.destinations || '') : ''}</textarea>
                        <small class="text-muted">Điểm đến/đi đâu</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Các điểm tham quan <span class="text-danger">*</span></label>
                    <textarea name="itinerary_days[${dayNumber}][attractions]" class="form-control" rows="2" required>${dayData ? (dayData.attractions || '') : ''}</textarea>
                    <small class="text-muted">Danh sách các điểm tham quan</small>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thời gian di chuyển</label>
                        <input type="text" name="itinerary_days[${dayNumber}][travel_time]" class="form-control" 
                               value="${dayData ? (dayData.travel_time || '') : ''}" 
                               placeholder="Ví dụ: 2 giờ, 8:00-10:00">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thời gian nghỉ ngơi</label>
                        <input type="text" name="itinerary_days[${dayNumber}][rest_time]" class="form-control" 
                               value="${dayData ? (dayData.rest_time || '') : ''}" 
                               placeholder="Ví dụ: 30 phút, 12:00-13:00">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thời gian ăn uống</label>
                        <input type="text" name="itinerary_days[${dayNumber}][meal_time]" class="form-control" 
                               value="${dayData ? (dayData.meal_time || '') : ''}" 
                               placeholder="Ví dụ: 12:00-13:00, 18:00-19:00">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', dayHtml);
    updateDayNumbers();
}

function removeItineraryDay(button) {
    const dayCard = button.closest('.itinerary-day');
    if (confirm('Bạn có chắc muốn xóa ngày này?')) {
        dayCard.remove();
        updateDayNumbers();
    }
}

function updateDayNumbers() {
    const days = document.querySelectorAll('.itinerary-day');
    days.forEach((day, index) => {
        const dayNumber = index + 1;
        day.setAttribute('data-day', dayNumber);
        day.querySelector('.card-header h6').innerHTML = `<i class="bi bi-calendar-day"></i> Ngày ${dayNumber}`;
        
        // Cập nhật tất cả các input trong ngày này
        const inputs = day.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/itinerary_days\[\d+\]/, `itinerary_days[${dayNumber}]`);
                input.setAttribute('name', newName);
            }
        });
        
        // Cập nhật hidden day_number
        const hiddenInput = day.querySelector('input[type="hidden"][name*="day_number"]');
        if (hiddenInput) {
            hiddenInput.value = dayNumber;
        }
    });
}

// Thêm ngày đầu tiên khi trang load
document.addEventListener('DOMContentLoaded', function() {
    addItineraryDay();
});

// Validate form trước khi submit
document.querySelector('form').addEventListener('submit', function(e) {
    const adultPrice = document.getElementById('adult_price');
    const childPrice = document.getElementById('child_price');
    const infantPrice = document.getElementById('infant_price');
    
    if (!validatePrice(adultPrice, 'adult_price') || 
        !validatePrice(childPrice, 'child_price') || 
        !validatePrice(infantPrice, 'infant_price')) {
        e.preventDefault();
        alert('Vui lòng kiểm tra lại các trường giá. Giá không được để trống và không được nhỏ hơn 0.');
        return false;
    }
    
    // Validate lịch trình chi tiết
    const days = document.querySelectorAll('.itinerary-day');
    if (days.length === 0) {
        e.preventDefault();
        alert('Phải có tối thiểu 1 ngày lịch trình.');
        return false;
    }
    
    // Kiểm tra các trường bắt buộc
    let hasError = false;
    days.forEach((day, index) => {
        const schedule = day.querySelector('textarea[name*="[schedule]"]');
        const destinations = day.querySelector('textarea[name*="[destinations]"]');
        const attractions = day.querySelector('textarea[name*="[attractions]"]');
        
        if (!schedule.value.trim() || !destinations.value.trim() || !attractions.value.trim()) {
            hasError = true;
        }
    });
    
    if (hasError) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin cho tất cả các ngày. Các trường: Lịch trình, Đi đâu, Các điểm tham quan là bắt buộc.');
        return false;
    }
    
    return true;
});
</script>
</body>
</html>
