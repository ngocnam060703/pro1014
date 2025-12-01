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
}
