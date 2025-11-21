<h2>Sửa nhật ký hướng dẫn viên</h2>

<form action="index.php?act=guide-journal-update" method="post">

    <input type="hidden" name="id" value="<?= $journal['id'] ?>">

    <label>Hướng dẫn viên</label>
    <select name="guide_id" class="form-control">
        <?php foreach ($guides as $g): ?>
            <option value="<?= $g['id'] ?>"
                <?= $journal['guide_id'] == $g['id'] ? 'selected' : '' ?>>
                <?= $g['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Chuyến đi</label>
    <select name="departure_id" class="form-control">
        <?php foreach ($departures as $d): ?>
            <option value="<?= $d['id'] ?>"
                <?= $journal['departure_id'] == $d['id'] ? 'selected' : '' ?>>
                <?= $d['title'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Nội dung nhật ký</label>
    <textarea name="note" class="form-control"><?= $journal['note'] ?></textarea>

    <button class="btn btn-primary mt-3">Cập nhật</button>
</form>
