<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sửa Lịch - <?= $tour['title'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Sửa Lịch Khởi Hành cho Tour: <?= htmlspecialchars($tour['title']) ?></h3>
    <form action="index.php?act=lichUpdate" method="POST">
        <input type="hidden" name="id" value="<?= $lich['id'] ?>">
        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

        <div class="mb-3">
            <label for="departure_time" class="form-label">Ngày khởi hành <span class="text-danger">*</span></label>
            <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" 
                   value="<?= str_replace(' ', 'T', $lich['departure_time']) ?>" 
                   min="<?= date('Y-m-d\TH:i') ?>" required
                   onchange="validateDepartureDate()">
            <small class="form-text text-muted">
              <span id="departure-date-hint">Vui lòng chọn ngày khởi hành trong tương lai</span>
            </small>
        </div>

        <div class="mb-3">
            <label for="meeting_point" class="form-label">Điểm gặp</label>
            <input type="text" name="meeting_point" id="meeting_point" class="form-control" value="<?= htmlspecialchars($lich['meeting_point']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="seats_available" class="form-label">Số chỗ <span class="text-muted">(7-50 chỗ)</span></label>
            <input type="number" name="seats_available" id="seats_available" class="form-control" 
                   value="<?= $lich['seats_available'] ?>" 
                   min="7" max="50" required 
                   onchange="updateSeatsHint(this.value)">
            <small class="form-text text-muted">
              <span id="seats-hint">Gợi ý: Xe 7 chỗ, 16 chỗ, 29 chỗ, 35 chỗ, 45 chỗ, 50 chỗ</span>
            </small>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea name="notes" id="notes" class="form-control"><?= htmlspecialchars($lich['notes']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật Lịch</button>
        <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
}
</script>
</body>
</html>
