<div class="container mt-4">
    <h3>Thêm Lịch Khởi Hành cho Tour: <?= htmlspecialchars($tour['title']) ?></h3>
    <form action="index.php?act=lichStore" method="POST">
        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

        <div class="mb-3">
            <label for="departure_time" class="form-label">Ngày khởi hành</label>
            <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="meeting_point" class="form-label">Điểm gặp</label>
            <input type="text" name="meeting_point" id="meeting_point" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="seats_available" class="form-label">Số chỗ</label>
            <input type="number" name="seats_available" id="seats_available" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Lịch</button>
        <a href="index.php?act=lich&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">Hủy</a>
    </form>
</div>
