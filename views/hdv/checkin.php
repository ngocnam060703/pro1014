<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/GuideScheduleModel.php";
require_once __DIR__ . "/../../models/GuideCheckinModel.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$departure_id = $_GET['departure_id'] ?? 0;

$scheduleModel = new GuideScheduleModel();
$customers = $scheduleModel->getTourCustomers($departure_id);
$schedule = $scheduleModel->getScheduleDetail($guide_id, $departure_id);

$checkinModel = new GuideCheckinModel();
$checkins = $checkinModel->getByDeparture($departure_id);
$stats = $checkinModel->getCheckinStats($departure_id);

// Tạo mảng checkin theo booking_id để dễ tra cứu
$checkinMap = [];
foreach($checkins as $checkin) {
    $checkinMap[$checkin['booking_id']] = $checkin;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Check-in khách hàng</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#343a40; padding-top:20px; position:fixed; }
.sidebar a{ color:#ddd; padding:12px; display:block; text-decoration:none; }
.sidebar a:hover{ background:#495057; color:#fff; border-left:3px solid #0d6efd; }
.content{ padding:30px; margin-left:16.666667%; }
.card-container{ background:#fff; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,0.1); margin-bottom:20px; }
</style>
</head>
<body>
<div class="row g-0">
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">HDV</h4>
    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_schedule_list"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    <a href="index.php?act=hdv_customers&departure_id=<?= $departure_id ?>"><i class="bi bi-people"></i> Danh sách khách</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="card-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-success"><i class="bi bi-check-circle"></i> Check-in / Điểm danh khách hàng</h3>
        <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $departure_id ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>

      <?php if($schedule): ?>
      <div class="alert alert-info mb-4">
        <strong>Tour:</strong> <?= htmlspecialchars($schedule['tour_name']) ?> | 
        <strong>Ngày khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($schedule['departure_time'])) ?> |
        <strong>Điểm tập trung:</strong> <?= htmlspecialchars($schedule['meeting_point']) ?>
      </div>
      <?php endif; ?>

      <?php if($stats): ?>
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card bg-primary text-white">
            <div class="card-body text-center">
              <h4><?= $stats['total'] ?? 0 ?></h4>
              <p class="mb-0">Tổng số khách</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-success text-white">
            <div class="card-body text-center">
              <h4><?= $stats['checked_in'] ?? 0 ?></h4>
              <p class="mb-0">Đã check-in</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-warning text-white">
            <div class="card-body text-center">
              <h4><?= $stats['late'] ?? 0 ?></h4>
              <p class="mb-0">Đến muộn</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-danger text-white">
            <div class="card-body text-center">
              <h4><?= $stats['absent'] ?? 0 ?></h4>
              <p class="mb-0">Vắng mặt</p>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if(!empty($customers)): ?>
      <form action="index.php?act=hdv_checkin_store" method="POST">
        <input type="hidden" name="guide_id" value="<?= $guide_id ?>">
        <input type="hidden" name="departure_id" value="<?= $departure_id ?>">
        
        <div class="table-responsive">
          <table class="table table-hover table-bordered">
            <thead class="table-success">
              <tr>
                <th>#</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Số người</th>
                <th>Trạng thái</th>
                <th>Thời gian check-in</th>
                <th>Ghi chú</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($customers as $i => $customer): ?>
              <?php 
                $checkin = $checkinMap[$customer['id']] ?? null;
                $isCheckedIn = $checkin && $checkin['status'] == 'checked_in';
              ?>
              <tr class="<?= $isCheckedIn ? 'table-success' : '' ?>">
                <td><?= $i + 1 ?></td>
                <td class="fw-bold"><?= htmlspecialchars($customer['customer_name']) ?></td>
                <td><?= htmlspecialchars($customer['customer_phone'] ?? '') ?></td>
                <td class="text-center"><span class="badge bg-info"><?= $customer['num_people'] ?></span></td>
                <td>
                  <?php if($checkin): ?>
                    <span class="badge bg-<?= $checkin['status'] == 'checked_in' ? 'success' : ($checkin['status'] == 'late' ? 'warning' : 'danger') ?>">
                      <?= $checkin['status'] == 'checked_in' ? 'Đã check-in' : ($checkin['status'] == 'late' ? 'Đến muộn' : 'Vắng mặt') ?>
                    </span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Chưa check-in</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?= $checkin && $checkin['checkin_time'] ? date('d/m/Y H:i', strtotime($checkin['checkin_time'])) : '-' ?>
                </td>
                <td>
                  <input type="text" name="notes[<?= $customer['id'] ?>]" 
                         class="form-control form-control-sm" 
                         value="<?= htmlspecialchars($checkin['notes'] ?? '') ?>"
                         placeholder="Ghi chú...">
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="status[<?= $customer['id'] ?>]" 
                           id="checked_in_<?= $customer['id'] ?>" value="checked_in" 
                           <?= $checkin && $checkin['status'] == 'checked_in' ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-success" for="checked_in_<?= $customer['id'] ?>">
                      <i class="bi bi-check"></i> Có mặt
                    </label>
                    
                    <input type="radio" class="btn-check" name="status[<?= $customer['id'] ?>]" 
                           id="late_<?= $customer['id'] ?>" value="late"
                           <?= $checkin && $checkin['status'] == 'late' ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-warning" for="late_<?= $customer['id'] ?>">
                      <i class="bi bi-clock"></i> Muộn
                    </label>
                    
                    <input type="radio" class="btn-check" name="status[<?= $customer['id'] ?>]" 
                           id="absent_<?= $customer['id'] ?>" value="absent"
                           <?= $checkin && $checkin['status'] == 'absent' ? 'checked' : '' ?>>
                    <label class="btn btn-sm btn-danger" for="absent_<?= $customer['id'] ?>">
                      <i class="bi bi-x"></i> Vắng
                    </label>
                  </div>
                  <input type="hidden" name="booking_ids[]" value="<?= $customer['id'] ?>">
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          <div class="mb-3">
            <label class="form-label">Địa điểm check-in</label>
            <input type="text" name="checkin_location" class="form-control" 
                   value="<?= htmlspecialchars($schedule['meeting_point'] ?? '') ?>" required>
          </div>
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-check-circle"></i> Lưu check-in
          </button>
        </div>
      </form>
      <?php else: ?>
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> Chưa có khách hàng nào trong tour này.
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

