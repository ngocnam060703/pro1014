<?php
require_once __DIR__ . "/../commons/function.php";

class CustomerSpecialRequestModel {
    
    // Lấy yêu cầu đặc biệt theo booking
    public function getByBooking($booking_id) {
        $sql = "SELECT * FROM customer_special_requests 
                WHERE booking_id = ?
                ORDER BY created_at DESC";
        return pdo_query($sql, $booking_id);
    }
    
    // Lấy yêu cầu đặc biệt theo departure
    public function getByDeparture($departure_id) {
        $sql = "SELECT 
                    csr.*,
                    b.customer_name,
                    b.customer_phone
                FROM customer_special_requests csr
                INNER JOIN bookings b ON csr.booking_id = b.id
                WHERE b.departure_id = ?
                ORDER BY csr.created_at DESC";
        return pdo_query($sql, $departure_id);
    }
    
    // Thêm yêu cầu đặc biệt
    public function store($data) {
        $sql = "INSERT INTO customer_special_requests 
                (booking_id, request_type, description, status, notes)
                VALUES (?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data['booking_id'],
            $data['request_type'],
            $data['description'],
            $data['status'] ?? 'pending',
            $data['notes'] ?? null
        );
    }
    
    // Cập nhật yêu cầu
    public function update($id, $data) {
        $sql = "UPDATE customer_special_requests 
                SET request_type = ?, description = ?, status = ?, notes = ?
                WHERE id = ?";
        return pdo_execute(
            $sql,
            $data['request_type'],
            $data['description'],
            $data['status'],
            $data['notes'] ?? null,
            $id
        );
    }
    
    // Xóa yêu cầu
    public function delete($id) {
        $sql = "DELETE FROM customer_special_requests WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}

