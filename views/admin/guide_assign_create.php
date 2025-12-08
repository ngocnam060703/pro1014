<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Khi thêm mới, gán mặc định mảng rỗng để tránh lỗi
$guides = $guides ?? [];
$tours = $tours ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm phân công HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: linear-gradient(to right, #fbc2eb, #a6c1ee); font-family: 'Segoe UI', sans-serif; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; }
.sidebar h4 { font-weight: 700; }
.sidebar a { color: #ccc; padding: 12px; display: block; text-decoration: none; border-left: 3px solid transparent; }
.sidebar a:hover { background: #495057; color: #fff; border-left: 3px solid #ff6a00; }
.content { padding: 30px; }
.card { border-radius: 18px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.btn-primary { background: linear-gradient(45deg,#ff6a00,#ee0979); border: none; }
.btn-primary:hover { background: linear-gradient(45deg,#ee0979,#ff6a00); }
.btn-secondary { background: #6c757d; border: none; }
.step-section { padding: 20px; margin-bottom: 20px; border-radius: 12px; background: linear-gradient(to right, #fdfbfb, #ebedee); box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-left: 4px solid #0d6efd; }
.step-header { font-weight: 700; color: #0d6efd; margin-bottom: 15px; font-size: 18px; }
.info-box { background: #fff; padding: 15px; border-radius: 8px; margin-top: 10px; border: 1px solid #dee2e6; }
.info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
.info-row:last-child { border-bottom: none; }
.info-label { font-weight: 600; color: #495057; }
.info-value { color: #212529; }
.badge-status { padding: 5px 10px; border-radius: 5px; font-size: 12px; }
.badge-open { background: #198754; color: #fff; }
.badge-upcoming { background: #0dcaf0; color: #000; }
.badge-in_progress { background: #0d6efd; color: #fff; }
.badge-completed { background: #6c757d; color: #fff; }
.schedule-item { background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 10px; border-left: 3px solid #0d6efd; }
.alert-warning-custom { background: #fff3cd; border: 1px solid #ffc107; color: #856404; }
.alert-danger-custom { background: #f8d7da; border: 1px solid #dc3545; color: #721c24; }
.alert-success-custom { background: #d1e7dd; border: 1px solid #198754; color: #0f5132; }
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
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign" style="color:#fff; background:#495057; border-left:3px solid #ff6a00;">
      <i class="bi bi-card-list"></i> Phân công HDV
    </a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-plus-circle"></i> Thêm phân công HDV</h3>
        <a href="index.php?act=guide-assign" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Quay lại danh sách</a>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="card p-4">
      <form action="index.php?act=guide-assign-store" method="POST" id="assignForm">

        <!-- BƯỚC 1: Chọn Tour -->
        <div class="step-section">
          <div class="step-header"><i class="bi bi-1-circle"></i> Bước 1: Chọn Tour</div>
          <div class="mb-3">
            <label class="form-label">Tour <span class="text-danger">*</span></label>
            <select name="tour_id" id="tour_id" class="form-select" required onchange="loadTourInfo()">
              <option value="">-- Chọn Tour --</option>
              <?php foreach($tours as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div id="tour-info-box" style="display: none;">
            <div class="info-box">
              <div class="info-row">
                <span class="info-label">Tên Tour:</span>
                <span class="info-value" id="tour-name">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Số ngày:</span>
                <span class="info-value" id="tour-days">-</span>
              </div>
            </div>
          </div>
        </div>

        <!-- BƯỚC 2: Chọn Lịch Khởi Hành -->
        <div class="step-section">
          <div class="step-header"><i class="bi bi-2-circle"></i> Bước 2: Chọn Lịch Khởi Hành</div>
          <div class="mb-3">
            <label class="form-label">Lịch khởi hành <span class="text-danger">*</span></label>
            <select name="departure_id" id="departure_id" class="form-select" required onchange="loadDepartureInfo()" disabled>
              <option value="">-- Vui lòng chọn tour trước --</option>
            </select>
            <div id="departure-loading" class="mt-2" style="display: none;">
              <small class="text-muted"><i class="bi bi-hourglass-split"></i> Đang tải lịch khởi hành...</small>
            </div>
          </div>

          <div id="departure-info-box" style="display: none;">
            <div class="info-box">
              <div class="info-row">
                <span class="info-label">Ngày khởi hành:</span>
                <span class="info-value" id="departure-start-date">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Ngày kết thúc:</span>
                <span class="info-value" id="departure-end-date">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Số chỗ:</span>
                <span class="info-value" id="departure-total-seats">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Số khách đã đặt:</span>
                <span class="info-value" id="departure-booked">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value" id="departure-status">-</span>
              </div>
            </div>
            <div id="departure-warning" class="alert alert-danger-custom mt-2" style="display: none;">
              <i class="bi bi-exclamation-triangle"></i> <span id="departure-warning-text"></span>
            </div>
          </div>
        </div>

        <!-- BƯỚC 3: Chọn HDV -->
        <div class="step-section">
          <div class="step-header"><i class="bi bi-3-circle"></i> Bước 3: Chọn HDV</div>
          <div class="mb-3">
            <label class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
            <select name="guide_id" id="guide_id" class="form-select" required onchange="loadGuideInfo()" disabled>
              <option value="">-- Vui lòng chọn lịch khởi hành trước --</option>
              <?php foreach($guides as $g): ?>
                <option value="<?= $g['id'] ?>" data-status="<?= $g['status'] ?? 'active' ?>">
                  <?= htmlspecialchars($g['fullname']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div id="guide-info-box" style="display: none;">
            <div class="info-box mb-3">
              <div class="info-row">
                <span class="info-label">Họ tên:</span>
                <span class="info-value" id="guide-name">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Số điện thoại:</span>
                <span class="info-value" id="guide-phone">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value" id="guide-email">-</span>
              </div>
              <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value" id="guide-status">-</span>
              </div>
            </div>

            <div id="guide-status-warning" class="alert alert-danger-custom mb-3" style="display: none;">
              <i class="bi bi-exclamation-triangle"></i> <span id="guide-status-warning-text"></span>
            </div>

            <div id="guide-schedule-box" style="display: none;">
              <h6 class="mb-2"><i class="bi bi-calendar3"></i> Lịch làm việc hiện tại:</h6>
              <div id="guide-schedule-list"></div>
            </div>

            <div id="guide-conflict-warning" class="alert alert-warning-custom mt-3" style="display: none;">
              <i class="bi bi-exclamation-triangle"></i> <span id="guide-conflict-text"></span>
            </div>
          </div>
        </div>

        <!-- BƯỚC 4: Ghi chú -->
        <div class="step-section">
          <div class="step-header"><i class="bi bi-4-circle"></i> Bước 4: Ghi chú phân công (Tùy chọn)</div>
          <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="note" class="form-control" rows="3" placeholder="Ví dụ: Tour đông khách nên cần HDV nhiều kinh nghiệm. HDV biết tiếng Anh."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Lý do phân công</label>
            <textarea name="reason" class="form-control" rows="2" placeholder="Nhập lý do phân công (nếu có)"></textarea>
          </div>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" name="departure_date" id="departure_date">
        <input type="hidden" name="meeting_point" id="meeting_point">
        <input type="hidden" name="max_people" id="max_people">
        <input type="hidden" name="status" value="scheduled">

        <!-- BƯỚC 5: Xác nhận -->
        <div class="d-flex justify-content-between mt-4">
          <a href="index.php?act=guide-assign" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
          <button type="submit" class="btn btn-primary" id="submit-btn" disabled><i class="bi bi-save"></i> Tạo phân công</button>
        </div>
      </form>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let selectedTourId = null;
let selectedDepartureId = null;
let selectedGuideId = null;
let canSubmit = false;

// Bước 1: Load thông tin tour
function loadTourInfo() {
    const tourId = document.getElementById('tour_id').value;
    selectedTourId = tourId;
    
    if (!tourId) {
        document.getElementById('tour-info-box').style.display = 'none';
        document.getElementById('departure_id').disabled = true;
        document.getElementById('departure_id').innerHTML = '<option value="">-- Vui lòng chọn tour trước --</option>';
        document.getElementById('departure-info-box').style.display = 'none';
        document.getElementById('guide_id').disabled = true;
        document.getElementById('guide-info-box').style.display = 'none';
        canSubmit = false;
        updateSubmitButton();
        return;
    }
    
    fetch(`index.php?act=guide-assign-get-tour-info&tour_id=${tourId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('tour-name').textContent = data.tour.title;
                document.getElementById('tour-days').textContent = data.tour.days + ' ngày';
                document.getElementById('tour-info-box').style.display = 'block';
                
                // Load departures
                const departureSelect = document.getElementById('departure_id');
                departureSelect.innerHTML = '<option value="">-- Chọn lịch khởi hành --</option>';
                
                if (data.departures && data.departures.length > 0) {
                    data.departures.forEach(dep => {
                        const option = document.createElement('option');
                        option.value = dep.id;
                        option.textContent = `${dep.departure_time} | ${dep.meeting_point || 'N/A'}`;
                        option.setAttribute('data-total-seats', dep.total_seats || dep.max_people || 0);
                        option.setAttribute('data-booked', dep.booked_guests || 0);
                        departureSelect.appendChild(option);
                    });
                    departureSelect.disabled = false;
                } else {
                    departureSelect.innerHTML = '<option value="">Không có lịch khởi hành chưa kết thúc</option>';
                    departureSelect.disabled = true;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin tour');
        });
}

// Bước 2: Load thông tin departure
function loadDepartureInfo() {
    const departureId = document.getElementById('departure_id').value;
    selectedDepartureId = departureId;
    
    if (!departureId) {
        document.getElementById('departure-info-box').style.display = 'none';
        document.getElementById('guide_id').disabled = true;
        document.getElementById('guide-info-box').style.display = 'none';
        canSubmit = false;
        updateSubmitButton();
        return;
    }
    
    const selectedOption = document.getElementById('departure_id').options[document.getElementById('departure_id').selectedIndex];
    const totalSeats = selectedOption.getAttribute('data-total-seats') || 0;
    const booked = selectedOption.getAttribute('data-booked') || 0;
    
    fetch(`index.php?act=guide-assign-get-departure-info&departure_id=${departureId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const dep = data.departure;
                
                // Format dates
                const startDate = new Date(dep.departure_time);
                const startDateStr = startDate.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
                
                let endDateStr = 'Tour 1 ngày';
                if (dep.end_date) {
                    const endDate = new Date(dep.end_date);
                    endDateStr = endDate.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
                }
                
                // Status badge
                let statusBadge = '';
                const status = dep.status || 'open';
                if (status === 'open') {
                    statusBadge = '<span class="badge-status badge-open">Mở bán</span>';
                } else if (status === 'upcoming') {
                    statusBadge = '<span class="badge-status badge-upcoming">Sắp đi</span>';
                } else if (status === 'in_progress') {
                    statusBadge = '<span class="badge-status badge-in_progress">Đang chạy</span>';
                } else if (status === 'completed') {
                    statusBadge = '<span class="badge-status badge-completed">Kết thúc</span>';
                }
                
                document.getElementById('departure-start-date').textContent = startDateStr;
                document.getElementById('departure-end-date').textContent = endDateStr;
                document.getElementById('departure-total-seats').textContent = totalSeats;
                document.getElementById('departure-booked').textContent = booked;
                document.getElementById('departure-status').innerHTML = statusBadge;
                
                // Set hidden fields
                document.getElementById('departure_date').value = startDateStr.split('/').reverse().join('-');
                document.getElementById('meeting_point').value = dep.meeting_point || '';
                document.getElementById('max_people').value = totalSeats;
                
                document.getElementById('departure-info-box').style.display = 'block';
                
                // Check if completed
                if (data.is_completed) {
                    document.getElementById('departure-warning').style.display = 'block';
                    document.getElementById('departure-warning-text').textContent = '⚠️ Không thể phân công vào lịch đã kết thúc!';
                    document.getElementById('guide_id').disabled = true;
                    canSubmit = false;
                } else {
                    document.getElementById('departure-warning').style.display = 'none';
                    document.getElementById('guide_id').disabled = false;
                }
                
                updateSubmitButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin lịch khởi hành');
        });
}

// Bước 3: Load thông tin HDV
function loadGuideInfo() {
    const guideId = document.getElementById('guide_id').value;
    selectedGuideId = guideId;
    
    if (!guideId) {
        document.getElementById('guide-info-box').style.display = 'none';
        canSubmit = false;
        updateSubmitButton();
        return;
    }
    
    fetch(`index.php?act=guide-assign-get-guide-info&guide_id=${guideId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const guide = data.guide;
                
                document.getElementById('guide-name').textContent = guide.fullname || '-';
                document.getElementById('guide-phone').textContent = guide.phone || '-';
                document.getElementById('guide-email').textContent = guide.email || '-';
                
                // Status
                let statusText = 'Hoạt động';
                if (guide.status === 'inactive' || guide.status === 'not_active') {
                    statusText = '<span class="text-danger">Không hoạt động</span>';
                } else if (guide.status === 'on_leave' || guide.status === 'leave') {
                    statusText = '<span class="text-warning">Nghỉ phép</span>';
                }
                document.getElementById('guide-status').innerHTML = statusText;
                
                document.getElementById('guide-info-box').style.display = 'block';
                
                // Check inactive status
                if (data.is_inactive) {
                    document.getElementById('guide-status-warning').style.display = 'block';
                    document.getElementById('guide-status-warning-text').textContent = data.status_message;
                    canSubmit = false;
                } else {
                    document.getElementById('guide-status-warning').style.display = 'none';
                }
                
                // Show schedule
                if (data.schedule && data.schedule.length > 0) {
                    let scheduleHtml = '';
                    data.schedule.forEach(item => {
                        const startDate = new Date(item.departure_time);
                        const startDateStr = startDate.toLocaleDateString('vi-VN');
                        scheduleHtml += `
                            <div class="schedule-item">
                                <strong>${item.tour_name}</strong><br>
                                <small>Ngày: ${startDateStr} | Trạng thái: ${item.assignment_status}</small>
                            </div>
                        `;
                    });
                    document.getElementById('guide-schedule-list').innerHTML = scheduleHtml;
                    document.getElementById('guide-schedule-box').style.display = 'block';
                } else {
                    document.getElementById('guide-schedule-box').style.display = 'none';
                }
                
                // Check conflict
                if (selectedDepartureId) {
                    checkScheduleConflict();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin HDV');
        });
}

// Kiểm tra trùng lịch
function checkScheduleConflict() {
    if (!selectedGuideId || !selectedDepartureId) {
        return;
    }
    
    fetch(`index.php?act=guide-assign-check-conflict&guide_id=${selectedGuideId}&departure_id=${selectedDepartureId}`)
        .then(response => response.json())
        .then(data => {
            if (data.has_conflict) {
                document.getElementById('guide-conflict-warning').style.display = 'block';
                document.getElementById('guide-conflict-text').textContent = data.message;
                canSubmit = false;
            } else {
                document.getElementById('guide-conflict-warning').style.display = 'none';
                if (!document.getElementById('guide-status-warning').style.display || 
                    document.getElementById('guide-status-warning').style.display === 'none') {
                    canSubmit = true;
                }
            }
            updateSubmitButton();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Update submit button
function updateSubmitButton() {
    const submitBtn = document.getElementById('submit-btn');
    if (canSubmit && selectedTourId && selectedDepartureId && selectedGuideId) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Form validation
document.getElementById('assignForm').addEventListener('submit', function(e) {
    if (!canSubmit) {
        e.preventDefault();
        alert('Vui lòng hoàn thành tất cả các bước và đảm bảo không có cảnh báo!');
        return false;
    }
    
    // Final conflict check
    if (selectedGuideId && selectedDepartureId) {
        fetch(`index.php?act=guide-assign-check-conflict&guide_id=${selectedGuideId}&departure_id=${selectedDepartureId}`)
            .then(response => response.json())
            .then(data => {
                if (data.has_conflict) {
                    e.preventDefault();
                    alert(data.message);
                    return false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});
</script>
</body>
</html>
