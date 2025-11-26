<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .container { margin-top: 40px; }
        h3 { color: #004080; margin-bottom: 25px; font-weight: bold; }
        .overview { margin-bottom: 30px; }
        .card { border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .card h5 { font-weight: bold; }
        .table thead { background-color: #004080; color: #fff; }
        .table-striped > tbody > tr:nth-of-type(odd) { background-color: #e9f1ff; }

        /* Màu trạng thái */
        .status-Pending { color: #ffc107; font-weight: bold; }
        .status-Confirmed { color: #28a745; font-weight: bold; }
        .status-Cancelled { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h3>Báo cáo đơn hàng</h3>

    <!-- Tổng quan -->
    <div class="row overview">
        <div class="col-md-3 mb-3">
            <div class="card p-3 text-center bg-primary text-white">
                <h5>Tổng đơn hàng</h5>
                <h3><?= $totalOrders ?></h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-3 text-center bg-success text-white">
                <h5>Tổng doanh thu</h5>
                <h3><?= number_format($totalRevenue) ?> đ</h3>
            </div>
        </div>
    </div>

    <!-- Bảng đơn hàng -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Tour</th>
                <th>Số lượng</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?= $report['id'] ?></td>
                        <td><?= htmlspecialchars($report['customer_name']) ?></td>
                        <td><?= htmlspecialchars($report['customer_email']) ?></td>
                        <td><?= htmlspecialchars($report['customer_phone']) ?></td>
                        <td><?= htmlspecialchars($report['tour_title']) ?></td>
                        <td><?= $report['num_people'] ?></td>
                        <td><?= $report['booking_date'] ?></td>

                        <?php 
                            // Đảm bảo giá trị status không bị lỗi class
                            $statusClass = htmlspecialchars($report['status']);
                        ?>
                        <td class="status-<?= $statusClass ?>"><?= $report['status'] ?></td>

                        <td><?= number_format($report['total_price']) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Chưa có đơn hàng nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
