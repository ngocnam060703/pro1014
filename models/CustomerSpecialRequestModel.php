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
                    b.customer_phone,
                    b.customer_email
                FROM customer_special_requests csr
                INNER JOIN bookings b ON csr.booking_id = b.id
                INNER JOIN departures d ON b.tour_id = d.tour_id
                WHERE d.id = ?
                ORDER BY csr.status ASC, csr.created_at DESC";
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
    
    // Lấy tất cả yêu cầu đặc biệt với thông tin đầy đủ
    public function getAllWithDetails() {
        $sql = "SELECT 
                    csr.*,
                    b.customer_name,
                    b.customer_phone,
                    b.customer_email,
                    b.tour_id,
                    t.title AS tour_title,
                    d.departure_time,
                    d.id AS departure_id
                FROM customer_special_requests csr
                INNER JOIN bookings b ON csr.booking_id = b.id
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN departures d ON b.tour_id = d.tour_id
                ORDER BY csr.created_at DESC";
        return pdo_query($sql);
    }
    
    // Lấy yêu cầu đặc biệt theo tour
    public function getByTour($tour_id) {
        $sql = "SELECT 
                    csr.*,
                    b.customer_name,
                    b.customer_phone,
                    b.customer_email
                FROM customer_special_requests csr
                INNER JOIN bookings b ON csr.booking_id = b.id
                WHERE b.tour_id = ?
                ORDER BY csr.status ASC, csr.created_at DESC";
        return pdo_query($sql, $tour_id);
    }
    
    // Lấy số lượng yêu cầu đang chờ xử lý
    public function getPendingCount($departure_id = null) {
        if ($departure_id) {
            $sql = "SELECT COUNT(*) as count
                    FROM customer_special_requests csr
                    INNER JOIN bookings b ON csr.booking_id = b.id
                    INNER JOIN departures d ON b.tour_id = d.tour_id
                    WHERE csr.status = 'pending' AND d.id = ?";
            $result = pdo_query_one($sql, $departure_id);
        } else {
            $sql = "SELECT COUNT(*) as count
                    FROM customer_special_requests
                    WHERE status = 'pending'";
            $result = pdo_query_one($sql);
        }
        return $result['count'] ?? 0;
    }
    
    // Lấy yêu cầu đặc biệt theo ID
    public function find($id) {
        $sql = "SELECT 
                    csr.*,
                    b.customer_name,
                    b.customer_phone,
                    b.customer_email,
                    b.tour_id,
                    t.title AS tour_title
                FROM customer_special_requests csr
                INNER JOIN bookings b ON csr.booking_id = b.id
                LEFT JOIN tours t ON b.tour_id = t.id
                WHERE csr.id = ?";
        return pdo_query_one($sql, $id);
    }
    
    // Cập nhật trạng thái
    public function updateStatus($id, $status, $notes = null) {
        $sql = "UPDATE customer_special_requests 
                SET status = ?, notes = ?
                WHERE id = ?";
        return pdo_execute($sql, $status, $notes, $id);
    }
}

