<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class ScheduleModel {

    // Lấy tất cả lịch cùng tên tour
    public function getAll() {
        $sql = "SELECT s.*, t.title AS tour_name 
                FROM departures s
                LEFT JOIN tours t ON s.tour_id = t.id
                ORDER BY s.departure_time DESC";
        return pdo_query($sql);
    }

    // Lấy 1 lịch theo id
    public function getById($id) {
        $sql = "SELECT s.*, t.title AS tour_name
                FROM departures s
                LEFT JOIN tours t ON s.tour_id = t.id
                WHERE s.id = ?";
        return pdo_query_one($sql, $id);
    }

    // Thêm lịch
    public function insert($data) {
        // Xử lý departure_time - có thể là datetime hoặc date+time riêng
        $departureDateTime = null;
        if (!empty($data['departure_date']) && !empty($data['departure_time'])) {
            $departureDateTime = $data['departure_date'] . ' ' . $data['departure_time'];
        } elseif (!empty($data['departure_time'])) {
            $departureDateTime = $data['departure_time'];
        }
        
        $sql = "INSERT INTO departures(
                    tour_id, departure_date, departure_time, end_date, end_time,
                    meeting_point, meeting_address, meeting_instructions,
                    total_seats, seats_available, seats_booked,
                    status, notes
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data["tour_id"] ?? null,
            $data["departure_date"] ?? null,
            $data["departure_time"] ?? null,
            $data["end_date"] ?? null,
            $data["end_time"] ?? null,
            $data["meeting_point"] ?? null,
            $data["meeting_address"] ?? null,
            $data["meeting_instructions"] ?? null,
            $data["total_seats"] ?? 0,
            $data["seats_available"] ?? 0,
            $data["seats_booked"] ?? 0,
            $data["status"] ?? 'scheduled',
            $data["notes"] ?? null
        );
    }

    // Cập nhật lịch
    public function update($id, $data) {
        $sql = "UPDATE departures 
                SET tour_id=?, departure_date=?, departure_time=?, end_date=?, end_time=?,
                    meeting_point=?, meeting_address=?, meeting_instructions=?,
                    total_seats=?, seats_available=?, seats_booked=?,
                    status=?, notes=?
                WHERE id=?";
        return pdo_execute(
            $sql,
            $data["tour_id"] ?? null,
            $data["departure_date"] ?? null,
            $data["departure_time"] ?? null,
            $data["end_date"] ?? null,
            $data["end_time"] ?? null,
            $data["meeting_point"] ?? null,
            $data["meeting_address"] ?? null,
            $data["meeting_instructions"] ?? null,
            $data["total_seats"] ?? 0,
            $data["seats_available"] ?? 0,
            $data["seats_booked"] ?? 0,
            $data["status"] ?? 'scheduled',
            $data["notes"] ?? null,
            $id
        );
    }

    // Xóa lịch
    public function delete($id) {
        // Kiểm tra xem có booking nào đang sử dụng departure này không
        $bookingCheck = pdo_query_one("SELECT COUNT(*) as count FROM bookings WHERE departure_id = ?", $id);
        if ($bookingCheck && $bookingCheck['count'] > 0) {
            throw new Exception("Không thể xóa lịch khởi hành này vì đã có " . $bookingCheck['count'] . " booking đang sử dụng.");
        }
        
        // Xóa lịch khởi hành
        // Các bảng có ON DELETE CASCADE sẽ tự động xóa:
        // - guide_assign (sau khi cập nhật foreign key)
        // - departure_staff_assignments
        // - departure_service_allocations (và các bảng chi tiết)
        $sql = "DELETE FROM departures WHERE id=?";
        return pdo_execute($sql, $id);
    }
    
    // Kiểm tra có thể xóa được không
    public function canDelete($id) {
        $errors = [];
        
        // Kiểm tra bookings
        $bookingCheck = pdo_query_one("SELECT COUNT(*) as count FROM bookings WHERE departure_id = ?", $id);
        if ($bookingCheck && $bookingCheck['count'] > 0) {
            $errors[] = "Có " . $bookingCheck['count'] . " booking đang sử dụng lịch khởi hành này.";
        }
        
        return [
            'can_delete' => empty($errors),
            'errors' => $errors
        ];
    }

    // Lấy danh sách tất cả tour (dùng cho dropdown)
    public function getAllTours() {
        $sql = "SELECT * FROM tours ORDER BY title ASC";
        return pdo_query($sql);
    }
}
