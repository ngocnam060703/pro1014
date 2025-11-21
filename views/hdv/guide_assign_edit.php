<h2>Sửa phân công hướng dẫn viên</h2>

<form action="index.php?act=guide-assign-update" method="post">

    <input type="hidden" name="id" value="<?= $assign['id'] ?>">

    <label>Hướng dẫn viên</label>
    <select name="guide_id" class="form-control">
        <?php foreach ($guides as $g): ?>
            <option value="<?= $g['id'] ?>" 
                    <?= $assign['guide_id']==$g['id']?'selected':'' ?>>
                <?= $g['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Chuyến đi</label>
    <select name="departure_id" class="form-control">
        <?php foreach ($departures as $d): ?>
            <option value="<?= $d['id'] ?>" 
                    <?= $assign['departure_id']==$d['id']?'selected':'' ?>>
                <?= $d['title'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Ghi chú</label>
    <textarea name="note" class="form-control"><?= $assign['note'] ?></textarea>

    <button class="btn btn-primary mt-3">Cập nhật</button>
</form>
