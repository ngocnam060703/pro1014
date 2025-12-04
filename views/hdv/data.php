<?php
// Dữ liệu demo
$incidents = [
    ['tour' => 'Tour Hạ Long', 'date' => '2025-12-05', 'issue' => 'Khách bị ốm, cần bác sĩ'],
    ['tour' => 'Tour Ninh Bình', 'date' => '2025-12-06', 'issue' => 'Xe trễ, khách phàn nàn'],
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Báo cáo sự cố</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f5f6fa; }
.sidebar { height: 100vh; background: #343a40; padding-top: 20px; }
.sidebar a { color: #ddd; padding: 12px; display: block; text-decoration: none; }
.sidebar a:hover { background: #495057; color: #fff; }
.content { padding: 30px; }
</style>
</head>

<body>
<div class="row g-0">
  <div class="col-2 sidebar">
    <h4 class="text-center text-light mb-4">HDV</h4>
    <a href="index.php?act=hdv_home"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=hdv_lichtrinh"><i class="bi bi-calendar-event"></i> Xem lịch HDV</a>
    <a href="index.php?act=hdv_nhatky"><i class="bi bi-journal-text"></i> Nhật ký tour</a>
    <a href="index.php?act=hdv_data"><i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố</a>
    <a href="index.php?act=hdv_logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <div class="col-10 content">
    <h3 class="mb-4">Báo cáo sự cố</h3>

    <table class="table table-striped table-bordered bg-white">
      <thead>
        <tr>
          <th>#</th>
          <th>Tour</th>
          <th>Ngày</th>
          <th>Sự cố</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($incidents as $i => $item): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= $item['tour'] ?></td>
          <td><?= $item['date'] ?></td>
          <td><?= $item['issue'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
