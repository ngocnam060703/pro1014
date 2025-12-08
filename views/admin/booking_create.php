<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../models/TourModel.php";
require_once __DIR__ . "/../../models/LichModel.php";
require_once __DIR__ . "/../../models/BookingModel.php";

$tourModel = new TourModel();
$lichModel = new LichModel();
$bookingModel = new BookingModel();

$tours = $tourModel->getAllTours();
$selected_tour_id = $_GET['tour_id'] ?? null;
$selected_departure_id = $_GET['departure_id'] ?? null;
$departures = [];
$tour = null;

if ($selected_tour_id) {
    $tour = $tourModel->getTourById($selected_tour_id);
    $departures = $lichModel->getLichByTour($selected_tour_id);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tạo Booking Mới</title>

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
.btn-success, .btn-secondary {
    border-radius: 50px;
}
#priceSummary {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
}
.guest-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: #fff;
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
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking" class="active"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card p-4">
      <h3 class="mb-4 fw-bold text-primary">
        <i class="bi bi-plus-circle"></i> Tạo Booking Mới
      </h3>

      <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> <?= $_SESSION['message'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <form action="index.php?act=booking-store" method="POST" id="bookingForm">
        
        <!-- Loại booking -->
        <div class="mb-4">
          <label class="form-label fw-bold">Loại Booking</label>
          <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="booking_type" id="type_individual" value="individual" checked>
            <label class="btn btn-outline-primary" for="type_individual">
              <i class="bi bi-person"></i> Khách lẻ (1-2 người)
            </label>
            
            <input type="radio" class="btn-check" name="booking_type" id="type_group" value="group">
            <label class="btn btn-outline-primary" for="type_group">
              <i class="bi bi-people"></i> Đoàn (Nhiều người/Công ty/Tổ chức)
            </label>
          </div>
        </div>

        <div class="row">
          <!-- Thông tin Tour -->
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="bi bi-map"></i> Thông tin Tour</h5>
            
            <div class="mb-3">
              <label class="form-label">Chọn Tour <span class="text-danger">*</span></label>
              <select name="tour_id" id="tour_id" class="form-select" required onchange="loadDepartures()">
                <option value="">-- Chọn tour --</option>
                <?php foreach ($tours as $t): ?>
                <option value="<?= $t['id'] ?>" <?= ($selected_tour_id == $t['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($t['title']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Lịch khởi hành</label>
              <select name="departure_id" id="departure_id" class="form-select" onchange="checkAvailability()">
                <option value="">-- Chọn lịch khởi hành --</option>
                <?php foreach ($departures as $d): ?>
                <option value="<?= $d['id'] ?>" <?= ($selected_departure_id == $d['id']) ? 'selected' : '' ?>>
                  <?= date('d/m/Y H:i', strtotime($d['departure_time'])) ?> - 
                  <?= htmlspecialchars($d['meeting_point']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Ngày đặt tour <span class="text-danger">*</span></label>
              <input type="date" name="booking_date" id="booking_date" class="form-control" 
                     value="<?= date('Y-m-d') ?>" 
                     min="<?= date('Y-m-d') ?>" 
                     required
                     onchange="validateBookingDate()">
              <small class="form-text text-muted">
                <span id="booking-date-hint">Vui lòng chọn ngày đặt tour trong tương lai</span>
              </small>
            </div>

            <div class="mb-3">
              <label class="form-label">Ngày khởi hành</label>
              <input type="date" name="departure_date" id="departure_date" class="form-control" 
                     min="<?= date('Y-m-d') ?>" 
                     onchange="validateDepartureDate(); checkAvailability();">
              <small class="form-text text-muted">
                <span id="departure-date-hint">Vui lòng chọn ngày khởi hành trong tương lai</span>
              </small>
            </div>

            <!-- Thông báo chỗ trống -->
            <div id="availabilityAlert" class="alert d-none"></div>
          </div>

          <!-- Thông tin khách hàng -->
          <div class="col-md-6">
            <h5 class="mb-3 text-primary"><i class="bi bi-person-circle"></i> Thông tin khách hàng</h5>
            
            <div class="mb-3">
              <label class="form-label">Họ tên <span class="text-danger">*</span></label>
              <input type="text" name="customer_name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="customer_email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
              <input type="tel" name="customer_phone" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Địa chỉ</label>
              <textarea name="customer_address" class="form-control" rows="2"></textarea>
            </div>

            <!-- Thông tin công ty (chỉ hiện khi chọn đoàn) -->
            <div id="companyInfo" style="display: none;">
              <div class="mb-3">
                <label class="form-label">Tên công ty/Tổ chức</label>
                <input type="text" name="company_name" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label">Mã số thuế</label>
                <input type="text" name="tax_code" class="form-control">
              </div>
            </div>
          </div>
        </div>

        <!-- Số lượng khách -->
        <div class="mb-4">
          <h5 class="mb-3 text-primary"><i class="bi bi-people"></i> Số lượng khách</h5>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Người lớn <span class="text-danger">*</span></label>
              <input type="number" name="num_adults" id="num_adults" class="form-control" value="1" min="0" required onchange="calculatePrice()">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Trẻ em (2-11 tuổi)</label>
              <input type="number" name="num_children" id="num_children" class="form-control" value="0" min="0" onchange="calculatePrice()">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Trẻ sơ sinh (dưới 2 tuổi)</label>
              <input type="number" name="num_infants" id="num_infants" class="form-control" value="0" min="0" onchange="calculatePrice()">
            </div>
          </div>
          <input type="hidden" name="num_people" id="num_people" value="1">
        </div>

        <!-- Danh sách khách (cho đoàn) -->
        <div id="guestsList" style="display: none;">
          <h5 class="mb-3 text-primary"><i class="bi bi-list-ul"></i> Danh sách khách (cho đoàn)</h5>
          <div id="guestsContainer"></div>
          <button type="button" class="btn btn-sm btn-outline-primary" onclick="addGuest()">
            <i class="bi bi-plus"></i> Thêm khách
          </button>
        </div>

        <!-- Yêu cầu đặc biệt -->
        <div class="mb-3">
          <label class="form-label">Yêu cầu đặc biệt</label>
          <textarea name="special_requests" class="form-control" rows="3" placeholder="Ví dụ: Ăn chay, yêu cầu phòng đơn, hỗ trợ xe lăn..."></textarea>
        </div>

        <!-- Ghi chú -->
        <div class="mb-3">
          <label class="form-label">Ghi chú</label>
          <textarea name="notes" class="form-control" rows="2"></textarea>
        </div>

        <!-- Tóm tắt giá -->
        <div id="priceSummary">
          <h5 class="mb-3">Tóm tắt giá</h5>
          <div id="priceDetails"></div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-success btn-lg me-2">
            <i class="bi bi-check-circle"></i> Tạo Booking
          </button>
          <a href="index.php?act=booking" class="btn btn-secondary btn-lg">
            <i class="bi bi-arrow-left"></i> Quay lại
          </a>
        </div>
      </form>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Xử lý thay đổi loại booking
document.querySelectorAll('input[name="booking_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'group') {
            document.getElementById('companyInfo').style.display = 'block';
            document.getElementById('guestsList').style.display = 'block';
        } else {
            document.getElementById('companyInfo').style.display = 'none';
            document.getElementById('guestsList').style.display = 'none';
        }
    });
});

