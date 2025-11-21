 <!-- ================= BÁO CÁO SỰ CỐ HDV ================= -->
    <h2 class="mt-5 mb-3">Báo cáo sự cố HDV</h2>
    <a href="index.php?act=incident-create" class="btn btn-success mb-2">
        <i class="bi bi-plus-circle"></i> Thêm báo cáo mới
    </a>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>HDV</th>
                <th>Tour</th>
                <th>Loại sự cố</th>
                <th>Mức độ</th>
                <th>Mô tả</th>
                <th>Ảnh</th>
                <th>Ngày báo cáo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($incidents as $i): ?>
                <tr>
                    <td><?= htmlspecialchars($i['guide_name']) ?></td>
                    <td><?= htmlspecialchars($i['departure_name']) ?></td>
                    <td><?= htmlspecialchars($i['incident_type']) ?></td>
                    <td><?= htmlspecialchars($i['severity']) ?></td>
                    <td><?= htmlspecialchars($i['description']) ?></td>
                    <td>
                        <?php if ($i['photos']): ?>
                            <img src="<?= htmlspecialchars($i['photos']) ?>" width="70" class="img-thumbnail">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($i['reported_at']) ?></td>
                    <td>
                        <a href="index.php?act=incident-edit&id=<?= $i['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="index.php?act=incident-delete&id=<?= $i['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>