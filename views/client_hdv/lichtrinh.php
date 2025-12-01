<?php include 'views/client_hdv/layout/header.php'; ?>

<div class="row">
    <!-- Sidebar -->
    <div class="col-md-3">
        <div class="list-group shadow-sm">
            <a class="list-group-item" href="index.php?act=hdv_home">Dashboard</a>
            <a class="list-group-item active" href="index.php?act=hdv_lichtrinh">Lịch trình</a>
            <a class="list-group-item" href="index.php?act=hdv_nhatky">Nhật ký</a>
            <a class="list-group-item" href="index.php?act=hdv_data">Dữ liệu khác</a>
        </div>
    </div>

    <!-- Main content -->
    <div class="col-md-9">
        <h3 class="mb-3">Lịch trình của bạn</h3>

        <?php if(empty($tours)): ?>
            <div class="alert alert-info shadow-sm">Hiện chưa có tour được phân công.</div>
        <?php else: ?>
            <div class="table-responsive shadow-sm">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Tour</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Ngày khởi hành</th>
                            <th>Điểm tập trung</th>
                            <th>Số người tối đa</th>
                            <th>Ghi chú</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tours as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['tour_name']) ?></td>
                                <td><?= htmlspecialchars($t['start_date']) ?></td>
                                <td><?= htmlspecialchars($t['end_date']) ?></td>
                                <td><?= htmlspecialchars($t['departure_date']) ?></td>
                                <td><?= htmlspecialchars($t['meeting_point']) ?></td>
                                <td><?= htmlspecialchars($t['max_people']) ?></td>
                                <td><?= htmlspecialchars($t['note']) ?></td>
                                <td>
                                    <?php
                                        // Badge trạng thái
                                        $status = $t['status'];
                                        $badgeClass = 'secondary';
                                        if($status == 'Đang diễn ra') $badgeClass = 'success';
                                        elseif($status == 'Chưa bắt đầu') $badgeClass = 'warning';
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/client_hdv/layout/footer.php'; ?>
