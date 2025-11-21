<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Lịch khởi hành - <?= $tour['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Thêm Lịch khởi hành - <?= $tour['title'] ?></h3>

    <form action="index.php?act=lich-store" method="post">
        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

        <div class="mb-3">
            <label>Ngày & giờ khởi hành</label>
            <input type="datetime-local" name="departure_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Điểm tập trung</label>
            <input type="text" name="meeting_point" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Số chỗ</label>
            <input type="number" name="seats_available" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ghi chú</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Thêm</button>
        <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
