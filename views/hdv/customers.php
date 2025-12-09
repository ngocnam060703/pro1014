<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../models/GuideScheduleModel.php";
require_once __DIR__ . "/../../models/CustomerSpecialRequestModel.php";

$guide_id = $_SESSION['guide']['id'] ?? 0;
$departure_id = $_GET['departure_id'] ?? 0;

$scheduleModel = new GuideScheduleModel();
$customers = $scheduleModel->getTourCustomers($departure_id);
$schedule = $scheduleModel->getScheduleDetail($guide_id, $departure_id);

$specialRequestModel = new CustomerSpecialRequestModel();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Danh sách khách trong đoàn</title>
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
        <h3 class="mb-0 fw-bold text-primary"><i class="bi bi-people"></i> Danh sách khách trong đoàn</h3>
        <div>
          <a href="index.php?act=hdv_schedule_detail&departure_id=<?= $departure_id ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
          </a>
          <a href="index.php?act=hdv_checkin&departure_id=<?= $departure_id ?>" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Check-in
          </a>
        </div>
      </div>

      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> <?= $_SESSION['message']; unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if($schedule): ?>
      <div class="alert alert-info mb-4">
        <strong>Tour:</strong> <?= htmlspecialchars($schedule['tour_name']) ?> | 
        <strong>Ngày khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($schedule['departure_time'])) ?>
      </div>
      <?php endif; ?>

      <?php if(!empty($customers)): ?>
      <div class="table-responsive">
        <table class="table table-hover table-bordered">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Số điện thoại</th>
              <th>Số người</th>
              <th>Yêu cầu đặc biệt</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($customers as $i => $customer): ?>
            <?php 
              $specialRequests = $specialRequestModel->getByBooking($customer['id']);
            ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td class="fw-bold"><?= htmlspecialchars($customer['customer_name']) ?></td>
              <td><?= htmlspecialchars($customer['customer_email'] ?? '') ?></td>
              <td><?= htmlspecialchars($customer['customer_phone'] ?? '') ?></td>
              <td class="text-center"><span class="badge bg-info"><?= $customer['num_people'] ?></span></td>
              <td>
                <?php if(!empty($specialRequests)): ?>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#requestsModal<?= $customer['id'] ?>">
                    <i class="bi bi-info-circle"></i> Xem (<?= count($specialRequests) ?>)
                  </button>
                <?php else: ?>
                  <span class="text-muted">Không có</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge bg-<?= $customer['status'] == 'confirmed' ? 'success' : 'warning' ?>">
                  <?= $customer['status'] == 'confirmed' ? 'Đã xác nhận' : ucfirst($customer['status']) ?>
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRequestModal<?= $customer['id'] ?>">
                  <i class="bi bi-plus-circle"></i> Thêm yêu cầu
                </button>
              </td>
            </tr>

            <!-- Modal Yêu cầu đặc biệt -->
            <?php if(!empty($specialRequests)): ?>
            <div class="modal fade" id="requestsModal<?= $customer['id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Yêu cầu đặc biệt - <?= htmlspecialchars($customer['customer_name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <?php foreach($specialRequests as $req): ?>
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="card-title"><?= htmlspecialchars($req['request_type']) ?></h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($req['description'])) ?></p>
                        <small class="text-muted">Trạng thái: 
                          <span class="badge bg-<?= $req['status'] == 'completed' ? 'success' : ($req['status'] == 'confirmed' ? 'info' : 'warning') ?>">
                            <?= $req['status'] == 'completed' ? 'Hoàn thành' : ($req['status'] == 'confirmed' ? 'Đã xác nhận' : 'Chờ xử lý') ?>
                          </span>
                        </small>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <!-- Modal Thêm yêu cầu -->
            <div class="modal fade" id="addRequestModal<?= $customer['id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Thêm yêu cầu đặc biệt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form action="index.php?act=hdv_special_request_store" method="POST">
                    <div class="modal-body">
                      <input type="hidden" name="booking_id" value="<?= $customer['id'] ?>">
                      <div class="mb-3">
                        <label class="form-label">Loại yêu cầu</label>
                        <select name="request_type" class="form-select" required>
                          <option value="diet">Ăn uống (ăn chay, kiêng...)</option>
                          <option value="medical">Y tế (bệnh lý, thuốc...)</option>
                          <option value="accessibility">Khả năng tiếp cận</option>
                          <option value="other">Khác</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                      <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
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

