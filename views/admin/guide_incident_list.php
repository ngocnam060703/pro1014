<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Danh sách sự cố HDV</title>

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
    text-align: center;
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
.table tbody td {
    padding: 15px;
    vertical-align: middle;
    text-align: center;
}
.badge-modern {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
}
.badge-info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); }
.badge-warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.badge-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
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
.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}
.action-btn {
    margin: 0 3px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}
.action-btn.info { background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); color: #fff; }
.action-btn.warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529; }
.action-btn.danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff; }
.photo-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}
.photo-thumbnail:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
.text-truncate-custom {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
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
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident" class="active">
      <i class="bi bi-exclamation-triangle"></i> Danh sách sự cố
    </a>

    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">

    <div class="card-container fade-in">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
              <h3 class="mb-1 fw-bold text-primary">
                  <i class="bi bi-exclamation-triangle"></i> Danh sách sự cố HDV
              </h3>
              <p class="text-muted mb-0">Tổng số: <strong><?= count($incidents ?? []) ?></strong> sự cố</p>
          </div>

          <a href="index.php?act=dashboard" class="btn btn-secondary btn-modern">
              <i class="bi bi-arrow-left-circle"></i> Quay lại Dashboard
          </a>
      </div>

      <!-- Bảng danh sách -->
      <div class="table-container fade-in">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tên HDV</th>
                <th>Chuyến đi</th>
                <th>Loại sự cố</th>
                <th>Mức độ</th>
                <th>Mô tả</th>
                <th>Giải pháp</th>
                <th>Ảnh</th>
                <th>Hành động</th>
              </tr>
            </thead>

            <tbody>
            <?php if (!empty($incidents)): ?>
              <?php foreach ($incidents as $i): ?>
              <tr>
                <td class="fw-bold">#<?= $i['id'] ?></td>
                <td class="fw-semibold text-primary">
                  <i class="bi bi-person-badge"></i> <?= htmlspecialchars($i['guide_name']) ?>
                </td>
                <td>
                  <i class="bi bi-map"></i> <?= htmlspecialchars($i['departure_name']) ?>
                </td>
                <td>
                  <span class="badge badge-modern badge-secondary">
                    <?= htmlspecialchars($i['incident_type']) ?>
                  </span>
                </td>
                <td>
                  <?php
                    $severity = $i['severity'] ?? 'low';
                    $badgeClass = [
                      'low' => 'badge-modern badge-info',
                      'medium' => 'badge-modern badge-warning',
                      'high' => 'badge-modern badge-danger'
                    ];
                    $badge = $badgeClass[$severity] ?? 'badge-modern badge-secondary';
                    $severityText = [
                      'low' => 'Thấp',
                      'medium' => 'Trung bình',
                      'high' => 'Cao'
                    ];
                    echo '<span class="badge ' . $badge . '">' . ($severityText[$severity] ?? ucfirst($severity)) . '</span>';
                  ?>
                </td>
                <td>
                  <div class="text-truncate-custom" title="<?= htmlspecialchars($i['description']) ?>">
                    <?= htmlspecialchars($i['description']) ?>
                  </div>
                </td>
                <td>
                  <div class="text-truncate-custom" title="<?= htmlspecialchars($i['solution'] ?? '') ?>">
                    <?= htmlspecialchars($i['solution'] ?? 'Chưa có') ?>
                  </div>
                </td>

                <td>
                  <?php if(!empty($i['photos'])): ?>
                    <img src="<?= htmlspecialchars($i['photos']) ?>" alt="Ảnh sự cố" class="photo-thumbnail">
                  <?php else: ?>
                    <span class="text-muted"><i class="bi bi-image"></i> Chưa có</span>
                  <?php endif; ?>
                </td>

                <td>
                  <a href="index.php?act=guide-incident-detail&id=<?= $i['id'] ?>" 
                     class="action-btn info" title="Chi tiết">
                    <i class="bi bi-eye"></i>
                  </a>

                  <a href="index.php?act=guide-incident-edit&id=<?= $i['id'] ?>" 
                     class="action-btn warning" title="Sửa">
                    <i class="bi bi-pencil-square"></i>
                  </a>

                  <a href="index.php?act=guide-incident-delete&id=<?= $i['id'] ?>" 
                     onclick="return confirm('Bạn chắc chắn muốn xóa sự cố này?')"
                     class="action-btn danger" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>

              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="9" class="text-center">
                  <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="mt-3">Hiện chưa có sự cố nào</h5>
                    <p class="text-muted">Các sự cố được báo cáo sẽ hiển thị ở đây</p>
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
