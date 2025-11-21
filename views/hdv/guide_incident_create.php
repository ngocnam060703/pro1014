<h2>Báo cáo sự cố</h2>

<form method="post" action="index.php?act=guide-incident-store" enctype="multipart/form-data">
    
    <label>Hướng dẫn viên</label>
    <select name="guide_id" class="form-control">
        <?php foreach($guides as $g): ?>
            <option value="<?= $g['id'] ?>"><?= $g['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Chuyến đi</label>
    <select name="departure_id" class="form-control">
        <?php foreach($departures as $d): ?>
            <option value="<?= $d['id'] ?>"><?= $d['title'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Loại sự cố</label>
    <input name="incident_type" class="form-control">

    <label>Mức độ</label>
    <select name="severity" class="form-control">
        <option value="low">Nhẹ</option>
        <option value="medium">Trung bình</option>
        <option value="high">Nghiêm trọng</option>
    </select>

    <label>Mô tả</label>
    <textarea name="description" class="form-control"></textarea>

    <label>Cách xử lý</label>
    <textarea name="solution" class="form-control"></textarea>

    <label>Ảnh</label>
    <input type="file" name="photos" class="form-control">

    <button class="btn btn-primary mt-3">Lưu</button>
</form>
