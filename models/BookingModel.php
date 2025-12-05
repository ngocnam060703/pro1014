<?php
require_once __DIR__ . "/../commons/function.php";

class BookingModel {

    // Lấy tất cả booking
    public function all() {
        $sql = "SELECT b.*, t.title AS tour_title
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.id
                ORDER BY b.created_at DESC";
        return pdo_query($sql);
    }

    // Lấy booking theo ID
    public function find($id) {
        $sql = "SELECT b.*, t.title AS tour_title
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.id
                WHERE b.id = ?";
        return pdo_query_one($sql, $id);
    }

    // Cập nhật trạng thái
    public function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        return pdo_execute($sql, $status, $id);
    }

    // Xóa booking
    public function delete($id) {
        $sql = "DELETE FROM bookings WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    // Lấy số đơn đặt hôm nay
    public function getOrdersCountToday() {
        $sql = "SELECT COUNT(*) as total 
                FROM bookings 
                WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'";
        $row = pdo_query_one($sql);
        return $row['total'] ?? 0;
    }

    // Lấy doanh thu hôm nay
    public function getRevenueToday() {
        $sql = "SELECT IFNULL(SUM(total_price), 0) as revenue 
                FROM bookings 
                WHERE DATE(created_at) = CURDATE() 
                AND status != 'cancelled'";
        $row = pdo_query_one($sql);
        return $row['revenue'] ?? 0;
    }

    // Lấy doanh thu 7 ngày gần đây
    public function getRevenueLast7Days() {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    IFNULL(SUM(total_price), 0) as revenue
                FROM bookings
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                AND status != 'cancelled'
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        return pdo_query($sql);
    }

    // Lấy số đơn theo ngày trong 7 ngày gần đây
    public function getOrdersCountLast7Days() {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as count
                FROM bookings
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                AND status != 'cancelled'
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        return pdo_query($sql);
    }

    // Lấy top tour bán chạy
    public function getTopTours($limit = 5) {
        // LIMIT không thể dùng placeholder, phải nối trực tiếp (đã validate là số nguyên)
        $limit = (int)$limit;
        $limit = max(1, min(100, $limit)); // Giới hạn từ 1 đến 100
        
        $sql = "SELECT 
                    t.id,
                    t.title,
                    COUNT(b.id) as booking_count,
                    SUM(b.total_price) as total_revenue
                FROM bookings b
                INNER JOIN tours t ON b.tour_id = t.id
                WHERE b.status != 'cancelled'
                GROUP BY t.id, t.title
                ORDER BY booking_count DESC
                LIMIT " . $limit;
        return pdo_query($sql);
    }

    // Lấy phân bố trạng thái booking
    public function getBookingStatusDistribution() {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM bookings
                GROUP BY status";
        return pdo_query($sql);
    }
}
