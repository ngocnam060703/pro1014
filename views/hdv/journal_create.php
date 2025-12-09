<?php 
if (session_status() == PHP_SESSION_NONE) session_start();

$guideId = $_SESSION['guide']['id'] ?? 0;
$departureId = $_GET['departure_id'] ?? 0;

// Lấy thông tin từ controller
$departure = $departure ?? null;
$tour = $tour ?? null;
$assignment = $assignment ?? null;

if (!$departure || !$tour) {
    header("Location: index.php?act=hdv_schedule_list");
    exit;
}

$departureDate = date('Y-m-d', strtotime($departure['departure_time']));
$endDate = !empty($departure['end_date']) ? $departure['end_date'] : $departureDate;
$today = date('Y-m-d');
$maxDate = min($today, $endDate);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ghi nhật ký tour</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; position: fixed; }
.sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
.sidebar a:hover { background: #495057; color: #fff; border-left: 3px solid #0d6efd; }
.content { padding: 30px; margin-left: 16.666667%; }
.card-container { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); margin-bottom: 20px; }
.required { color: red; }
.incident-section { display: none; background: #fff3cd; padding: 15px; border-radius: 10px; margin-top: 15px; }
.photo-preview { max-width: 150px; max-height: 150px; margin: 5px; border-radius: 5px; }
</style>
</head>
<body>
<div class="row g-0">
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">HDV</h4>
    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_schedule_list"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="card-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-primary"><i class="bi bi-journal-plus"></i> Ghi nhật ký tour</h3>
        <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $departureId ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <!-- Thông tin tour -->
      <div class="alert alert-info mb-4">
        <h5><i class="bi bi-info-circle"></i> Thông tin tour</h5>
        <p class="mb-1"><strong>Tên tour:</strong> <?= htmlspecialchars($tour['title']) ?></p>
        <p class="mb-1"><strong>Ngày khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($departure['departure_time'])) ?></p>
        <p class="mb-0"><strong>Ngày kết thúc:</strong> <?= !empty($departure['end_date']) ? date('d/m/Y', strtotime($departure['end_date'])) : 'N/A' ?></p>
      </div>

      <form method="POST" action="index.php?act=hdv_journal_store" enctype="multipart/form-data" id="journalForm">
        <input type="hidden" name="departure_id" value="<?= $departureId ?>">
        
        <!-- 1. Chọn ngày thực hiện -->
        <div class="mb-4">
          <label for="journal_date" class="form-label">
            <i class="bi bi-calendar3"></i> Ngày thực hiện <span class="required">*</span>
          </label>
          <input type="date" class="form-control" id="journal_date" name="journal_date" 
                 value="<?= $today ?>" 
                 max="<?= $maxDate ?>" 
                 min="<?= $departureDate ?>" 
                 required>
          <small class="text-muted">
            Mặc định: ngày hiện tại. Không được chọn ngày tương lai hoặc ngoài khoảng ngày khởi hành - kết thúc.
          </small>
          <div class="invalid-feedback" id="dateError"></div>
        </div>

        <!-- 2. Nội dung nhật ký -->
        <div class="mb-4">
          <h5 class="text-primary mb-3"><i class="bi bi-file-text"></i> Nội dung nhật ký <span class="required">*</span></h5>
          
          <div class="mb-3">
            <label for="activities" class="form-label">Hoạt động đã thực hiện</label>
            <textarea class="form-control" id="activities" name="activities" rows="4" 
                      placeholder="Mô tả các hoạt động đã thực hiện trong ngày..."></textarea>
          </div>

          <div class="mb-3">
            <label for="completed_attractions" class="form-label">Các điểm tham quan hoàn thành</label>
            <textarea class="form-control" id="completed_attractions" name="completed_attractions" rows="3" 
                      placeholder="Liệt kê các điểm tham quan đã hoàn thành..."></textarea>
          </div>

          <div class="mb-3">
            <label for="travel_time" class="form-label">Thời gian di chuyển</label>
            <input type="text" class="form-control" id="travel_time" name="travel_time" 
                   placeholder="VD: 8:00 - 10:00, 14:00 - 16:00">
          </div>

          <div class="mb-3">
            <label for="customer_status" class="form-label">Tình trạng khách</label>
            <textarea class="form-control" id="customer_status" name="customer_status" rows="3" 
                      placeholder="Mô tả tình trạng sức khỏe, tinh thần của khách..."></textarea>
          </div>

          <div class="mb-3">
            <label for="important_notes" class="form-label">Ghi chú quan trọng</label>
            <textarea class="form-control" id="important_notes" name="important_notes" rows="3" 
                      placeholder="Các ghi chú quan trọng cần lưu ý..."></textarea>
          </div>

          <div class="mb-3">
            <label for="note" class="form-label">Ghi chú khác (tùy chọn)</label>
            <textarea class="form-control" id="note" name="note" rows="2" 
                      placeholder="Ghi chú bổ sung..."></textarea>
          </div>
        </div>

        <!-- 3. Báo cáo sự cố -->
        <div class="mb-4">
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="has_incident" name="has_incident" value="1">
            <label class="form-check-label" for="has_incident">
              <strong><i class="bi bi-exclamation-triangle text-warning"></i> Có sự cố phát sinh</strong>
            </label>
          </div>

          <div class="incident-section" id="incidentSection">
            <h6 class="text-warning mb-3"><i class="bi bi-exclamation-circle"></i> Thông tin sự cố</h6>
            
            <div class="mb-3">
              <label for="incident_time" class="form-label">Thời điểm sự cố</label>
              <input type="datetime-local" class="form-control" id="incident_time" name="incident_time">
            </div>

            <div class="mb-3">
              <label for="incident_description" class="form-label">Mô tả chi tiết <span class="required">*</span></label>
              <textarea class="form-control" id="incident_description" name="incident_description" rows="4" 
                        placeholder="Mô tả chi tiết về sự cố..."></textarea>
            </div>

            <div class="mb-3">
              <label for="affected_customers" class="form-label">Khách bị ảnh hưởng</label>
              <textarea class="form-control" id="affected_customers" name="affected_customers" rows="2" 
                        placeholder="Danh sách khách bị ảnh hưởng..."></textarea>
            </div>

            <div class="mb-3">
              <label for="incident_solution" class="form-label">Cách xử lý</label>
              <textarea class="form-control" id="incident_solution" name="incident_solution" rows="3" 
                        placeholder="Mô tả cách xử lý sự cố..."></textarea>
            </div>

            <div class="mb-3">
              <label for="incident_severity" class="form-label">Mức độ <span class="required">*</span></label>
              <select class="form-select" id="incident_severity" name="incident_severity" required>
                <option value="low">Nhẹ</option>
                <option value="medium" selected>Trung bình</option>
                <option value="high">Nghiêm trọng</option>
              </select>
            </div>
          </div>
        </div>

        <!-- 4. Upload hình ảnh -->
        <div class="mb-4">
          <h5 class="text-primary mb-3"><i class="bi bi-images"></i> Upload hình ảnh/video (tùy chọn)</h5>
          <div class="mb-3">
            <input type="file" class="form-control" id="photos" name="photos[]" 
                   accept="image/*,video/*" multiple>
            <small class="text-muted">
              Tối đa 10 file, mỗi file ≤ 5MB. Hỗ trợ: JPG, PNG, GIF, MP4, MOV, AVI
            </small>
          </div>
          <div id="photoPreview" class="mt-3"></div>
        </div>

        <!-- Nút gửi -->
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-send"></i> Gửi nhật ký
          </button>
          <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $departureId ?>" class="btn btn-secondary btn-lg">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Hiển thị/ẩn form sự cố
