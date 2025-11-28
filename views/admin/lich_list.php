<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch khởi hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-4">
    <h3>Lịch Khởi Hành: <?= htmlspecialchars($tour['title']) ?></h3>
    <a href="index.php?act=lich-create&tour_id=<?= $tour['id'] ?>" class="btn btn-success mb-3">Thêm Lịch</a>
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Ngày khởi hành</th>
                <th>Điểm gặp</th>
                <th>Số chỗ</th>
                <th>Ghi chú</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($listLich)): ?>
                <?php foreach($listLich as $lich): ?>
                    <tr>
                        <td><?= $lich['id'] ?></td>
                        <td><?= $lich['departure_time'] ?></td>
                        <td><?= htmlspecialchars($lich['meeting_point']) ?></td>
                        <td><?= $lich['seats_available'] ?></td>
                        <td><?= htmlspecialchars($lich['notes']) ?></td>
                        <td>
                            <a href="index.php?act=lich-edit&id=<?= $lich['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="index.php?act=lich-delete&id=<?= $lich['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa lịch này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Chưa có lịch nào</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php?act=tour" class="btn btn-secondary">Quay lại Tour</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
