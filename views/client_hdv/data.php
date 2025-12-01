<?php include 'views/client_hdv/layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item list-group-item-action" href="index.php?act=hdv_home">Dashboard</a>
            <a class="list-group-item list-group-item-action" href="index.php?act=hdv_lichtrinh">Lịch trình</a>
            <a class="list-group-item list-group-item-action" href="index.php?act=hdv_nhatky">Nhật ký</a>
            <a class="list-group-item list-group-item-action active" href="index.php?act=hdv_data">Dữ liệu khác</a>
        </div>
    </div>

    <div class="col-md-9">
        <h3>Upload file</h3>

        <?php if(!empty($upload_error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($upload_error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Chọn file</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả (tùy chọn)</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button type="submit" name="upload_file" class="btn btn-primary">Upload</button>
        </form>

        <hr>

        <h4>Danh sách file đã upload</h4>
        <?php if(empty($files)): ?>
            <p>Chưa có file nào.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach($files as $file): ?>
                    <li class="list-group-item">
                        <a href="uploads/<?= htmlspecialchars($file['filename']) ?>" target="_blank">
                            <?= htmlspecialchars($file['filename']) ?>
                        </a>
                        <small class="text-muted"> - <?= htmlspecialchars($file['description']) ?></small>
                        <span class="float-end"><?= $file['uploaded_at'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/client_hdv/layout/footer.php'; ?>
