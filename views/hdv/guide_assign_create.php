<h2>Phân công hướng dẫn viên</h2>

<form action="index.php?act=guide-assign-store" method="post">

    <label>Hướng dẫn viên</label>
    <select name="guide_id" class="form-control">
        <?php foreach ($guides as $g): ?>
            <option value="<?= $g['id'] ?>"><?= $g['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Chuyến đi</label>
    <select name="departure_id" class="form-control">
        <?php foreach ($departures as $d): ?>
            <option value="<?= $d['id'] ?>"><?= $d['title'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Ghi chú</label>
    <textarea name="note" class="form-control"></textarea>

    <button class="btn btn-primary mt-3">Lưu phân công</button>
</form>
