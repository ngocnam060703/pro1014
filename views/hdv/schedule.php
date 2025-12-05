<h2>Lịch trình hướng dẫn viên</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Tour</th>
        <th>Ngày</th>
        <th>Ghi chú</th>
    </tr>

    <?php foreach ($schedule as $row) : ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['tour_name'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['notes'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
