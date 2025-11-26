<?php
require_once "commons/env.php"; // nếu bạn dùng file khác để connect thì thay lại

class ReportModel {

    // Lấy danh sách báo cáo tất cả đơn hàng
    public function getAllOrdersReport() {
        $sql = "
            SELECT 
                o.id, 
                o.customer_name, 
                o.customer_email, 
                o.customer_phone, 
                t.title AS tour_title,
                o.num_people, 
                o.booking_date, 
                o.status, 
                o.total_price
            FROM orders o
            JOIN tours t ON o.tour_id = t.id
            ORDER BY o.id DESC
        ";

        return pdo_query($sql);
    }

    // Tổng đơn hàng
    public function getTotalOrders() {
        $sql = "SELECT COUNT(*) AS total FROM orders";
        $row = pdo_query_one($sql);
        return $row["total"] ?? 0;
    }

    // Tổng doanh thu
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_price) AS revenue FROM orders WHERE status = 'Confirmed'";
        $row = pdo_query_one($sql);
        return $row["revenue"] ?? 0;
    }
}
