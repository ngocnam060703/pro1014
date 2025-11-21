<div class="container mt-4">

    <!-- ================= LỊCH LÀM VIỆC HDV ================= -->
    <h2 class="mb-3">Lịch làm việc HDV</h2>
    <a href="index.php?act=guide-assign-create" class="btn btn-success mb-2">
        <i class="bi bi-plus-circle"></i> Thêm phân công mới
    </a>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>HDV</th>
                <th>Tour</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Ghi chú</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assigns as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['guide_name']) ?></td>
                    <td><?= htmlspecialchars($a['tour_title']) ?></td>
                    <td><?= htmlspecialchars($a['start_date']) ?></td>
                    <td><?= htmlspecialchars($a['end_date']) ?></td>
                    <td><?= htmlspecialchars($a['note']) ?></td>
                    <td>
                        <a href="index.php?act=guide-assign-edit&id=<?= $a['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="index.php?act=guide-assign-delete&id=<?= $a['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>