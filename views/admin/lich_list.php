<div class="container mt-4">
    <h3>Lịch Khởi Hành: <?= htmlspecialchars($tour['title']) ?></h3>
    <a href="index.php?act=lichCreate&tour_id=<?= $tour['id'] ?>" class="btn btn-success mb-3">Thêm Lịch</a>
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
                            <a href="index.php?act=lichEdit&id=<?= $lich['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="index.php?act=lichDelete&id=<?= $lich['id'] ?>" class="btn btn-sm btn-danger"
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
