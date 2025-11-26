<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm phân công HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: linear-gradient(to right, #a1c4fd, #c2e9fb); font-family: 'Segoe UI', sans-serif; }
.form-container {
  max-width: 900px; margin: 40px auto; background: #fff; padding: 30px;
  border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  border-top: 8px solid #5a5afc;
}
.form-header { font-weight: 700; color: #5a5afc; margin-bottom: 25px; }
.form-section { padding: 15px 20px; margin-bottom: 20px; border-radius: 12px; background: linear-gradient(to right, #fdfbfb, #ebedee); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
.btn-primary { background: linear-gradient(45deg,#5a5afc,#fc5a8d); border: none; }
.btn-primary:hover { background: linear-gradient(45deg,#fc5a8d,#5a5afc); }
.btn-secondary { background: #6c757d; border: none; }
</style>
</head>
<body>

<div class="form-container">
  <h3 class="form-header"><i class="bi bi-plus-circle"></i> Thêm phân công HDV</h3>

  <form action="index.php?act=guide-assign-store" method="POST">
    <div class="row g-3">

      <div class="col-md-6 form-section">
        <label class="form-label">Hướng dẫn viên</label>
        <select name="guide_id" class="form-select" required>
          <option value="">-- Chọn HDV --</option>
          <?php foreach($guides as $g): ?>
            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['fullname']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Tour</label>
        <select name="tour_id" class="form-select" required>
          <option value="">-- Chọn Tour --</option>
          <?php foreach($tours as $t): ?>
            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Lịch khởi hành</label>
        <select name="departure_id" class="form-select" required>
          <option value="">-- Chọn lịch --</option>
          <?php foreach($departures as $d): ?>
            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['departure_time'].' | '.$d['meeting_point']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Ngày khởi hành</label>
        <input type="date" name="departure_date" class="form-control" required>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Điểm tập trung</label>
        <input type="text" name="meeting_point" class="form-control">
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Số khách tối đa</label>
        <input type="number" name="max_people" class="form-control" min="1" required>
      </div>

      <div class="col-12 form-section">
        <label class="form-label">Ghi chú</label>
        <textarea name="note" class="form-control" rows="3"></textarea>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select" required>
          <option value="scheduled">Chưa bắt đầu</option>
          <option value="in_progress">Đang thực hiện</option>
          <option value="completed">Hoàn thành</option>
        </select>
      </div>

      <div class="col-12 d-flex justify-content-between mt-3">
        <a href="index.php?act=guide-assign" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu phân công</button>
      </div>

    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
