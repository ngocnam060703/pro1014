<h2>Phân công hướng dẫn viên</h2>

<a href="index.php?act=guide-assign-create" class="btn btn-primary">+ Phân công</a>

<table class="table table-bordered mt-3">
    <thead>
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
        <?php foreach ($data as $a): ?>
            <tr>
                <td><?= $a["id"] ?></td>
                <td><?= $a["guide_name"] ?></td>
                <td><?= $a["departure_name"] ?></td>
                <td><?= $a["note"] ?></td>
                <td><?= $a["assigned_at"] ?></td>

                <td>
                    <a class="btn btn-warning btn-sm" href="index.php?act=guide-assign-edit&id=<?= $a['id'] ?>">Sửa</a>
                    <a onclick="return confirm('Xóa phân công?')" 
                       class="btn btn-danger btn-sm"
                       href="index.php?act=guide-assign-delete&id=<?= $a['id'] ?>">
                       Xóa
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
