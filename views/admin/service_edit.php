<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa dịch vụ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h3 class="mb-4 fw-bold">Sửa dịch vụ</h3>

  <form action="index.php?act=service-update" method="POST" class="card p-4 shadow-sm">

    <!-- ID ẩn -->
    <input type="hidden" name="id" value="<?= $service['id'] ?>">

    <!-- Chọn chuyến đi -->
    <div class="mb-3">
      <label class="form-label">Chuyến đi</label>
      <select name="trip" class="form-control" required>
        <?php foreach($trips as $t): ?>
          <option value="<?= $t['id'] ?>" <?= $t['id']==$service['trip'] ? 'selected' : '' ?>>
            <?= $t['title'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Tên dịch vụ -->
    <div class="mb-3">
      <label class="form-label">Tên dịch vụ</label>
      <input type="text" name="service_name" class="form-control" value="<?= htmlspecialchars($service['service_name']) ?>" required>
    </div>

    <!-- Trạng thái -->
    <div class="mb-3">
      <label class="form-label">Trạng thái</label>
      <select name="status" class="form-control">
        <option value="Hoạt động" <?= $service['status']=='Hoạt động'?'selected':'' ?>>Hoạt động</option>
        <option value="Tạm ngưng" <?= $service['status']=='Tạm ngưng'?'selected':'' ?>>Tạm ngưng</option>
        <option value="Ngừng hoạt động" <?= $service['status']=='Ngừng hoạt động'?'selected':'' ?>>Ngừng hoạt động</option>
      </select>
    </div>

    <!-- Ghi chú -->
    <div class="mb-3">
      <label class="form-label">Ghi chú</label>
      <textarea name="note" class="form-control"><?= htmlspecialchars($service['note'] ?? '') ?></textarea>
    </div>

    <button class="btn btn-primary">Cập nhật</button>
    <a href="index.php?act=service" class="btn btn-secondary">Quay lại</a>

  </form>
</div>
</body>
</html>