document.getElementById('has_incident').addEventListener('change', function() {
    const section = document.getElementById('incidentSection');
    section.style.display = this.checked ? 'block' : 'none';
    if (!this.checked) {
        // Reset form sự cố
        document.getElementById('incident_description').value = '';
        document.getElementById('incident_severity').value = 'medium';
    }
});

// Validate ngày
const journalDate = document.getElementById('journal_date');
const departureDate = '<?= $departureDate ?>';
const endDate = '<?= $endDate ?>';
const today = '<?= $today ?>';

journalDate.addEventListener('change', function() {
    const selectedDate = this.value;
    const dateError = document.getElementById('dateError');
    
    if (selectedDate > today) {
        this.setCustomValidity('Ngày ghi nhật ký không hợp lệ.');
        dateError.textContent = 'Không được chọn ngày tương lai.';
        this.classList.add('is-invalid');
    } else if (selectedDate < departureDate || selectedDate > endDate) {
        this.setCustomValidity('Ngày ghi nhật ký không hợp lệ.');
        dateError.textContent = 'Ngày phải trong khoảng từ ngày khởi hành đến ngày kết thúc.';
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        dateError.textContent = '';
        this.classList.remove('is-invalid');
    }
});

// Validate nội dung nhật ký
const form = document.getElementById('journalForm');
form.addEventListener('submit', function(e) {
    const activities = document.getElementById('activities').value.trim();
    const completedAttractions = document.getElementById('completed_attractions').value.trim();
    const travelTime = document.getElementById('travel_time').value.trim();
    const customerStatus = document.getElementById('customer_status').value.trim();
    const importantNotes = document.getElementById('important_notes').value.trim();
    
    if (!activities && !completedAttractions && !travelTime && !customerStatus && !importantNotes) {
        e.preventDefault();
        alert('Vui lòng nhập nội dung nhật ký.');
        return false;
    }
    
    // Validate sự cố nếu có
    if (document.getElementById('has_incident').checked) {
        const incidentDesc = document.getElementById('incident_description').value.trim();
        if (!incidentDesc) {
            e.preventDefault();
            alert('Vui lòng nhập mô tả chi tiết về sự cố.');
            return false;
        }
    }
});

// Preview ảnh
const photoInput = document.getElementById('photos');
const photoPreview = document.getElementById('photoPreview');
const maxFiles = 10;
const maxSize = 5 * 1024 * 1024; // 5MB

photoInput.addEventListener('change', function() {
    photoPreview.innerHTML = '';
    const files = this.files;
    
    if (files.length > maxFiles) {
        alert(`Chỉ được upload tối đa ${maxFiles} file.`);
        this.value = '';
        return;
    }
    
    Array.from(files).forEach((file, index) => {
        if (file.size > maxSize) {
            alert(`File "${file.name}" vượt quá 5MB.`);
            return;
        }
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'photo-preview';
                img.style.objectFit = 'cover';
                photoPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            const div = document.createElement('div');
            div.className = 'alert alert-info d-inline-block m-2';
            div.innerHTML = `<i class="bi bi-film"></i> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            photoPreview.appendChild(div);
        }
    });
});
</script>
</body>
</html>