// Load lịch khởi hành khi chọn tour
function loadDepartures() {
    const tourId = document.getElementById('tour_id').value;
    if (!tourId) return;
    
    window.location.href = 'index.php?act=booking-create&tour_id=' + tourId;
}

// Kiểm tra chỗ trống
function checkAvailability() {
    const tourId = document.getElementById('tour_id').value;
    const departureId = document.getElementById('departure_id').value;
    const departureDate = document.getElementById('departure_date').value;
    const numPeople = parseInt(document.getElementById('num_people').value) || 1;
    
    if (!tourId || !numPeople) return;
    
    // Gọi AJAX để kiểm tra
    fetch('index.php?act=booking-check-availability&tour_id=' + tourId + 
          '&departure_id=' + departureId + '&departure_date=' + departureDate + 
          '&num_people=' + numPeople)
        .then(response => response.json())
        .then(data => {
            const alert = document.getElementById('availabilityAlert');
            if (data.available) {
                alert.className = 'alert alert-success';
                alert.innerHTML = '<i class="bi bi-check-circle"></i> ' + data.message;
            } else {
                alert.className = 'alert alert-warning';
                alert.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + data.message;
            }
            alert.classList.remove('d-none');
        });
}

// Validate ngày đặt tour
function validateBookingDate() {
    const bookingDate = document.getElementById('booking_date').value;
    const bookingHint = document.getElementById('booking-date-hint');
    const bookingInput = document.getElementById('booking_date');
    
    if (!bookingDate) {
        if (bookingHint) {
            bookingHint.textContent = 'Vui lòng chọn ngày đặt tour trong tương lai';
            bookingHint.className = 'text-muted';
        }
        bookingInput.setCustomValidity('');
        return;
    }
    
    const selectedDate = new Date(bookingDate);
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);
    
    if (selectedDate < now) {
        if (bookingHint) {
            bookingHint.textContent = '⚠️ Vui lòng chọn ngày đặt tour trong tương lai.';
            bookingHint.className = 'text-danger';
        }
        bookingInput.setCustomValidity('Vui lòng chọn ngày đặt tour trong tương lai.');
    } else {
        if (bookingHint) {
            bookingHint.textContent = '✓ Ngày đặt tour hợp lệ';
            bookingHint.className = 'text-success';
        }
        bookingInput.setCustomValidity('');
    }
}

