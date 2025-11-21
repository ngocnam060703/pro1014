<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sửa Lịch - <?= $tour['title'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Sửa Lịch - <?= $tour['title'] ?></h3>
    <form action="index.php?act=lich-update" method="post">
        <input type="hidden" name="id" value="<?= $lich['id'] ?>">
        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

        <div class="mb-3">
            <label>Giờ khởi hành</label>
            <input type="datetime-local" name="departure_time" class="form-control" value="<?= $lich['departure_time'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Điểm tập trung</label>
            <input type="text" name="meeting_point" class="form-control" value="<?= $lich['meeting_point'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Số chỗ</label>
            <input type="number" name="slots" class="form-control" value="<?= $lich['seats_available'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Ghi chú</label>
            <textarea name="note" class="form-control"><?= $lich['notes'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">Hủy</a>
    </form>
</div>
</body>
</html>
