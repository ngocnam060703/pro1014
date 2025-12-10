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
<title>Thêm lịch trình</title>
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
.form-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
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
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-calendar-plus"></i> Thêm lịch trình</h3>
              <p class="text-muted mb-0">Tạo lịch trình mới cho tour</p>
          </div>
          <a href="index.php?act=schedule" class="btn btn-secondary btn-modern">
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

      <form action="index.php?act=schedule-store" method="post" id="scheduleForm">
          <div class="form-section fade-in">
              <h5 class="mb-3"><i class="bi bi-info-circle"></i> Thông tin cơ bản</h5>
              
              <div class="mb-3">
                  <label class="form-label"><i class="bi bi-map"></i> Chọn Tour <span class="text-danger">*</span></label>
                  <select name="tour_id" class="form-select" required>
                      <option value="">-- Chọn tour --</option>
                      <?php foreach($listTour as $tour): ?>
                          <option value="<?= $tour['id'] ?>"><?= htmlspecialchars($tour['title']) ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label"><i class="bi bi-calendar-event"></i> Ngày & giờ khởi hành <span class="text-danger">*</span></label>
                      <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" 
                             min="<?= date('Y-m-d\TH:i') ?>" required
                             onchange="validateDepartureDate()">
                      <small class="form-text text-muted">
                          <span id="departure-date-hint">Vui lòng chọn ngày khởi hành trong tương lai</span>
                      </small>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label"><i class="bi bi-calendar-check"></i> Ngày kết thúc</label>
                      <input type="date" name="end_date" id="end_date" class="form-control"
                             min="<?= date('Y-m-d') ?>"
                             onchange="validateDates()">
                      <small class="form-text text-muted">
                          <span id="end-date-hint">Để trống nếu tour 1 ngày. Phải >= ngày khởi hành</span>
                      </small>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label"><i class="bi bi-geo-alt"></i> Điểm tập trung <span class="text-danger">*</span></label>
                      <input type="text" name="meeting_point" class="form-control" 
                             placeholder="Ví dụ: Sân bay Nội Bài, Ga Hà Nội..." required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label"><i class="bi bi-clock"></i> Giờ kết thúc</label>
                      <input type="time" name="end_time" id="end_time" class="form-control">
                      <small class="form-text text-muted">Chỉ điền nếu có giờ kết thúc cụ thể</small>
                  </div>
              </div>

              <div class="mb-3">
                  <label class="form-label"><i class="bi bi-geo-alt-fill"></i> Địa chỉ tập trung</label>
                  <input type="text" name="meeting_address" class="form-control" 
                         placeholder="Địa chỉ chi tiết (tùy chọn)">
              </div>

              <div class="mb-3">
                  <label class="form-label"><i class="bi bi-info-circle"></i> Hướng dẫn tập trung</label>
                  <textarea name="meeting_instructions" class="form-control" rows="2" 
                            placeholder="Hướng dẫn cho khách hàng về điểm tập trung (tùy chọn)"></textarea>
              </div>

              <div class="mb-3">
                  <label class="form-label"><i class="bi bi-flag"></i> Trạng thái <span class="text-danger">*</span></label>
                  <select name="status" class="form-select" required>
                      <option value="scheduled" selected>Sắp khởi hành</option>
                      <option value="open">Đang mở bán</option>
                      <option value="upcoming">Sắp khởi hành</option>
                      <option value="in_progress">Đang chạy</option>
                      <option value="completed">Đã hoàn thành</option>
                      <option value="cancelled">Đã hủy</option>
                  </select>
                  <small class="form-text text-muted">Mặc định: Sắp khởi hành</small>
              </div>
          </div>

          <div class="form-section fade-in">
              <h5 class="mb-3"><i class="bi bi-people"></i> Thông tin chỗ ngồi</h5>
              
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label"><i class="bi bi-ticket-perforated"></i> Tổng số chỗ <span class="text-muted">(7-50 chỗ)</span> <span class="text-danger">*</span></label>
                      <input type="number" name="total_seats" id="total_seats" class="form-control" 
                             min="7" max="50" required 
                             onchange="updateTotalSeatsHint(this.value); calculateSeatsAvailable();">
                      <small class="form-text text-muted">
                          <span id="total-seats-hint">Gợi ý: Xe 7 chỗ, 16 chỗ, 29 chỗ, 35 chỗ, 45 chỗ, 50 chỗ</span>
                      </small>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label"><i class="bi bi-ticket"></i> Số chỗ còn <span class="text-muted">(tự động tính)</span> <span class="text-danger">*</span></label>
                      <input type="number" name="seats_available" id="seats_available" class="form-control" 
                             min="0" max="50" required readonly
                             onchange="updateSeatsHint(this.value)">
                      <small class="form-text text-muted">
                          <span id="seats-hint">Sẽ tự động = Tổng số chỗ - Số chỗ đã đặt</span>
                      </small>
                  </div>
              </div>
              
              <div class="mb-3">
                  <label class="form-label"><i class="bi bi-person-check"></i> Số chỗ đã đặt</label>
                  <input type="number" name="seats_booked" id="seats_booked" class="form-control" 
                         min="0" max="50" value="0" 
                         onchange="calculateSeatsAvailable()">
              </div>
          </div>

          <div class="form-section fade-in">
              <h5 class="mb-3"><i class="bi bi-sticky"></i> Ghi chú</h5>
              <div class="mb-3">
                  <label class="form-label">Ghi chú</label>
                  <textarea name="notes" class="form-control" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
              </div>
          </div>

          <div class="mt-4 d-flex gap-2">
              <button type="submit" class="btn btn-success btn-modern" id="submitBtn">
                  <i class="bi bi-save"></i> Lưu lịch trình
              </button>
              <a href="index.php?act=schedule" class="btn btn-secondary btn-modern">
                  <i class="bi bi-arrow-left"></i> Quay lại
              </a>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function updateTotalSeatsHint(value) {
    const hint = document.getElementById('total-seats-hint');
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

function updateSeatsHint(value) {
    const hint = document.getElementById('seats-hint');
    if (!hint) return;
    const seats = parseInt(value);
    if (seats < 0) {
        hint.textContent = 'Số chỗ còn không thể âm';
        hint.className = 'text-danger';
    } else {
        hint.textContent = 'Sẽ tự động = Tổng số chỗ - Số chỗ đã đặt';
        hint.className = 'text-muted';
    }
}

function calculateSeatsAvailable() {
    const totalSeats = parseInt(document.getElementById('total_seats').value) || 0;
    const seatsBooked = parseInt(document.getElementById('seats_booked').value) || 0;
    const seatsAvailable = Math.max(0, totalSeats - seatsBooked);
    
    document.getElementById('seats_available').value = seatsAvailable;
    updateSeatsHint(seatsAvailable);
}

function validateDepartureDate() {
    const departureTime = document.getElementById('departure_time').value;
    const departureHint = document.getElementById('departure-date-hint');
    const departureInput = document.getElementById('departure_time');
    
    if (!departureTime) {
        if (departureHint) {
            departureHint.textContent = 'Vui lòng chọn ngày khởi hành trong tương lai';
            departureHint.className = 'text-muted';
        }
        departureInput.setCustomValidity('');
        validateDates();
        return;
    }
    
    const departureDate = new Date(departureTime);
    const now = new Date();
    
    if (departureDate < now) {
        if (departureHint) {
            departureHint.textContent = '⚠️ Vui lòng chọn ngày khởi hành trong tương lai.';
            departureHint.className = 'text-danger';
        }
        departureInput.setCustomValidity('Vui lòng chọn ngày khởi hành trong tương lai.');
    } else {
        if (departureHint) {
            departureHint.textContent = '✓ Ngày khởi hành hợp lệ';
            departureHint.className = 'text-success';
        }
        departureInput.setCustomValidity('');
    }
    
    validateDates();
}

function validateDates() {
    const departureTime = document.getElementById('departure_time').value;
    const endDate = document.getElementById('end_date').value;
    const hint = document.getElementById('end-date-hint');
    
    if (!departureTime) {
        if (hint) {
            hint.textContent = 'Để trống nếu tour 1 ngày. Phải >= ngày khởi hành';
            hint.className = 'text-muted';
        }
        return;
    }
    
    if (endDate) {
        const departureDate = new Date(departureTime);
        const endDateObj = new Date(endDate);
        
        // So sánh chỉ phần ngày (bỏ qua giờ)
        departureDate.setHours(0, 0, 0, 0);
        endDateObj.setHours(0, 0, 0, 0);
        
        if (endDateObj < departureDate) {
            if (hint) {
                hint.textContent = '⚠️ Ngày kết thúc phải >= ngày khởi hành!';
                hint.className = 'text-danger';
            }
            document.getElementById('end_date').setCustomValidity('Ngày kết thúc phải >= ngày khởi hành');
        } else {
            if (hint) {
                hint.textContent = '✓ Ngày kết thúc hợp lệ';
                hint.className = 'text-success';
            }
            document.getElementById('end_date').setCustomValidity('');
        }
    } else {
        if (hint) {
            hint.textContent = 'Để trống nếu tour 1 ngày. Phải >= ngày khởi hành';
            hint.className = 'text-muted';
        }
        document.getElementById('end_date').setCustomValidity('');
    }
    
    // Cập nhật min của end_date khi departure_time thay đổi
    if (departureTime) {
        const departureDate = new Date(departureTime);
        departureDate.setHours(0, 0, 0, 0);
        const minDate = departureDate.toISOString().split('T')[0];
        document.getElementById('end_date').setAttribute('min', minDate);
    }
}

// Validate form trước khi submit
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    const departureTime = document.getElementById('departure_time').value;
    const endDate = document.getElementById('end_date').value;
    const totalSeats = parseInt(document.getElementById('total_seats').value) || 0;
    const tourId = document.querySelector('select[name="tour_id"]').value;
    
    // Kiểm tra tour đã chọn chưa
    if (!tourId) {
        e.preventDefault();
        alert('Vui lòng chọn Tour trước khi tạo lịch trình!');
        return false;
    }
    
    // Kiểm tra ngày khởi hành
    if (departureTime) {
        const departureDate = new Date(departureTime);
        const now = new Date();
        if (departureDate < now) {
            e.preventDefault();
            alert('Vui lòng chọn ngày khởi hành trong tương lai!');
            return false;
        }
    }
    
    // Kiểm tra ngày kết thúc
    if (endDate && departureTime) {
        const departureDate = new Date(departureTime);
        const endDateObj = new Date(endDate);
        departureDate.setHours(0, 0, 0, 0);
        endDateObj.setHours(0, 0, 0, 0);
        if (endDateObj < departureDate) {
            e.preventDefault();
            alert('Ngày kết thúc phải >= ngày khởi hành!');
            return false;
        }
    }
    
    // Kiểm tra số chỗ
    if (totalSeats < 7 || totalSeats > 50) {
        e.preventDefault();
        alert('Tổng số chỗ phải từ 7 đến 50 chỗ!');
        return false;
    }
    
    // Disable submit button để tránh double submit
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang xử lý...';
    
    return true;
});

// Khởi tạo giá trị mặc định
document.addEventListener('DOMContentLoaded', function() {
    // Set min date cho end_date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('end_date').setAttribute('min', today);
    
    // Tính seats_available ban đầu
    calculateSeatsAvailable();
});
</script>
</body>
</html>
