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
    background: linear-gradient(to right, #dfe9f3, #ffffff);
    font-family: 'Segoe UI', sans-serif;
}
.sidebar {
    height: 100vh;
    background: #343a40;
    padding-top: 20px;
}
.sidebar h4 { font-weight: 700; color:#fff; }
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
.sidebar a.active {
    color:#fff;
    background:#495057;
    border-left:3px solid #0d6efd;
}
.content { padding: 30px; }
.card {
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.table thead {
    background: linear-gradient(to right, #5a5afc, #6c63ff);
    color: #fff;
}
</style>
</head>
<body>
<div class="row g-0">
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>
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
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-exclamation-circle"></i> Quản lý yêu cầu đặc biệt
        <?php if($pendingCount > 0): ?>
          <span class="badge bg-danger ms-2"><?= $pendingCount ?> chờ xử lý</span>
        <?php endif; ?>
      </h3>
      <div>
        <a href="index.php?act=dashboard" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại
        </a>
      </div>
    </div>

    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="card p-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle mb-0">
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
                  <td><?= $req['id'] ?></td>
                  <td>
                    <strong><?= htmlspecialchars($req['customer_name']) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars($req['customer_phone'] ?? '') ?></small>
                  </td>
                  <td><?= htmlspecialchars($req['tour_title'] ?? 'N/A') ?></td>
                  <td>
                    <?php
                    $typeLabels = [
                        'diet' => 'Ăn uống',
                        'medical' => 'Y tế',
                        'accessibility' => 'Tiếp cận',
                        'other' => 'Khác'
                    ];
                    echo $typeLabels[$req['request_type']] ?? $req['request_type'];
                    ?>
                  </td>
                  <td>
                    <small><?= htmlspecialchars(mb_substr($req['description'], 0, 50)) . (mb_strlen($req['description']) > 50 ? '...' : '') ?></small>
                  </td>
                  <td>
                    <span class="badge bg-<?= $req['status'] == 'completed' ? 'success' : ($req['status'] == 'confirmed' ? 'info' : 'warning') ?>">
                      <?= $req['status'] == 'completed' ? 'Hoàn thành' : ($req['status'] == 'confirmed' ? 'Đã xác nhận' : 'Chờ xử lý') ?>
                    </span>
                  </td>
                  <td><?= date('d/m/Y H:i', strtotime($req['created_at'])) ?></td>
                  <td class="text-center d-flex justify-content-center gap-1">
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $req['id'] ?>">
                      <i class="bi bi-eye"></i>
                    </button>
                    <a href="index.php?act=special-request-edit&id=<?= $req['id'] ?>" class="btn btn-warning btn-sm">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#statusModal<?= $req['id'] ?>">
                      <i class="bi bi-check-circle"></i>
                    </button>
                    <a href="index.php?act=special-request-delete&id=<?= $req['id'] ?>" 
                       onclick="return confirm('Xóa yêu cầu này?')" 
                       class="btn btn-danger btn-sm">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>

                <!-- Modal Xem chi tiết -->
                <div class="modal fade" id="viewModal<?= $req['id'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Chi tiết yêu cầu #<?= $req['id'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <p><strong>Khách hàng:</strong> <?= htmlspecialchars($req['customer_name']) ?></p>
                        <p><strong>Điện thoại:</strong> <?= htmlspecialchars($req['customer_phone'] ?? '') ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($req['customer_email'] ?? '') ?></p>
                        <p><strong>Tour:</strong> <?= htmlspecialchars($req['tour_title'] ?? 'N/A') ?></p>
                        <p><strong>Loại yêu cầu:</strong> <?= $typeLabels[$req['request_type']] ?? $req['request_type'] ?></p>
                        <p><strong>Mô tả:</strong><br><?= nl2br(htmlspecialchars($req['description'])) ?></p>
                        <?php if($req['notes']): ?>
                        <p><strong>Ghi chú:</strong><br><?= nl2br(htmlspecialchars($req['notes'])) ?></p>
                        <?php endif; ?>
                        <p><strong>Trạng thái:</strong> 
                          <span class="badge bg-<?= $req['status'] == 'completed' ? 'success' : ($req['status'] == 'confirmed' ? 'info' : 'warning') ?>">
                            <?= $req['status'] == 'completed' ? 'Hoàn thành' : ($req['status'] == 'confirmed' ? 'Đã xác nhận' : 'Chờ xử lý') ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal Cập nhật trạng thái -->
                <div class="modal fade" id="statusModal<?= $req['id'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Cập nhật trạng thái</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <form action="index.php?act=special-request-update-status" method="POST">
                        <div class="modal-body">
                          <input type="hidden" name="id" value="<?= $req['id'] ?>">
                          <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select" required>
                              <option value="pending" <?= $req['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                              <option value="confirmed" <?= $req['status'] == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                              <option value="completed" <?= $req['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($req['notes'] ?? '') ?></textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                          <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-3">
                  <i class="bi bi-info-circle"></i> Chưa có yêu cầu đặc biệt nào
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

