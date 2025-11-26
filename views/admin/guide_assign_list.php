<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Danh sách phân công HDV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(to right, #fdfbfb, #ebedee);
    font-family: 'Segoe UI', sans-serif;
}
.table-container {
    margin: 40px auto;
    max-width: 1200px;
    background: #fff;
    border-radius: 20px;
    padding: 20px 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
.table-header h3 {
    color: #0d6efd; /* xanh nước biển */
    font-weight: 700;
}
.btn-add {
    background: linear-gradient(45deg,#0d6efd,#0a58ca);
    color: #fff;
    font-weight: 600;
}
.btn-add:hover {
    background: linear-gradient(45deg,#0a58ca,#0d6efd);
}
.table thead th {
    background: #0d6efd; /* xanh nước biển */
    color: #fff;
    border: none;
    text-align: center;
}
.table tbody td {
    vertical-align: middle;
    text-align: center;
}
.table tbody tr:hover {
    background: #e7f1ff;
    transition: 0.3s;
}
.badge-scheduled { background: #ffc107; color: #212529; }
.badge-in_progress { background: #0dcaf0; color: #fff; }
.badge-completed { background: #198754; color: #fff; }
.action-btn {
    margin: 0 2px;
    padding: 6px 12px;
    border-radius: 8px;
}
.action-btn.edit { background: #0d6efd; color: #fff; }
.action-btn.edit:hover { background: #084298; }
.action-btn.delete { background: #dc3545; color: #fff; }
.action-btn.delete:hover { background: #a71d2a; }
</style>
</head>
<body>

<div class="table-container">
  <div class="table-header">
    <h3><i class="bi bi-list-check"></i> Danh sách phân công HDV</h3>
    <a href="index.php?act=guide-assign-create" class="btn btn-add"><i class="bi bi-plus-circle"></i> Thêm phân công</a>
  </div>

  <table class="table table-hover table-bordered align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Hướng dẫn viên</th>
        <th>Tour</th>
        <th>Ngày khởi hành</th>
        <th>Điểm tập trung</th>
        <th>Số khách tối đa</th>
        <th>Ghi chú</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($data)): ?>
        <?php foreach($data as $row): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['guide_name'] ?? 'Chưa có') ?></td>
            <td><?= htmlspecialchars($row['tour_name'] ?? 'Chưa có') ?></td>
            <td><?= htmlspecialchars($row['departure_date']) ?></td>
            <td><?= htmlspecialchars($row['meeting_point']) ?></td>
            <td><?= $row['max_people'] ?></td>
            <td><?= htmlspecialchars($row['note']) ?></td>
            <td>
              <?php 
                $status = $row['status'] ?? 'scheduled';
                $badge = [
                  'scheduled' => 'badge-scheduled',
                  'in_progress' => 'badge-in_progress',
                  'completed' => 'badge-completed'
                ][$status] ?? 'badge-scheduled';
              ?>
              <span class="badge <?= $badge ?>"><?= ucfirst(str_replace('_',' ',$status)) ?></span>
            </td>
            <td>
              <a href="index.php?act=guide-assign-edit&id=<?= $row['id'] ?>" class="action-btn edit"><i class="bi bi-pencil-square"></i></a>
              <a href="index.php?act=guide-assign-delete&id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="action-btn delete"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9">Chưa có phân công HDV nào!</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
