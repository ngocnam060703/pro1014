<?php
// Hàm hiển thị badge trạng thái
function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge badge-modern badge-warning">Chờ xác nhận</span>',
        'deposit_paid' => '<span class="badge badge-modern badge-info">Đã cọc</span>',
        'completed' => '<span class="badge badge-modern badge-success">Hoàn tất</span>',
        'cancelled' => '<span class="badge badge-modern badge-danger">Hủy</span>'
    ];
    $status_lower = strtolower($status ?? 'pending');
    return $badges[$status_lower] ?? '<span class="badge badge-modern badge-secondary">' . htmlspecialchars($status) . '</span>';
}

function getPaymentStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge badge-modern badge-warning">Chưa thanh toán</span>',
        'partial' => '<span class="badge badge-modern badge-info">Đã cọc</span>',
        'paid' => '<span class="badge badge-modern badge-success">Đã thanh toán</span>',
        'refunded' => '<span class="badge badge-modern badge-secondary">Đã hoàn tiền</span>'
    ];
    $status_lower = strtolower($status ?? 'pending');
    return $badges[$status_lower] ?? '<span class="badge badge-modern badge-secondary">' . htmlspecialchars($status) . '</span>';
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
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 15px 15px 0 0 !important;
    padding: 15px 20px;
    font-weight: 600;
}
.info-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    border-left: 4px solid #667eea;
}
.info-label {
    font-weight: 600;
    color: #667eea;
    margin-bottom: 8px;
}
.info-value {
    color: #212529;
    font-size: 16px;
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
}
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.table-container {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.table thead th {
    border: none;
    padding: 15px;
    font-weight: 600;
}
.table tbody tr {
    transition: all 0.3s;
}
.table tbody tr:hover {
    background: linear-gradient(to right, #f8f9ff 0%, #fff 50%);
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
    <a href="index.php?act=booking" class="active"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="card-container fade-in mb-4">
      <div class="d-flex justify-content-between align-items-center">
          <div>
              <h3 class="mb-1 fw-bold text-primary"><i class="bi bi-cart-check"></i> Chi tiết Booking #<?= $booking['id'] ?></h3>
              <p class="text-muted mb-0">Thông tin chi tiết về booking</p>
          </div>
          <a href="index.php?act=booking" class="btn btn-secondary btn-modern">
              <i class="bi bi-arrow-left-circle"></i> Quay lại danh sách
          </a>
      </div>
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

    <div class="card-container fade-in mb-4">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin Booking</h5>
      </div>
      <div class="p-4">
        <div class="row">
          <div class="col-md-6">
            <div class="info-section">
              <h6 class="mb-3"><i class="bi bi-person-circle"></i> Thông tin khách hàng</h6>
              <div class="mb-2">
                <div class="info-label">Họ tên</div>
                <div class="info-value"><?= htmlspecialchars($booking['customer_name']) ?></div>
              </div>
              <div class="mb-2">
                <div class="info-label">Email</div>
                <div class="info-value"><?= htmlspecialchars($booking['customer_email']) ?></div>
              </div>
              <div class="mb-2">
                <div class="info-label">Điện thoại</div>
                <div class="info-value"><?= htmlspecialchars($booking['customer_phone']) ?></div>
              </div>
              <?php if (!empty($booking['customer_address'])): ?>
              <div class="mb-2">
                <div class="info-label">Địa chỉ</div>
                <div class="info-value"><?= htmlspecialchars($booking['customer_address']) ?></div>
              </div>
              <?php endif; ?>
              <?php if (!empty($booking['company_name'])): ?>
              <div class="mb-2">
                <div class="info-label">Công ty/Tổ chức</div>
                <div class="info-value"><?= htmlspecialchars($booking['company_name']) ?></div>
              </div>
              <?php endif; ?>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="info-section">
              <h6 class="mb-3"><i class="bi bi-map"></i> Thông tin tour</h6>
              <div class="mb-2">
                <div class="info-label">Tour</div>
                <div class="info-value"><?= htmlspecialchars($booking['tour_title']) ?></div>
              </div>
              <div class="mb-2">
                <div class="info-label">Số lượng</div>
                <div class="info-value">
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
                </div>
              </div>
              <div class="mb-2">
                <div class="info-label">Tổng tiền</div>
                <div class="info-value fw-bold text-success" style="font-size: 1.2rem;">
                  <i class="bi bi-currency-dollar"></i> <?= number_format($booking['total_price']) ?> đ
                </div>
              </div>
              <?php if (isset($booking['deposit_amount']) && $booking['deposit_amount'] > 0): ?>
              <div class="mb-2">
                <div class="info-label">Đã cọc</div>
                <div class="info-value"><?= number_format($booking['deposit_amount']) ?> đ</div>
              </div>
              <div class="mb-2">
                <div class="info-label">Còn lại</div>
                <div class="info-value"><?= number_format($booking['remaining_amount'] ?? 0) ?> đ</div>
              </div>
              <?php endif; ?>
              <div class="mb-2">
                <div class="info-label">Ngày đặt</div>
                <div class="info-value"><i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($booking['booking_date'])) ?></div>
              </div>
              <?php if (!empty($booking['departure_date'])): ?>
              <div class="mb-2">
                <div class="info-label">Ngày khởi hành</div>
                <div class="info-value"><i class="bi bi-calendar-event"></i> <?= date('d/m/Y', strtotime($booking['departure_date'])) ?></div>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <?php if (!empty($booking['special_requests'])): ?>
        <div class="info-section mt-3">
          <h6 class="mb-2"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</h6>
          <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($booking['special_requests'])) ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($booking['notes'])): ?>
        <div class="info-section mt-3">
          <h6 class="mb-2"><i class="bi bi-sticky"></i> Ghi chú</h6>
          <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
        </div>
        <?php endif; ?>
        
        <div class="row mt-3">
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Trạng thái booking</div>
              <div class="info-value"><?= getStatusBadge($booking['status'] ?? 'pending') ?></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Trạng thái thanh toán</div>
              <div class="info-value"><?= getPaymentStatusBadge($booking['payment_status'] ?? 'pending') ?></div>
            </div>
          </div>
          <?php if (isset($booking['booking_code'])): ?>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Mã booking</div>
              <div class="info-value"><code><?= htmlspecialchars($booking['booking_code']) ?></code></div>
            </div>
          </div>
          <?php endif; ?>
          <?php if (isset($booking['confirmed_at']) && $booking['confirmed_at']): ?>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Ngày xác nhận</div>
              <div class="info-value"><?= date('d/m/Y H:i', strtotime($booking['confirmed_at'])) ?></div>
            </div>
          </div>
          <?php endif; ?>
          <?php if (isset($booking['cancelled_at']) && $booking['cancelled_at']): ?>
          <div class="col-md-6">
            <div class="info-section">
              <div class="info-label">Ngày hủy</div>
              <div class="info-value"><?= date('d/m/Y H:i', strtotime($booking['cancelled_at'])) ?></div>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Form thay đổi trạng thái -->
        <div class="card-container fade-in mb-4">
          <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Thay đổi trạng thái</h5>
          </div>
          <div class="p-4">
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
                
                <button type="submit" class="btn btn-success btn-modern">
                  <i class="bi bi-check-circle"></i> Cập nhật trạng thái
                </button>
            </form>
          </div>
        </div>

        <!-- Lịch sử thay đổi trạng thái -->
        <?php if (!empty($statusHistory)): ?>
        <div class="card-container fade-in">
          <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử thay đổi trạng thái</h5>
          </div>
          <div class="p-4">
            <div class="table-container">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
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
        </div>
        <?php endif; ?>

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
