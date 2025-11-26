<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm dịch vụ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h3 class="mb-4 fw-bold">Thêm dịch vụ</h3>

  <form action="index.php?act=service-store" method="POST" class="card p-4 shadow-sm">

  <div class="mb-3">
    <label class="form-label">Chuyến đi</label>
    <select name="trip" class="form-control" required>
      <?php foreach($trips as $t): ?>
        <option value="<?= $t['id'] ?>"><?= $t['title'] ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Tên dịch vụ</label>
    <input type="text" name="service_name" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-control">
      <option>Hoạt động</option>
      <option>Tạm ngưng</option>
      <option>Ngừng hoạt động</option>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Ghi chú</label>
    <textarea name="note" class="form-control"></textarea>
  </div>

  <button class="btn btn-primary">Thêm</button>
  <a href="index.php?act=service" class="btn btn-secondary">Quay lại</a>

</form>

</div>
</body>
</html>
