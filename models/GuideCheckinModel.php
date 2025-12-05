<?php
require_once __DIR__ . "/../commons/function.php";

class GuideCheckinModel {
    
    // Lấy danh sách check-in của một departure
    public function getByDeparture($departure_id) {
        $sql = "SELECT 
                    gc.*,
                    b.customer_name,
                    b.customer_phone,
                    b.num_people
                FROM guide_checkin gc
                INNER JOIN bookings b ON gc.booking_id = b.id
                WHERE gc.departure_id = ?
                ORDER BY gc.checkin_time DESC";
        return pdo_query($sql, $departure_id);
    }
    
    // Check-in một booking
    public function checkin($data) {
        // Kiểm tra xem đã có check-in chưa
        $existing = pdo_query_one(
            "SELECT id FROM guide_checkin WHERE guide_id = ? AND departure_id = ? AND booking_id = ?",
            $data['guide_id'],
            $data['departure_id'],
            $data['booking_id']
        );
        
        if ($existing) {
            // Cập nhật
            $sql = "UPDATE guide_checkin 
                    SET checkin_time = ?, checkin_location = ?, status = ?, notes = ?
                    WHERE id = ?";
            return pdo_execute(
                $sql,
                $data['checkin_time'] ?? date('Y-m-d H:i:s'),
                $data['checkin_location'] ?? '',
                $data['status'] ?? 'checked_in',
                $data['notes'] ?? null,
                $existing['id']
            );
        } else {
            // Thêm mới
            $sql = "INSERT INTO guide_checkin 
                    (guide_id, departure_id, booking_id, checkin_time, checkin_location, status, notes)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            return pdo_execute(
                $sql,
                $data['guide_id'],
                $data['departure_id'],
                $data['booking_id'],
                $data['checkin_time'] ?? date('Y-m-d H:i:s'),
                $data['checkin_location'] ?? '',
                $data['status'] ?? 'checked_in',
                $data['notes'] ?? null
            );
        }
    }
    
    // Cập nhật trạng thái check-in
    public function updateStatus($id, $status, $notes = null) {
        $sql = "UPDATE guide_checkin 
                SET status = ?, notes = ?
                WHERE id = ?";
        return pdo_execute($sql, $status, $notes, $id);
    }
    
    // Lấy thống kê check-in
    public function getCheckinStats($departure_id) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late
                FROM guide_checkin
                WHERE departure_id = ?";
        return pdo_query_one($sql, $departure_id);
    }
}

