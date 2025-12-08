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
<title>Sửa lịch trình</title>
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
.btn-primary, .btn-success, .btn-secondary {
    border-radius: 50px;
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
    <a href="index.php?act=schedule" class="active"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
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
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-pencil-square"></i> Sửa lịch trình
      </h3>
      <a href="index.php?act=schedule" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
      </a>
    </div>

    <div class="card p-4">
      <form action="index.php?act=schedule-update" method="post">
        <input type="hidden" name="id" value="<?= $schedule['id'] ?>">

        <div class="mb-3">
          <label class="form-label">Chọn Tour</label>
          <select name="tour_id" class="form-select" required>
            <option value="">-- Chọn tour --</option>
            <?php foreach($listTour as $tour): ?>
              <option value="<?= $tour['id'] ?>" <?= $schedule['tour_id']==$tour['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($tour['title']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Ngày & giờ khởi hành <span class="text-danger">*</span></label>
            <input type="datetime-local" name="departure_time" id="departure_time" class="form-control"
                   value="<?= date('Y-m-d\TH:i', strtotime($schedule['departure_time'])) ?>" 
                   min="<?= date('Y-m-d\TH:i') ?>" required
                   onchange="validateDepartureDate()">
            <small class="form-text text-muted">
              <span id="departure-date-hint">Vui lòng chọn ngày khởi hành trong tương lai</span>
            </small>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                   value="<?= !empty($schedule['end_date']) ? date('Y-m-d', strtotime($schedule['end_date'])) : '' ?>"
                   min="<?= date('Y-m-d') ?>"
                   onchange="validateDates()">
            <small class="form-text text-muted">
              <span id="end-date-hint">Để trống nếu tour 1 ngày. Phải >= ngày khởi hành</span>
            </small>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Điểm tập trung</label>
          <input type="text" name="meeting_point" class="form-control" value="<?= $schedule['meeting_point'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Trạng thái</label>
          <select name="status" class="form-select" required>
            <option value="open" <?= ($schedule['status'] ?? '') == 'open' ? 'selected' : '' ?>>Đang mở bán</option>
            <option value="upcoming" <?= ($schedule['status'] ?? '') == 'upcoming' ? 'selected' : '' ?>>Sắp khởi hành</option>
            <option value="in_progress" <?= ($schedule['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>Đang chạy</option>
            <option value="completed" <?= ($schedule['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
            <option value="cancelled" <?= ($schedule['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
          </select>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Tổng số chỗ <span class="text-muted">(7-50 chỗ)</span></label>
            <input type="number" name="total_seats" id="total_seats" class="form-control" 
                   value="<?= $schedule['total_seats'] ?? ($schedule['seats_available'] + $schedule['seats_booked']) ?>" 
                   min="7" max="50" required 
                   onchange="updateTotalSeatsHint(this.value); calculateSeatsAvailable();">
            <small class="form-text text-muted">
              <span id="total-seats-hint">Gợi ý: Xe 7 chỗ, 16 chỗ, 29 chỗ, 35 chỗ, 45 chỗ, 50 chỗ</span>
            </small>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Số chỗ còn <span class="text-muted">(tự động tính)</span></label>
            <input type="number" name="seats_available" id="seats_available" class="form-control" 
                   value="<?= $schedule['seats_available'] ?>" 
                   min="0" max="50" required readonly
                   onchange="updateSeatsHint(this.value)">
            <small class="form-text text-muted">
              <span id="seats-hint">Sẽ tự động = Tổng số chỗ - Số chỗ đã đặt</span>
            </small>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Số chỗ đã đặt</label>
          <input type="number" name="seats_booked" id="seats_booked" class="form-control" 
                 value="<?= $schedule['seats_booked'] ?? 0 ?>" 
                 min="0" max="50" 
                 onchange="calculateSeatsAvailable()">
        </div>

        <div class="mb-3">
          <label class="form-label">Ghi chú</label>
          <textarea name="notes" class="form-control" rows="3"><?= $schedule['notes'] ?></textarea>
        </div>

        <button type="submit" class="btn btn-success me-2">
          <i class="bi bi-save"></i> Cập nhật
        </button>
        <a href="index.php?act=schedule" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>

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
</script>
</body>
</html>
