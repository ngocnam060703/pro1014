<div class="container mt-4">

    <h2 class="mb-3">Sửa Hướng dẫn viên</h2>

    <form action="index.php?act=guide-update" method="post">
        <input type="hidden" name="id" value="<?= $guide['id'] ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Tên HDV</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($guide['name']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Cập nhật
        </button>
        <a href="index.php?act=guide" class="btn btn-secondary">Hủy</a>
    </form>

</div>
