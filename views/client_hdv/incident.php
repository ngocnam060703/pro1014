<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo ngày – Hướng dẫn viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f7fa">

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="?act=hdv_dashboard">HDV – Báo cáo ngày</a>
            <a class="btn btn-light" href="?act=hdv_logout">Đăng xuất</a>
        </div>
    </nav>

    <div class="container mt-4">

        <h3>Báo cáo ngày của <?= $_SESSION['user']['name'] ?></h3>
        <p>Vui lòng điền thông tin bên dưới.</p>

        <form action="?act=hdv_report_post" method="POST" class="card p-4">

    <!-- Chọn ngày -->
    <div class="mb-3">
        <label class="form-label">Ngày báo cáo</label>
        <input type="date" id="date_report" name="date_report" class="form-control" required>
    </div>

    <!-- Tour sẽ load tự động -->
    <div class="mb-3">
        <label class="form-label">Tour trong ngày</label>
        <select name="tour_id" id="tour_select" class="form-control" required>
            <option value="">-- Chọn tour --</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Tổng số khách</label>
        <input type="number" name="total_customers" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Ghi chú</label>
        <textarea name="notes" class="form-control" rows="5" required></textarea>
    </div>

    <button class="btn btn-primary">Gửi báo cáo</button>

</form>

<script>
document.getElementById("date_report").addEventListener("change", function() {
    let selectedDate = this.value;

    fetch("?act=ajax_get_tours&date=" + selectedDate)
        .then(res => res.json())
        .then(data => {
            let select = document.getElementById("tour_select");
            select.innerHTML = '<option value="">-- Chọn tour --</option>';

            data.forEach(t => {
                select.innerHTML += `<option value="${t.id}">${t.tour_name} (${t.start_date})</option>`;
            });
        });
});
</script>


    </div>

</body>
</html>
