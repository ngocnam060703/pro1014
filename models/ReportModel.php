<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class ReportModel {

    // Lấy tất cả đơn hàng với thông tin tour
    public function getAllOrdersReport() {
        $sql = "SELECT o.id, o.customer_name, o.customer_email, o.customer_phone,
                       o.num_people, o.booking_date, o.status, o.total_price,
                       t.title AS tour_title
                FROM orders o
                LEFT JOIN tours t ON o.tour_id = t.id
                WHERE o.status != 'admin'"; // lọc nếu cần
        return pdo_query($sql);
    }

    // Tổng số đơn hàng
    public function getTotalOrders() {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status != 'admin'";
        $row = pdo_query_one($sql);
        return $row['total'] ?? 0;
    }

    // Tổng doanh thu
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_price) as revenue FROM orders WHERE status != 'admin'";
        $row = pdo_query_one($sql);
        return $row['revenue'] ?? 0;
    }
}
