<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class OrderModel {
    // Lấy số đơn đặt hôm nay
    public function getOrdersCountToday() {
        $sql = "SELECT COUNT(*) as total 
                FROM orders 
                WHERE DATE(created_at) = CURDATE()";
        $row = pdo_query_one($sql);
        return $row['total'] ?? 0;
    }

    // Lấy doanh thu hôm nay (chỉ các đơn đã thanh toán)
    public function getRevenueToday() {
        // Sử dụng cột total_price theo database hiện tại
        $sql = "SELECT IFNULL(SUM(total_price), 0) as revenue 
                FROM orders 
                WHERE DATE(created_at) = CURDATE() 
                AND status = 'paid'";
        $row = pdo_query_one($sql);
        return $row['revenue'] ?? 0;
    }
}
