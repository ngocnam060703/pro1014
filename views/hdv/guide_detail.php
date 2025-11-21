<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ hướng dẫn viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f6fa">

<div class="container mt-4">

    <a href="index.php?act=guide" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>

    <div class="card shadow-sm p-4">

        <!-- Thông tin cá nhân -->
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                     style="width:130px;height:130px;font-size:40px; margin:auto;">
                    <?= strtoupper($guide["name"][0]) ?>
                </div>
                <h4 class="mt-3"><?= $guide["name"] ?></h4>

                <span class="badge 
                    <?= $guide["status"] === "active" ? "bg-success" : "bg-danger" ?>">
                    <?= $guide["status"] === "active" ? "Đang hoạt động" : "Tạm nghỉ" ?>
                </span>
            </div>

            <div class="col-md-9">
                <h4>Thông tin chi tiết</h4>
                <hr>

                <div class="row">
                    <div class="col-6"><strong>Số điện thoại:</strong> <?= $guide["phone"] ?></div>
                    <div class="col-6"><strong>Email:</strong> <?= $guide["email"] ?></div>
                    <div class="col-6 mt-2"><strong>Ngày tạo:</strong> <?= $guide["created_at"] ?></div>
                    <div class="col-6 mt-2"><strong>Cập nhật:</strong> <?= $guide["updated_at"] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Các bảng phụ -->
    <div class="row mt-4 g-4">

        <!-- Nhật ký -->
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5><i class="bi bi-journal-text"></i> Nhật ký làm việc</h5>
                <hr>
                <?php if (empty($journals)): ?>
                    <p class="text-muted">Không có nhật ký.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($journals as $j): ?>
                            <li class="list-group-item">
                                <strong><?= $j["title"] ?></strong>
                                <p class="mb-1"><?= $j["note"] ?></p>
                                <small class="text-muted"><?= $j["created_at"] ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Phân công tour -->
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5><i class="bi bi-map"></i> Phân công Tour</h5>
                <hr>
                <?php if (empty($assigns)): ?>
                    <p class="text-muted">Không có phân công.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($assigns as $a): ?>
                            <li class="list-group-item">
                                <strong><?= $a["title"] ?></strong>
                                <p class="mb-1">Ngày bắt đầu: <?= $a["assigned_date"] ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sự cố -->
        <div class="col-md-12">
            <div class="card p-3 shadow-sm">
                <h5><i class="bi bi-exclamation-circle"></i> Sự cố liên quan</h5>
                <hr>
                <?php if (empty($incidents)): ?>
                    <p class="text-muted">Không có sự cố.</p>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Loại sự cố</th>
                            <th>Mức độ</th>
                            <th>Mô tả</th>
                            <th>Ngày báo cáo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($incidents as $i): ?>
                            <tr>
                                <td><?= $i["title"] ?></td>
                                <td><?= $i["incident_type"] ?></td>
                                <td><?= $i["severity"] ?></td>
                                <td><?= $i["description"] ?></td>
                                <td><?= $i["reported_at"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

</body>
</html>
