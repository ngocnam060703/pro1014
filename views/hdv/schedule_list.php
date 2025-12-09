<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lịch làm việc HDV</title>
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
.sidebar a { 
  color: #ecf0f1; 
  padding: 15px 20px; 
  display: block; 
  text-decoration: none; 
  transition: all 0.3s;
  border-left: 3px solid transparent;
}
.sidebar a:hover { 
  background: rgba(255,255,255,0.1); 
  color: #fff; 
  border-left: 3px solid #3498db;
  transform: translateX(5px);
}
.sidebar a.active {
  background: rgba(52, 152, 219, 0.2);
  border-left: 3px solid #3498db;
  color: #fff;
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
.filter-section { 
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 25px; 
  border-radius: 15px; 
  margin-bottom: 25px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.schedule-card {
  background: #fff;
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s;
  border-left: 5px solid #667eea;
}
.schedule-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.schedule-card.new-assignment {
  border-left-color: #28a745;
  background: linear-gradient(to right, #f0fff4 0%, #fff 20%);
}
.schedule-card h5 {
  color: #667eea;
  margin-bottom: 15px;
}
.badge-modern {
  padding: 8px 15px;
  border-radius: 20px;
  font-weight: 500;
  font-size: 0.85rem;
}
.badge-scheduled { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-in_progress { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); color: #fff; }
.badge-completed { background: linear-gradient(135deg, #198754 0%, #20c997 100%); color: #fff; }
.badge-paused { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: #fff; }
.info-item {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  color: #555;
}
.info-item i {
  width: 25px;
  color: #667eea;
  margin-right: 10px;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.fade-in {
  animation: fadeIn 0.6s ease-out;
}
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #6c757d;
}
.empty-state i {
  font-size: 5rem;
  opacity: 0.3;
  margin-bottom: 20px;
}
</style>
</head>
<body>
<div class="row g-0">
  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4 fw-bold">HDV</h4>
    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_schedule_list" class="active"><i class="bi bi-calendar-event"></i> Lịch làm việc</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_profile"><i class="bi bi-person-circle"></i> Hồ sơ</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-calendar-event"></i> Lịch làm việc của tôi</h3>
          <p class="text-muted mb-0">Tổng số: <strong><?= count($schedules ?? []) ?></strong> tour được phân công</p>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-primary" onclick="location.reload()" title="Làm mới danh sách">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
          </button>
          <button type="button" class="btn btn-outline-secondary" id="autoRefreshBtn" onclick="toggleAutoRefresh()" title="Tự động làm mới mỗi 30 giây">
            <i class="bi bi-pause-circle"></i> Bật tự động làm mới
          </button>
        </div>
      </div>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <i class="bi bi-check-circle"></i> <?= $_SESSION['message']; unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Bộ lọc -->
      <div class="filter-section fade-in">
        <h5 class="mb-3"><i class="bi bi-funnel"></i> Bộ lọc</h5>
        <form method="GET" action="index.php" class="row g-3">
          <input type="hidden" name="act" value="hdv_schedule_list">
          
          <div class="col-md-3">
            <label class="form-label fw-semibold">Tìm kiếm theo tên tour</label>
            <input type="text" name="search" class="form-control" placeholder="Nhập tên tour..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Lọc theo tháng</label>
            <input type="month" name="month" class="form-control" value="<?= htmlspecialchars($_GET['month'] ?? '') ?>">
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Lọc theo ngày</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Lọc theo trạng thái</label>
            <select name="status" class="form-select">
              <option value="">-- Tất cả --</option>
              <option value="scheduled" <?= (isset($_GET['status']) && $_GET['status'] == 'scheduled') ? 'selected' : '' ?>>Sắp đi</option>
              <option value="in_progress" <?= (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'selected' : '' ?>>Đang chạy</option>
              <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>Đã kết thúc</option>
              <option value="paused" <?= (isset($_GET['status']) && $_GET['status'] == 'paused') ? 'selected' : '' ?>>Tạm hoãn</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">&nbsp;</label>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Lọc</button>
              <a href="index.php?act=hdv_schedule_list" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
          </div>
        </form>
      </div>

      <!-- Danh sách lịch trình -->
      <?php if(empty($schedules)): ?>
        <div class="empty-state fade-in">
          <i class="bi bi-calendar-x"></i>
          <h4 class="mt-3">Chưa có lịch làm việc nào được phân công</h4>
          <p class="text-muted">Vui lòng liên hệ admin để được phân công tour.</p>
          <button type="button" class="btn btn-primary mt-3" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
          </button>
        </div>
      <?php else: ?>
        <div class="row g-3 fade-in">
          <?php foreach($schedules as $i => $sch): ?>
            <?php
            // Kiểm tra xem assignment có mới không (tạo trong 24h qua)
            $isNew = false;
            if (!empty($sch['assigned_at'])) {
                $assignedTime = strtotime($sch['assigned_at']);
                $hoursAgo = (time() - $assignedTime) / 3600;
                $isNew = $hoursAgo <= 24;
            }
            
            // Tính toán trạng thái
            $status = $sch['status'] ?? 'scheduled';
            $statusBadge = [
              'scheduled' => 'badge-scheduled',
              'in_progress' => 'badge-in_progress',
              'completed' => 'badge-completed',
              'paused' => 'badge-paused'
            ][$status] ?? 'badge-scheduled';
            $statusText = [
              'scheduled' => 'Sắp đi',
              'in_progress' => 'Đang chạy',
              'completed' => 'Đã kết thúc',
              'paused' => 'Tạm hoãn'
            ][$status] ?? $status;
            
            // Tính số ngày
            $durationDays = $sch['duration_days'] ?? 1;
            ?>
            <div class="col-md-6">
              <div class="schedule-card <?= $isNew ? 'new-assignment' : '' ?>">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div class="flex-grow-1">
                    <h5 class="mb-2">
                      <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($sch['tour_name'] ?? 'Chưa có') ?>
                      <?php if($isNew): ?>
                        <span class="badge bg-success badge-modern ms-2">Mới</span>
                      <?php endif; ?>
                    </h5>
                    <?php if(!empty($sch['tour_code'])): ?>
                      <p class="text-muted mb-2">
                        <i class="bi bi-tag"></i> Mã tour: <strong><?= htmlspecialchars($sch['tour_code']) ?></strong>
                      </p>
                    <?php endif; ?>
                  </div>
                  <span class="badge <?= $statusBadge ?> badge-modern"><?= $statusText ?></span>
                </div>

                <div class="info-item">
                  <i class="bi bi-calendar-event"></i>
                  <div>
                    <strong>Khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($sch['departure_time'] ?? '')) ?>
                    <?php if(!empty($sch['end_date'])): ?>
                      <br><strong>Kết thúc:</strong> <?= date('d/m/Y', strtotime($sch['end_date'])) ?>
                      <?php if(!empty($sch['end_time'])): ?>
                        <?= date('H:i', strtotime($sch['end_time'])) ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="info-item">
                  <i class="bi bi-clock-history"></i>
                  <div>
                    <strong>Thời lượng:</strong> 
                    <?= $durationDays ?> ngày
                    <?php if($durationDays > 1): ?>
                      / <?= $durationDays - 1 ?> đêm
                    <?php endif; ?>
                  </div>
                </div>

                <?php if(!empty($sch['meeting_point'])): ?>
                <div class="info-item">
                  <i class="bi bi-geo-alt"></i>
                  <div><strong>Điểm tập trung:</strong> <?= htmlspecialchars($sch['meeting_point']) ?></div>
                </div>
                <?php endif; ?>

                <?php if(isset($sch['booked_guests'])): ?>
                <div class="info-item">
                  <i class="bi bi-people"></i>
                  <div><strong>Số khách:</strong> <?= $sch['booked_guests'] ?> khách</div>
                </div>
                <?php endif; ?>

                <?php if(!empty($sch['note'])): ?>
                <div class="info-item">
                  <i class="bi bi-sticky"></i>
                  <div>
                    <strong>Ghi chú:</strong> 
                    <span class="text-muted"><?= htmlspecialchars(substr($sch['note'], 0, 100)) ?><?= strlen($sch['note']) > 100 ? '...' : '' ?></span>
                  </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($sch['assigned_at'])): ?>
                <div class="info-item">
                  <i class="bi bi-person-check"></i>
                  <div>
                    <small class="text-muted">Phân công: <?= date('d/m/Y H:i', strtotime($sch['assigned_at'])) ?></small>
                  </div>
                </div>
                <?php endif; ?>

                <div class="d-flex gap-2 mt-3">
                  <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $sch['departure_id'] ?>" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-eye"></i> Xem chi tiết
                  </a>
                  <?php if($status == 'scheduled' || $status == 'in_progress'): ?>
                    <a href="index.php?act=hdv_journal_create&departure_id=<?= $sch['departure_id'] ?>" class="btn btn-success">
                      <i class="bi bi-journal-plus"></i> Ghi nhật ký
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-refresh mỗi 30 giây để cập nhật lịch làm việc mới
let autoRefreshInterval = null;

// Bật/tắt auto-refresh
function toggleAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
        document.getElementById('autoRefreshBtn').innerHTML = '<i class="bi bi-pause-circle"></i> Bật tự động làm mới';
        document.getElementById('autoRefreshBtn').classList.remove('btn-success');
        document.getElementById('autoRefreshBtn').classList.add('btn-outline-secondary');
    } else {
        autoRefreshInterval = setInterval(() => {
            location.reload();
        }, 30000); // Refresh mỗi 30 giây
        document.getElementById('autoRefreshBtn').innerHTML = '<i class="bi bi-play-circle"></i> Tắt tự động làm mới';
        document.getElementById('autoRefreshBtn').classList.remove('btn-outline-secondary');
        document.getElementById('autoRefreshBtn').classList.add('btn-success');
    }
}

// Thông báo khi có assignment mới
document.addEventListener('DOMContentLoaded', function() {
    const newAssignments = document.querySelectorAll('.new-assignment');
    if (newAssignments.length > 0) {
        console.log('Có <?= count(array_filter($schedules ?? [], function($sch) { return !empty($sch['assigned_at']) && (time() - strtotime($sch['assigned_at'])) / 3600 <= 24; })) ?? 0 ?> phân công mới!');
    }
});
</script>
</body>
</html>
