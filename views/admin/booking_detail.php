<?php
// Hàm hiển thị badge trạng thái
function getStatusBadge($status) {
    $badges = [
        'pending' => ['class' => 'warning', 'text' => 'Chờ xác nhận'],
        'deposit_paid' => ['class' => 'info', 'text' => 'Đã cọc'],
        'completed' => ['class' => 'success', 'text' => 'Hoàn tất'],
        'cancelled' => ['class' => 'danger', 'text' => 'Hủy']
    ];
    $status_lower = strtolower($status ?? 'pending');
    $badge = $badges[$status_lower] ?? ['class' => 'secondary', 'text' => $status];
    return '<span class="badge bg-' . $badge['class'] . '">' . $badge['text'] . '</span>';
}

function getPaymentStatusBadge($status) {
    $badges = [
        'pending' => ['class' => 'warning', 'text' => 'Chưa thanh toán'],
        'partial' => ['class' => 'info', 'text' => 'Đã cọc'],
        'paid' => ['class' => 'success', 'text' => 'Đã thanh toán'],
        'refunded' => ['class' => 'secondary', 'text' => 'Đã hoàn tiền']
    ];
    $status_lower = strtolower($status ?? 'pending');
    $badge = $badges[$status_lower] ?? ['class' => 'secondary', 'text' => $status];
    return '<span class="badge bg-' . $badge['class'] . '">' . $badge['text'] . '</span>';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết Booking #<?= $booking['id'] ?></title>

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
.sidebar h4 { font-weight: 700; }
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
.content { padding: 30px; }
.card {
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.btn-primary {
    background: linear-gradient(45deg,#5a5afc,#fc5a8d);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(45deg,#fc5a8d,#5a5afc);
}
.btn-success, .btn-secondary {
    border-radius: 6px;
}
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
    <a href="index.php?act=booking" style="color:#fff; background:#495057; border-left:3px solid #0d6efd;">
      <i class="bi bi-cart"></i> Quản lý Booking
    </a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-cart-check"></i> Chi tiết Booking #<?= $booking['id'] ?>
      </h3>
    </div>

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

    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin Booking</h5>
      </div>
      <div class="card-body p-4">
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-primary mb-3">Thông tin khách hàng</h6>
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($booking['customer_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($booking['customer_email']) ?></p>
            <p><strong>Điện thoại:</strong> <?= htmlspecialchars($booking['customer_phone']) ?></p>
            <?php if (!empty($booking['customer_address'])): ?>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($booking['customer_address']) ?></p>
            <?php endif; ?>
            <?php if (!empty($booking['company_name'])): ?>
            <p><strong>Công ty/Tổ chức:</strong> <?= htmlspecialchars($booking['company_name']) ?></p>
            <?php endif; ?>
          </div>
          
          <div class="col-md-6">
            <h6 class="text-primary mb-3">Thông tin tour</h6>
            <p><strong>Tour:</strong> <?= htmlspecialchars($booking['tour_title']) ?></p>
            <p><strong>Số lượng:</strong> 
              <?php if (isset($booking['num_adults']) && $booking['num_adults'] > 0): ?>
                <?= $booking['num_adults'] ?> người lớn
              <?php endif; ?>
              <?php if (isset($booking['num_children']) && $booking['num_children'] > 0): ?>
                , <?= $booking['num_children'] ?> trẻ em
              <?php endif; ?>
              <?php if (isset($booking['num_infants']) && $booking['num_infants'] > 0): ?>
                , <?= $booking['num_infants'] ?> trẻ sơ sinh
              <?php endif; ?>
              (Tổng: <?= $booking['num_people'] ?> người)
            </p>
            <p><strong>Tổng tiền:</strong> <span class="text-success fw-bold"><?= number_format($booking['total_price']) ?> đ</span></p>
            <?php if (isset($booking['deposit_amount']) && $booking['deposit_amount'] > 0): ?>
            <p><strong>Đã cọc:</strong> <?= number_format($booking['deposit_amount']) ?> đ</p>
            <p><strong>Còn lại:</strong> <?= number_format($booking['remaining_amount'] ?? 0) ?> đ</p>
            <?php endif; ?>
            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y', strtotime($booking['booking_date'])) ?></p>
            <?php if (!empty($booking['departure_date'])): ?>
            <p><strong>Ngày khởi hành:</strong> <?= date('d/m/Y', strtotime($booking['departure_date'])) ?></p>
            <?php endif; ?>
          </div>
        </div>
        
        <?php if (!empty($booking['special_requests'])): ?>
        <div class="mt-3">
          <h6 class="text-primary">Yêu cầu đặc biệt</h6>
          <p class="text-muted"><?= nl2br(htmlspecialchars($booking['special_requests'])) ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($booking['notes'])): ?>
        <div class="mt-3">
          <h6 class="text-primary">Ghi chú</h6>
          <p class="text-muted"><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
        </div>
        <?php endif; ?>
        
        <div class="row mb-3 mt-3">
          <div class="col-md-6">
            <p><strong>Trạng thái booking:</strong> <?= getStatusBadge($booking['status'] ?? 'pending') ?></p>
            <p><strong>Trạng thái thanh toán:</strong> <?= getPaymentStatusBadge($booking['payment_status'] ?? 'pending') ?></p>
          </div>
          <div class="col-md-6">
            <?php if (isset($booking['booking_code'])): ?>
            <p><strong>Mã booking:</strong> <code><?= htmlspecialchars($booking['booking_code']) ?></code></p>
            <?php endif; ?>
            <?php if (isset($booking['confirmed_at']) && $booking['confirmed_at']): ?>
            <p><strong>Ngày xác nhận:</strong> <?= date('d/m/Y H:i', strtotime($booking['confirmed_at'])) ?></p>
            <?php endif; ?>
            <?php if (isset($booking['cancelled_at']) && $booking['cancelled_at']): ?>
            <p><strong>Ngày hủy:</strong> <?= date('d/m/Y H:i', strtotime($booking['cancelled_at'])) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Form thay đổi trạng thái -->
        <div class="card mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Thay đổi trạng thái</h5>
          </div>
          <div class="card-body">
            <form action="index.php?act=booking-update-status" method="post">
                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Trạng thái booking <span class="text-danger">*</span></label>
                    <?php 
                    $payment_status = strtolower($booking['payment_status'] ?? 'pending');
                    $can_cancel = !in_array($payment_status, ['paid', 'partial']);
                    ?>
                    <select name="status" class="form-select" required id="booking_status">
                        <option value="pending" <?= strtolower($booking['status'] ?? '')=='pending'?'selected':'' ?>>Chờ xác nhận</option>
                        <option value="deposit_paid" <?= strtolower($booking['status'] ?? '')=='deposit_paid'?'selected':'' ?>>Đã cọc</option>
                        <option value="completed" <?= strtolower($booking['status'] ?? '')=='completed'?'selected':'' ?>>Hoàn tất</option>
                        <option value="cancelled" <?= strtolower($booking['status'] ?? '')=='cancelled'?'selected':'' ?> <?= !$can_cancel ? 'disabled' : '' ?>>Hủy</option>
                    </select>
                    <?php if (!$can_cancel): ?>
                      <small class="text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Không thể hủy booking vì khách đã thanh toán hoặc đã đặt cọc. Vui lòng hoàn tiền trước khi hủy.
                      </small>
                    <?php endif; ?>
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Trạng thái thanh toán</label>
                    <?php 
                    // Kiểm tra tour đã kết thúc chưa
                    $tour_ended = false;
                    if (!empty($booking['departure_id'])) {
                        require_once "models/ScheduleModel.php";
                        $scheduleModel = new ScheduleModel();
                        $departure = $scheduleModel->getById($booking['departure_id']);
                        if ($departure) {
                            $tour_ended = ($departure['status'] == 'completed') || 
                                         (!empty($departure['end_date']) && strtotime($departure['end_date']) < time());
                        }
                    }
                    ?>
                    <select name="payment_status" class="form-select" id="payment_status">
                        <option value="">-- Giữ nguyên --</option>
                        <option value="pending" <?= strtolower($booking['payment_status'] ?? '')=='pending'?'selected':'' ?>>Chưa thanh toán</option>
                        <option value="partial" <?= strtolower($booking['payment_status'] ?? '')=='partial'?'selected':'' ?>>Đã cọc</option>
                        <option value="paid" <?= strtolower($booking['payment_status'] ?? '')=='paid'?'selected':'' ?> <?= $tour_ended ? 'disabled' : '' ?>>Đã thanh toán</option>
                        <option value="refunded" <?= strtolower($booking['payment_status'] ?? '')=='refunded'?'selected':'' ?>>Đã hoàn tiền</option>
                    </select>
                    <?php if ($tour_ended): ?>
                      <small class="text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Không thể ghi nhận thanh toán mới vì tour đã kết thúc.
                      </small>
                    <?php endif; ?>
                  </div>
                </div>
                
                <div class="mb-3">
                  <label class="form-label">Lý do thay đổi</label>
                  <textarea name="change_reason" class="form-control" rows="2" placeholder="Nhập lý do thay đổi trạng thái (nếu có)"></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">
                  <i class="bi bi-check-circle"></i> Cập nhật trạng thái
                </button>
            </form>
          </div>
        </div>

        <!-- Lịch sử thay đổi trạng thái -->
        <?php if (!empty($statusHistory)): ?>
        <div class="card">
          <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử thay đổi trạng thái</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Thời gian</th>
                    <th>Trạng thái cũ</th>
                    <th>Trạng thái mới</th>
                    <th>Thanh toán cũ</th>
                    <th>Thanh toán mới</th>
                    <th>Người thay đổi</th>
                    <th>Lý do</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($statusHistory as $history): ?>
                  <tr>
                    <td><?= date('d/m/Y H:i:s', strtotime($history['created_at'])) ?></td>
                    <td><?= getStatusBadge($history['old_status'] ?? 'N/A') ?></td>
                    <td><?= getStatusBadge($history['new_status']) ?></td>
                    <td><?= getPaymentStatusBadge($history['old_payment_status']) ?></td>
                    <td><?= getPaymentStatusBadge($history['new_payment_status']) ?></td>
                    <td><?= htmlspecialchars($history['changed_by_name'] ?? 'Hệ thống') ?></td>
                    <td><?= htmlspecialchars($history['change_reason'] ?? '-') ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <a href="index.php?act=booking" class="btn btn-secondary mt-3">
          <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
        </a>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validation khi submit form thay đổi trạng thái
document.querySelector('form[action*="booking-update-status"]')?.addEventListener('submit', function(e) {
    const statusSelect = document.getElementById('booking_status');
    const paymentSelect = document.getElementById('payment_status');
    const selectedStatus = statusSelect.value;
    const selectedPayment = paymentSelect.value;
    const currentPaymentStatus = '<?= strtolower($booking['payment_status'] ?? 'pending') ?>';
    const tourEnded = <?= $tour_ended ? 'true' : 'false' ?>;
    
    // Kiểm tra hủy booking khi đã thanh toán
    if (selectedStatus === 'cancelled') {
        if (currentPaymentStatus === 'paid' || currentPaymentStatus === 'partial') {
            e.preventDefault();
            alert('Không thể hủy booking vì khách đã thanh toán hoặc đã đặt cọc. Vui lòng hoàn tiền trước khi hủy.');
            return false;
        }
    }
    
    // Kiểm tra ghi nhận thanh toán khi tour đã kết thúc
    if ((selectedPayment === 'paid' || selectedPayment === 'partial') && tourEnded) {
        e.preventDefault();
        alert('Không thể ghi nhận thanh toán mới vì tour đã kết thúc.');
        return false;
    }
    
    // Kiểm tra hoàn tiền
    if (selectedPayment === 'refunded' && currentPaymentStatus === 'pending') {
        e.preventDefault();
        alert('Không thể hoàn tiền vì khách chưa thanh toán.');
        return false;
    }
    
    return true;
});
</script>
</body>
</html>
