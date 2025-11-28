<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm lịch trình</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { 
    background: linear-gradient(to right, #a1c4fd, #c2e9fb); 
    font-family: 'Segoe UI', sans-serif; 
}
.form-container {
    max-width: 900px; 
    margin: 40px auto; 
    background: #fff; 
    padding: 30px;
    border-radius: 20px; 
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    border-top: 8px solid #5a5afc;
}
.form-header { 
    font-weight: 700; 
    color: #5a5afc; 
    margin-bottom: 25px; 
}
.form-section { 
    padding: 15px 20px; 
    margin-bottom: 20px; 
    border-radius: 12px; 
    background: linear-gradient(to right, #fdfbfb, #ebedee); 
    box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
}
.btn-primary { 
    background: linear-gradient(45deg,#5a5afc,#fc5a8d); 
    border: none; 
}
.btn-primary:hover { 
    background: linear-gradient(45deg,#fc5a8d,#5a5afc); 
}
.btn-secondary { background: #6c757d; border: none; }
</style>
</head>

<body>

<div class="form-container">
  <h3 class="form-header"><i class="bi bi-calendar-plus"></i> Thêm lịch trình</h3>

  <form action="index.php?act=schedule-store" method="post">

    <div class="row g-3">

      <div class="col-md-6 form-section">
        <label class="form-label">Chọn Tour</label>
        <select name="tour_id" id="tour_id" class="form-select" required>
            <option value="">-- Chọn tour --</option>
            <?php foreach($listTour as $tour): ?>
                <option value="<?= $tour['id'] ?>"><?= $tour['title'] ?></option>
            <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Ngày & giờ khởi hành</label>
        <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" required>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Điểm tập trung</label>
        <input type="text" name="meeting_point" id="meeting_point" class="form-control" required>
      </div>

      <div class="col-md-6 form-section">
        <label class="form-label">Số chỗ còn</label>
        <input type="number" name="seats_available" id="seats_available" class="form-control" min="1" required>
      </div>

      <div class="col-12 form-section">
        <label class="form-label">Ghi chú</label>
        <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
      </div>

      <div class="col-12 d-flex justify-content-between mt-3">
        <a href="index.php?act=schedule" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Thêm lịch trình
        </button>
      </div>

    </div>

  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
