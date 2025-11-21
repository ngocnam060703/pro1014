<div class="container mt-4">

    <h2 class="mb-3">Phân công Hướng dẫn viên</h2>
    
    <!-- Nút thêm phân công mới -->
    <a href="index.php?act=guide-assign-create" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Thêm phân công mới
    </a>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Hướng dẫn viên</th>
                <th>Chuyến đi</th>
                <th>Ghi chú</th>
                <th>Ngày phân công</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assigns as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['id']) ?></td>
                    <td><?= htmlspecialchars($a['guide_name']) ?></td>
                    <td><?= htmlspecialchars($a['tour_title']) ?></td>
                    <td><?= htmlspecialchars($a['note']) ?></td>
                    <td><?= htmlspecialchars($a['assigned_at']) ?></td>
                    <td>
                        <a href="index.php?act=guide-assign-edit&id=<?= $a['id'] ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil-square"></i> Sửa
                        </a>
                        <a href="index.php?act=guide-assign-delete&id=<?= $a['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa phân công này?')">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
