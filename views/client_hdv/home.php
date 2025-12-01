<?php include 'views/client_hdv/layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item list-group-item-action" href="index.php?act=hdv_home">Dashboard</a>
            <a class="list-group-item list-group-item-action" href="index.php?act=hdv_lichtrinh">Lịch trình</a>
            <a class="list-group-item list-group-item-action" href="index.php?act=hdv_nhatky">Nhật ký</a>
            <a class="list-group-item list-group-item-action" href="index.php?act=hdv_data">Dữ liệu khác</a>
        </div>
    </div>

    <div class="col-md-9">
        <h3>Xin chào, <?= htmlspecialchars($_SESSION['guide']['fullname']) ?></h3>
        <p>Đây là dashboard dành cho Hướng dẫn viên.</p>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Tóm tắt công việc</h5>

                <p class="card-text">
                    Số tour được giao:
                    <strong><?= isset($count_tours) ? $count_tours : 0 ?></strong>
                </p>

                <p class="card-text">
                    Số nhật ký đã gửi:
                    <strong><?= isset($count_logs) ? $count_logs : 0 ?></strong>
                </p>
            </div>
        </div>

    </div>
</div>

<?php include 'views/client_hdv/layout/footer.php'; ?>
