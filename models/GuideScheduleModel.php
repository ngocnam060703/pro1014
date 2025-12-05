<?php
require_once __DIR__ . "/../commons/function.php";

class GuideScheduleModel {
    
    // Lấy lịch trình chi tiết của một tour được phân công
    public function getScheduleDetail($guide_id, $departure_id) {
        $sql = "SELECT 
                    ga.*,
                    t.title AS tour_name,
                    t.description AS tour_description,
                    t.itinerary AS tour_itinerary,
                    d.departure_time,
                    d.meeting_point,
                    d.seats_available,
                    d.notes AS departure_notes
                FROM guide_assign ga
                INNER JOIN departures d ON ga.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE ga.guide_id = ? AND ga.departure_id = ?";
        return pdo_query_one($sql, $guide_id, $departure_id);
    }
    
    // Lấy lịch trình từng ngày của tour
    public function getItineraryDays($tour_id) {
        // Kiểm tra xem bảng có tồn tại không
        try {
            $sql = "SELECT * FROM tour_itinerary_detail 
                    WHERE tour_id = ? 
                    ORDER BY day_number ASC";
            return pdo_query($sql, $tour_id);
        } catch (Exception $e) {
            // Nếu bảng chưa tồn tại, trả về mảng rỗng
            return [];
        }
    }
    
    // Lấy danh sách khách trong đoàn
    public function getTourCustomers($departure_id) {
        $sql = "SELECT 
                    b.*,
                    t.title AS tour_title
                FROM bookings b
                INNER JOIN departures d ON b.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE b.departure_id = ? AND b.status != 'cancelled'
                ORDER BY b.created_at ASC";
        return pdo_query($sql, $departure_id);
    }
}

