<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class BookingStatusHistoryModel {

    // Lấy lịch sử thay đổi trạng thái của booking
    public function getHistoryByBookingId($booking_id) {
        $sql = "SELECT h.*, 
                u.full_name as changed_by_name
                FROM booking_status_history h
                LEFT JOIN users u ON h.changed_by = u.id
                WHERE h.booking_id = ?
                ORDER BY h.created_at DESC";
        return pdo_query($sql, $booking_id);
    }

    // Lấy lịch sử theo ID
    public function getHistoryById($id) {
        $sql = "SELECT * FROM booking_status_history WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    // Thêm lịch sử thay đổi trạng thái
    public function addHistory($data) {
        $sql = "INSERT INTO booking_status_history (
            booking_id, old_status, new_status, 
            old_payment_status, new_payment_status,
            changed_by, change_reason, notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        return pdo_execute(
            $sql,
            $data['booking_id'],
            $data['old_status'] ?? null,
            $data['new_status'],
            $data['old_payment_status'] ?? null,
            $data['new_payment_status'] ?? null,
            $data['changed_by'] ?? null,
            $data['change_reason'] ?? null,
            $data['notes'] ?? null
        );
    }

    // Lấy lịch sử gần đây nhất của booking
    public function getLatestHistory($booking_id) {
        $sql = "SELECT * FROM booking_status_history 
                WHERE booking_id = ? 
                ORDER BY created_at DESC 
                LIMIT 1";
        return pdo_query_one($sql, $booking_id);
    }
}

