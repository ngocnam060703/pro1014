<div class="container mt-4">

    <h2 class="mb-3">Thêm Hướng dẫn viên</h2>

    <form action="index.php?act=guide-store" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Tên HDV</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm
        </button>
        <a href="index.php?act=guide" class="btn btn-secondary">Hủy</a>
    </form>

</div>
