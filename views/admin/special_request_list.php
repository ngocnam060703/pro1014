<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý yêu cầu đặc biệt</title>
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
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}
.table tbody tr {
    transition: all 0.3s;
    border-bottom: 1px solid #e9ecef;
}
.table tbody tr:hover {
    background: linear-gradient(to right, #f8f9ff 0%, #fff 50%);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.table tbody tr.table-warning {
    background: linear-gradient(to right, #fff3cd 0%, #fff 50%);
}
.table tbody td {
    padding: 15px;
    vertical-align: middle;
}
.btn-modern {
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
}
.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
}
.badge-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
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
  <div class="col-2 sidebar">
    <h4 class="mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request" class="active"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
              <h3 class="mb-1 fw-bold text-primary">
                  <i class="bi bi-exclamation-circle"></i> Quản lý yêu cầu đặc biệt
                  <?php if($pendingCount > 0): ?>
                      <span class="badge badge-modern badge-warning ms-2"><?= $pendingCount ?> chờ xử lý</span>
                  <?php endif; ?>
              </h3>
              <p class="text-muted mb-0">Tổng số: <strong><?= count($requests ?? []) ?></strong> yêu cầu</p>
          </div>
          <a href="index.php?act=dashboard" class="btn btn-secondary btn-modern">
              <i class="bi bi-arrow-left-circle"></i> Quay lại
          </a>
      </div>

      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <i class="bi bi-check-circle"></i> <?= $_SESSION['message']; unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <i class="bi bi-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Bảng danh sách -->
      <div class="table-container fade-in">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Tour</th>
                <th>Loại yêu cầu</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
            <?php if(!empty($requests)): ?>
              <?php foreach($requests as $req): ?>
                <tr class="<?= $req['status'] == 'pending' ? 'table-warning' : '' ?>">
                  <td class="fw-bold">#<?= $req['id'] ?></td>
                  <td>
                    <strong class="text-primary"><i class="bi bi-person"></i> <?= htmlspecialchars($req['customer_name']) ?></strong><br>
                    <small class="text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($req['customer_phone'] ?? '') ?></small>
                  </td>
                  <td><i class="bi bi-map"></i> <?= htmlspecialchars($req['tour_title'] ?? 'N/A') ?></td>
                  <td>
                    <?php
                    $typeLabels = [
                        'diet' => '<span class="badge bg-info"><i class="bi bi-cup"></i> Ăn uống</span>',
                        'medical' => '<span class="badge bg-danger"><i class="bi bi-heart-pulse"></i> Y tế</span>',
                        'accessibility' => '<span class="badge bg-primary"><i class="bi bi-universal-access"></i> Tiếp cận</span>',
                        'other' => '<span class="badge bg-secondary"><i class="bi bi-three-dots"></i> Khác</span>'
                    ];
                    echo $typeLabels[$req['request_type']] ?? '<span class="badge bg-secondary">' . htmlspecialchars($req['request_type']) . '</span>';
                    ?>
                  </td>
                  <td>
                    <small><?= htmlspecialchars(mb_substr($req['description'], 0, 50)) . (mb_strlen($req['description']) > 50 ? '...' : '') ?></small>
                  </td>
                  <td>
                    <span class="badge <?= $req['status'] == 'completed' ? 'badge-modern badge-success' : ($req['status'] == 'confirmed' ? 'badge-modern badge-info' : 'badge-modern badge-warning') ?>">
                      <?= $req['status'] == 'completed' ? 'Hoàn thành' : ($req['status'] == 'confirmed' ? 'Đã xác nhận' : 'Chờ xử lý') ?>
                    </span>
                  </td>
                  <td><i class="bi bi-calendar"></i> <?= date('d/m/Y H:i', strtotime($req['created_at'])) ?></td>
                  <td class="text-center">
                    <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewModal<?= $req['id'] ?>" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </button>
                    <a href="index.php?act=special-request-edit&id=<?= $req['id'] ?>" class="btn btn-warning btn-sm me-1" title="Sửa">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#statusModal<?= $req['id'] ?>" title="Cập nhật trạng thái">
                      <i class="bi bi-check-circle"></i>
                    </button>
                    <a href="index.php?act=special-request-delete&id=<?= $req['id'] ?>" 
                       onclick="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?')" 
                       class="btn btn-danger btn-sm" title="Xóa">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>

                <!-- Modal Xem chi tiết -->
                <div class="modal fade" id="viewModal<?= $req['id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-info-circle"></i> Chi tiết yêu cầu #<?= $req['id'] ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <div class="info-section">
                              <div class="info-label">Khách hàng</div>
                              <div class="info-value"><?= htmlspecialchars($req['customer_name']) ?></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="info-section">
                              <div class="info-label">Điện thoại</div>
                              <div class="info-value"><?= htmlspecialchars($req['customer_phone'] ?? '') ?></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="info-section">
                              <div class="info-label">Email</div>
                              <div class="info-value"><?= htmlspecialchars($req['customer_email'] ?? '') ?></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="info-section">
                              <div class="info-label">Tour</div>
                              <div class="info-value"><?= htmlspecialchars($req['tour_title'] ?? 'N/A') ?></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="info-section">
                              <div class="info-label">Loại yêu cầu</div>
                              <div class="info-value">
                                <?php
                                $typeLabels = [
                                    'diet' => 'Ăn uống',
                                    'medical' => 'Y tế',
                                    'accessibility' => 'Tiếp cận',
                                    'other' => 'Khác'
                                ];
                                echo $typeLabels[$req['request_type']] ?? $req['request_type'];
                                ?>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="info-section">
                              <div class="info-label">Trạng thái</div>
                              <div class="info-value">
                                <span class="badge <?= $req['status'] == 'completed' ? 'badge-modern badge-success' : ($req['status'] == 'confirmed' ? 'badge-modern badge-info' : 'badge-modern badge-warning') ?>">
                                  <?= $req['status'] == 'completed' ? 'Hoàn thành' : ($req['status'] == 'confirmed' ? 'Đã xác nhận' : 'Chờ xử lý') ?>
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="info-section">
                              <div class="info-label">Mô tả</div>
                              <div class="info-value"><?= nl2br(htmlspecialchars($req['description'])) ?></div>
                            </div>
                          </div>
                          <?php if($req['notes']): ?>
                          <div class="col-12">
                            <div class="info-section">
                              <div class="info-label">Ghi chú</div>
                              <div class="info-value"><?= nl2br(htmlspecialchars($req['notes'])) ?></div>
                            </div>
                          </div>
                          <?php endif; ?>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal Cập nhật trạng thái -->
                <div class="modal fade" id="statusModal<?= $req['id'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-arrow-repeat"></i> Cập nhật trạng thái</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <form action="index.php?act=special-request-update-status" method="POST">
                        <div class="modal-body">
                          <input type="hidden" name="id" value="<?= $req['id'] ?>">
                          <div class="mb-3">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <select name="status" class="form-select" required>
                              <option value="pending" <?= $req['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                              <option value="confirmed" <?= $req['status'] == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                              <option value="completed" <?= $req['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label fw-semibold">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($req['notes'] ?? '') ?></textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                          <button type="submit" class="btn btn-primary btn-modern">Cập nhật</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">
                  <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="mt-3">Chưa có yêu cầu đặc biệt nào</h5>
                    <p class="text-muted">Các yêu cầu đặc biệt từ khách hàng sẽ hiển thị ở đây</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
