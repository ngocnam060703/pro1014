<h2>Nhật ký hướng dẫn viên</h2>

<a href="index.php?act=guide-journal-create" class="btn btn-primary">+ Ghi nhật ký</a>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hướng dẫn viên</th>
            <th>Chuyến đi</th>
            <th>Nội dung</th>
            <th>Ngày tạo</th>
            <th>Chức năng</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($data as $j): ?>
            <tr>
                <td><?= $j["id"] ?></td>
                <td><?= $j["guide_name"] ?></td>
                <td><?= $j["departure_name"] ?></td>
                <td><?= $j["note"] ?></td>
                <td><?= $j["created_at"] ?></td>

                <td>
                    <a class="btn btn-warning btn-sm" href="index.php?act=guide-journal-edit&id=<?= $j['id'] ?>">Sửa</a>
                    <a onclick="return confirm('Xóa nhật ký?')" 
                       class="btn btn-danger btn-sm"
                       href="index.php?act=guide-journal-delete&id=<?= $j['id'] ?>">
                       Xóa
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
