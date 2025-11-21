<h2>Ghi nhật ký hướng dẫn viên</h2>

<form action="index.php?act=guide-journal-store" method="post">

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

    <label>Nội dung nhật ký</label>
    <textarea name="note" class="form-control"></textarea>

    <button class="btn btn-primary mt-3">Lưu</button>
</form>
