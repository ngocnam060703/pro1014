<!-- ================= NHẬT KÝ TOUR HDV ================= -->
    <h2 class="mt-5 mb-3">Nhật ký tour HDV</h2>
    <a href="index.php?act=guide-journal-create" class="btn btn-success mb-2">
        <i class="bi bi-plus-circle"></i> Thêm nhật ký mới
    </a>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>HDV</th>
                <th>Tour</th>
                <th>Nội dung</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($journals as $j): ?>
                <tr>
                    <td><?= htmlspecialchars($j['guide_name']) ?></td>
                    <td><?= htmlspecialchars($j['departure_name']) ?></td>
                    <td><?= htmlspecialchars($j['note']) ?></td>
                    <td><?= htmlspecialchars($j['created_at']) ?></td>
                    <td>
                        <a href="index.php?act=guide-journal-edit&id=<?= $j['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="index.php?act=guide-journal-delete&id=<?= $j['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>