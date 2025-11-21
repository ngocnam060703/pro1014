<div class="container mt-4">

    <h2 class="mb-3">Danh sách Hướng dẫn viên</h2>

    <a href="index.php?act=guide-create" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Thêm HDV mới
    </a>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên HDV</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($guides)): ?>
                <?php foreach ($guides as $g): ?>
                    <tr>
                        <td><?= htmlspecialchars($g['id']) ?></td>
                        <td><?= htmlspecialchars($g['name']) ?></td>
                        <td>
                            <a href="index.php?act=guide-edit&id=<?= $g['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <a href="index.php?act=guide-delete&id=<?= $g['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa HDV này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Chưa có HDV nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
