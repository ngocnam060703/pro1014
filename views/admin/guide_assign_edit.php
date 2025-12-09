<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Khi sửa, gán mặc định để tránh lỗi
$assign = $assign ?? [
    'id'=>'', 'guide_id'=>'', 'tour_id'=>'', 'departure_id'=>'',
    'departure_date'=>'', 'meeting_point'=>'', 'max_people'=>'', 'note'=>'', 'reason'=>'', 'status'=>'scheduled'
];

// Kiểm tra business rules
$isTourCompleted = false;
$isAssignmentCompleted = false;
if (!empty($assign['departure_id'])) {
    require_once "models/GuideAssignModel.php";
    $assignModel = new GuideAssignModel();
    $isTourCompleted = $assignModel->isTourCompleted($assign['departure_id']);
    $isAssignmentCompleted = $assignModel->isAssignmentCompleted($assign['id'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa phân công HDV</title>
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
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 15px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
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
    <a href="index.php?act=guide-assign" class="active"><i class="bi bi-card-list"></i> Phân công HDV</a>
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
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-pencil-square"></i> Sửa phân công HDV</h3>
              <p class="text-muted mb-0">Cập nhật thông tin phân công</p>
          </div>
          <a href="index.php?act=guide-assign" class="btn btn-secondary btn-modern">
              <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
          </a>
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

      <form action="index.php?act=guide-assign-update" method="POST" id="assignForm">
        <input type="hidden" name="id" value="<?= $assign['id'] ?>">

        <div class="row g-3">
          <div class="col-md-6 form-section">
            <label class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
            <select name="guide_id" id="guide_id" class="form-select" required onchange="checkScheduleConflict()" <?= ($isTourCompleted || $isAssignmentCompleted) ? 'disabled' : '' ?>>
              <option value="">-- Chọn HDV --</option>
              <?php foreach($guides as $g): ?>
                <option value="<?= $g['id'] ?>" <?= $assign['guide_id']==$g['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($g['fullname']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if($isTourCompleted || $isAssignmentCompleted): ?>
              <input type="hidden" name="guide_id" value="<?= $assign['guide_id'] ?>">
              <div class="alert alert-warning mt-2">
                <i class="bi bi-exclamation-triangle"></i> 
                <?php if($isTourCompleted): ?>
                  Không thể đổi HDV vì tour đã kết thúc.
                <?php elseif($isAssignmentCompleted): ?>
                  Không thể đổi HDV vì phân công đã kết thúc.
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <small class="form-text text-muted">
              <span id="guide-conflict-hint"></span>
            </small>
          </div>

          <div class="col-md-6 form-section">
            <label class="form-label">Tour</label>
            <select name="tour_id" class="form-select" required>
              <option value="">-- Chọn Tour --</option>
              <?php foreach($tours as $t): ?>
                <option value="<?= $t['id'] ?>" <?= $assign['tour_id']==$t['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($t['title']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6 form-section">
            <label class="form-label">Lịch khởi hành <span class="text-danger">*</span></label>
            <select name="departure_id" id="departure_id" class="form-select" required onchange="checkScheduleConflict()">
              <option value="">-- Chọn lịch --</option>
              <?php foreach($departures as $d): ?>
                <option value="<?= $d['id'] ?>" 
                        data-departure-time="<?= $d['departure_time'] ?>"
                        data-end-date="<?= $d['end_date'] ?? '' ?>"
                        <?= $assign['departure_id']==$d['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($d['departure_time'].' | '.$d['meeting_point']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">
              <span id="departure-conflict-hint"></span>
            </small>
          </div>

          <div class="col-md-6 form-section">
            <label class="form-label">Ngày khởi hành</label>
            <input type="date" name="departure_date" class="form-control" value="<?= $assign['departure_date'] ?>" required>
          </div>

          <div class="col-md-6 form-section">
            <label class="form-label">Điểm tập trung</label>
            <input type="text" name="meeting_point" class="form-control" value="<?= htmlspecialchars($assign['meeting_point']) ?>">
          </div>

          <div class="col-md-6 form-section">
            <label class="form-label">Số khách tối đa</label>
            <input type="number" name="max_people" class="form-control" min="1" value="<?= $assign['max_people'] ?>" required>
          </div>

          <div class="col-12 form-section">
            <label class="form-label">Ghi chú</label>
            <textarea name="note" class="form-control" rows="3"><?= htmlspecialchars($assign['note'] ?? '') ?></textarea>
          </div>

          <div class="col-12 form-section">
            <label class="form-label">Lý do phân công</label>
            <textarea name="reason" class="form-control" rows="2" placeholder="Nhập lý do phân công (nếu có)"><?= htmlspecialchars($assign['reason'] ?? '') ?></textarea>
          </div>

          <div class="col-md-6 form-section">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select" required>
              <option value="scheduled" <?= ($assign['status'] ?? '')=='scheduled' ? 'selected' : '' ?>>Chưa bắt đầu</option>
              <option value="in_progress" <?= ($assign['status'] ?? '')=='in_progress' ? 'selected' : '' ?>>Đang chạy</option>
              <option value="paused" <?= ($assign['status'] ?? '')=='paused' ? 'selected' : '' ?>>Tạm dừng</option>
              <option value="completed" <?= ($assign['status'] ?? '')=='completed' ? 'selected' : '' ?>>Đã kết thúc</option>
            </select>
          </div>

          <div class="col-12 d-flex justify-content-between mt-3">
            <a href="index.php?act=guide-assign" class="btn btn-secondary btn-modern"><i class="bi bi-arrow-left"></i> Quay lại</a>
            <button type="submit" class="btn btn-primary btn-modern"><i class="bi bi-save"></i> Cập nhật phân công</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function checkScheduleConflict() {
    const guideId = document.getElementById('guide_id').value;
    const departureId = document.getElementById('departure_id').value;
    const assignId = <?= $assign['id'] ?? 0 ?>;
    
    if (!guideId || !departureId) {
        document.getElementById('guide-conflict-hint').innerHTML = '';
        document.getElementById('departure-conflict-hint').innerHTML = '';
        return;
    }
    
    const url = `index.php?act=guide-assign-check-conflict&guide_id=${guideId}&departure_id=${departureId}&exclude_id=${assignId}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.has_conflict) {
                document.getElementById('guide-conflict-hint').innerHTML = 
                    '<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> ' + data.message + '</span>';
                document.getElementById('departure-conflict-hint').innerHTML = 
                    '<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> ' + data.message + '</span>';
            } else {
                document.getElementById('guide-conflict-hint').innerHTML = 
                    '<span class="text-success"><i class="bi bi-check-circle"></i> HDV có thể phân công</span>';
                document.getElementById('departure-conflict-hint').innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Kiểm tra khi form submit
document.getElementById('assignForm').addEventListener('submit', function(e) {
    const guideId = document.getElementById('guide_id').value;
    const departureId = document.getElementById('departure_id').value;
    
    if (!guideId || !departureId) {
        e.preventDefault();
        alert('Vui lòng chọn đầy đủ thông tin!');
        return false;
    }
    
    const conflictHint = document.getElementById('guide-conflict-hint').innerHTML;
    if (conflictHint.includes('exclamation-triangle')) {
        e.preventDefault();
        alert('Không thể lưu vì có xung đột lịch trình!');
        return false;
    }
});
</script>
</body>
</html>
