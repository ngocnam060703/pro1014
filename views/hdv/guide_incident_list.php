<h2>Danh sách sự cố hướng dẫn viên</h2>

<a class="btn btn-primary" href="index.php?act=guide-incident-create">+ Báo sự cố</a>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hướng dẫn viên</th>
            <th>Tour</th>
            <th>Loại sự cố</th>
            <th>Mức độ</th>
            <th>Ảnh</th>
            <th>Báo lúc</th>
            <th>Chức năng</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($data as $i): ?>
        <tr>
            <td><?= $i["id"] ?></td>
            <td><?= $i["guide_name"] ?></td>
            <td><?= $i["departure_name"] ?></td>
            <td><?= $i["incident_type"] ?></td>
            <td><?= $i["severity"] ?></td>
            <td>
                <?php if ($i["photos"]): ?>
                    <img src="<?= $i["photos"] ?>" width="70">
                <?php endif; ?>
            </td>
            <td><?= $i["reported_at"] ?></td>

            <td>
                <a class="btn btn-warning btn-sm" href="index.php?act=guide-incident-edit&id=<?= $i['id'] ?>">Sửa</a>
                <a onclick="return confirm('Xóa?')" 
                   class="btn btn-danger btn-sm"
                   href="index.php?act=guide-incident-delete&id=<?= $i['id'] ?>">
                    Xóa
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
