<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa lịch trình</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Sửa lịch trình</h3>
    <form action="index.php?act=schedule-update" method="post">
        <input type="hidden" name="id" value="<?= $schedule['id'] ?>">
        <div class="mb-3">
            <label for="tour_id" class="form-label">Chọn Tour</label>
            <select name="tour_id" id="tour_id" class="form-select" required>
                <option value="">-- Chọn tour --</option>
                <?php foreach($listTour as $tour): ?>
                    <option value="<?= $tour['id'] ?>" <?= $schedule['tour_id']==$tour['id'] ? 'selected' : '' ?>>
                        <?= $tour['title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="departure_time" class="form-label">Ngày & giờ khởi hành</label>
            <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" 
                   value="<?= date('Y-m-d\TH:i', strtotime($schedule['departure_time'])) ?>" required>
        </div>
        <div class="mb-3">
            <label for="meeting_point" class="form-label">Điểm tập trung</label>
            <input type="text" name="meeting_point" id="meeting_point" class="form-control" value="<?= $schedule['meeting_point'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="seats_available" class="form-label">Số chỗ còn</label>
            <input type="number" name="seats_available" id="seats_available" class="form-control" value="<?= $schedule['seats_available'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea name="notes" id="notes" class="form-control"><?= $schedule['notes'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật lịch trình</button>
        <a href="index.php?act=schedule" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
