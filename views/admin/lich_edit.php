<div class="container mt-4">
    <h3>Sửa Lịch Khởi Hành cho Tour: <?= htmlspecialchars($tour['title']) ?></h3>
    <form action="index.php?act=lichUpdate" method="POST">
        <input type="hidden" name="id" value="<?= $lich['id'] ?>">
        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

        <div class="mb-3">
            <label for="departure_time" class="form-label">Ngày khởi hành</label>
            <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" 
                   value="<?= str_replace(' ', 'T', $lich['departure_time']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="meeting_point" class="form-label">Điểm gặp</label>
            <input type="text" name="meeting_point" id="meeting_point" class="form-control" value="<?= htmlspecialchars($lich['meeting_point']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="seats_available" class="form-label">Số chỗ</label>
            <input type="number" name="seats_available" id="seats_available" class="form-control" value="<?= $lich['seats_available'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea name="notes" id="notes" class="form-control"><?= htmlspecialchars($lich['notes']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật Lịch</button>
        <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">Hủy</a>
    </form>
</div>