// Validate ngày khởi hành
function validateDepartureDate() {
    const departureDate = document.getElementById('departure_date').value;
    const departureHint = document.getElementById('departure-date-hint');
    const departureInput = document.getElementById('departure_date');
    
    if (!departureDate) {
        if (departureHint) {
            departureHint.textContent = 'Vui lòng chọn ngày khởi hành trong tương lai';
            departureHint.className = 'text-muted';
        }
        departureInput.setCustomValidity('');
        return;
    }
    
    const selectedDate = new Date(departureDate);
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);
    
    if (selectedDate < now) {
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
}

// Tính giá
function calculatePrice() {
    const tourId = document.getElementById('tour_id').value;
    const numAdults = parseInt(document.getElementById('num_adults').value) || 0;
    const numChildren = parseInt(document.getElementById('num_children').value) || 0;
    const numInfants = parseInt(document.getElementById('num_infants').value) || 0;
    const numPeople = numAdults + numChildren + numInfants;
    
    document.getElementById('num_people').value = numPeople;
    
    if (!tourId) {
        document.getElementById('priceDetails').innerHTML = '<p class="text-muted">Vui lòng chọn tour</p>';
        return;
    }
    
    const departureDate = document.getElementById('departure_date').value;
    
    fetch('index.php?act=booking-calculate-price&tour_id=' + tourId + 
          '&num_adults=' + numAdults + '&num_children=' + numChildren + 
          '&num_infants=' + numInfants + '&departure_date=' + departureDate)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `
                    <table class="table table-sm">
                        <tr><td>Người lớn (${data.num_adults} x ${formatNumber(data.adult_price)} đ)</td>
                            <td class="text-end">${formatNumber(data.adult_price * data.num_adults)} đ</td></tr>
                        <tr><td>Trẻ em (${data.num_children} x ${formatNumber(data.child_price)} đ)</td>
                            <td class="text-end">${formatNumber(data.child_price * data.num_children)} đ</td></tr>
                        <tr><td>Trẻ sơ sinh (${data.num_infants} x ${formatNumber(data.infant_price)} đ)</td>
                            <td class="text-end">${formatNumber(data.infant_price * data.num_infants)} đ</td></tr>
                        <tr class="table-primary"><td><strong>Tổng cộng</strong></td>
                            <td class="text-end"><strong>${formatNumber(data.total)} đ</strong></td></tr>
                    </table>
                `;
                document.getElementById('priceDetails').innerHTML = html;
            }
        });
}

function formatNumber(num) {
    return new Intl.NumberFormat('vi-VN').format(num);
}

// Thêm khách vào danh sách (cho đoàn)
let guestCount = 0;
function addGuest() {
    guestCount++;
    const container = document.getElementById('guestsContainer');
    const guestDiv = document.createElement('div');
    guestDiv.className = 'guest-item';
    guestDiv.id = 'guest_' + guestCount;
    guestDiv.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Loại khách</label>
                <select name="guests[${guestCount}][guest_type]" class="form-select">
                    <option value="adult">Người lớn</option>
                    <option value="child">Trẻ em</option>
                    <option value="infant">Trẻ sơ sinh</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Họ tên</label>
                <input type="text" name="guests[${guestCount}][full_name]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="guests[${guestCount}][date_of_birth]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">CMND/CCCD</label>
                <input type="text" name="guests[${guestCount}][id_card]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeGuest(${guestCount})">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </div>
        </div>
    `;
    container.appendChild(guestDiv);
}

function removeGuest(id) {
    document.getElementById('guest_' + id).remove();
}

// Tính giá khi load trang
calculatePrice();
</script>
</body>
</html>

