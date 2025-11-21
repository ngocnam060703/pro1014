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

    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Lịch khởi hành của: <?= $tour['title'] ?></h3>

        <a href="index.php?act=lich-create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm lịch khởi hành
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Ngày giờ khởi hành</th>
                        <th>Điểm tập trung</th>
                        <th>Số chỗ còn</th>
                        <th>Ghi chú</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($listLich)): ?>
                    <?php foreach ($listLich as $lich): ?>
                        <tr>
                            <td><?= $lich['id'] ?></td>
                            
                            <td>
                                📅 <?= date('d/m/Y', strtotime($lich['departure_time'])) ?><br>
                                ⏰ <?= $lich['departure_time'] ?>
                            </td>
                            
                            <td><?= $lich['meeting_point'] ?></td>
                            
                            <td><?= $lich['seats_available'] ?></td>
                            
                            <td><?= $lich['notes'] ?></td>
                            
                            <td class="d-flex gap-1">
                                <a href="index.php?act=lich-edit&id=<?= $lich['id'] ?>" 
                                   class="btn btn-warning btn-sm">
                                   <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="index.php?act=lich-delete&id=<?= $lich['id'] ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa lịch khởi hành ID <?= $lich['id'] ?> không?')" 
                                   class="btn btn-danger btn-sm">
                                   <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>

                <?php else: ?>
                    <tr>
<td colspan="6" class="text-center text-muted">
                            Chưa có lịch khởi hành nào
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
<a href="index.php?act=tour" class="btn btn-secondary mt-3">Quay lại danh sách tour</a>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>