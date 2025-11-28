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
<title><?= isset($tour) ? 'Sửa Tour' : 'Thêm Tour' ?></title>

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
.btn-success, .btn-secondary {
    border-radius: 50px;
}
</style>
</head>

<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour" class="active"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="card p-4">
      <h3 class="mb-4 fw-bold text-primary">
        <i class="bi bi-card-list"></i> <?= isset($tour) ? 'Sửa Tour' : 'Thêm Tour' ?>
      </h3>

      <form action="index.php?act=<?= isset($tour) ? 'tour-update' : 'tour-store' ?>" method="POST">
        <?php if(isset($tour)) { ?>
            <input type="hidden" name="id" value="<?= $tour['id'] ?>">
        <?php } ?>

        <div class="mb-3">
          <label class="form-label">Tiêu đề</label>
          <input type="text" name="title" class="form-control" value="<?= $tour['title'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Mô tả</label>
          <textarea name="description" class="form-control" rows="3" required><?= $tour['description'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Lịch trình</label>
          <textarea name="itinerary" class="form-control" rows="3" required><?= $tour['itinerary'] ?? '' ?></textarea>
        </div>

        <div class="row">
          <div class="col mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="<?= $tour['price'] ?? '' ?>" required>
          </div>
          <div class="col mb-3">
            <label class="form-label">Số chỗ</label>
            <input type="number" name="slots" class="form-control" value="<?= $tour['slots'] ?? '' ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Điểm khởi hành</label>
          <input type="text" name="departure" class="form-control" value="<?= $tour['departure'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Trạng thái</label>
          <select name="status" class="form-select">
            <option value="active" <?= (isset($tour) && $tour['status']=='active')?'selected':'' ?>>Active</option>
            <option value="inactive" <?= (isset($tour) && $tour['status']=='inactive')?'selected':'' ?>>Inactive</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i> <?= isset($tour) ? 'Cập nhật' : 'Lưu' ?></button>
        <a href="index.php?act=tour" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
      </form>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
