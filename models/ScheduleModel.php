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

    // Kiểm tra tour có đang chạy không (có departures với status in_progress hoặc đang trong khoảng thời gian)
    public function getActiveDeparturesByTourId($tourId) {
        $sql = "SELECT * FROM departures 
                WHERE tour_id = ? 
                AND (
                    status = 'in_progress' 
                    OR status = 'upcoming'
                    OR (departure_time <= NOW() AND (end_date IS NULL OR end_date >= CURDATE()))
                )";
        return pdo_query($sql, $tourId);
    }

    // Tìm kiếm theo mã lịch trình (ID)
    public function searchById($searchId) {
        // Chuyển đổi sang số nguyên nếu có thể
        $id = is_numeric($searchId) ? (int)$searchId : 0;
        
        $sql = "SELECT 
                    s.*,
                    t.title AS tour_name,
                    (SELECT GROUP_CONCAT(DISTINCT g.fullname SEPARATOR ', ')
                     FROM departure_staff_assignments dsa
                     LEFT JOIN guides g ON dsa.staff_id = g.id
                     WHERE dsa.departure_id = s.id AND dsa.staff_type = 'guide'
                    ) AS guide_names,
                    (SELECT GROUP_CONCAT(DISTINCT 
                        CASE 
                            WHEN sa.service_type = 'transport' THEN CONCAT('Xe: ', COALESCE(td.vehicle_type, ''), IF(td.vehicle_number IS NOT NULL, CONCAT(' - ', td.vehicle_number), ''))
                            WHEN sa.service_type = 'flight' THEN CONCAT('Máy bay: ', COALESCE(fd.airline, ''), IF(fd.flight_number IS NOT NULL, CONCAT(' - ', fd.flight_number), ''))
                            WHEN sa.service_type = 'hotel' THEN CONCAT('Khách sạn: ', COALESCE(hd.hotel_name, ''))
                            ELSE COALESCE(sa.service_name, '')
                        END
                        SEPARATOR '; ')
                     FROM departure_service_allocations sa
                     LEFT JOIN departure_transport_details td ON sa.id = td.service_allocation_id AND sa.service_type = 'transport'
                     LEFT JOIN departure_flight_details fd ON sa.id = fd.service_allocation_id AND sa.service_type = 'flight'
                     LEFT JOIN departure_hotel_details hd ON sa.id = hd.service_allocation_id AND sa.service_type = 'hotel'
                     WHERE sa.departure_id = s.id
                    ) AS vehicles,
                    CASE 
                        WHEN s.end_date IS NOT NULL THEN DATEDIFF(s.end_date, DATE(s.departure_time))
                        ELSE 1
                    END AS days_count,
                    CASE 
                        WHEN s.end_date IS NOT NULL AND DATEDIFF(s.end_date, DATE(s.departure_time)) > 0 
                        THEN DATEDIFF(s.end_date, DATE(s.departure_time)) - 1
                        ELSE 0
                    END AS nights_count
                FROM departures s
                LEFT JOIN tours t ON s.tour_id = t.id
                WHERE s.id = ?
                ORDER BY s.departure_time DESC";
        
        return pdo_query($sql, $id);
    }

    // Lấy tất cả lịch trình với đầy đủ thông tin
    public function getAllWithDetails($filters = []) {
        $where = [];
        $params = [];
        
        // Filter theo tên tour
        if (!empty($filters['tour_id'])) {
            $where[] = "s.tour_id = ?";
            $params[] = $filters['tour_id'];
        }
        
        // Filter theo tháng/năm
        if (!empty($filters['month']) && !empty($filters['year'])) {
            $where[] = "MONTH(s.departure_time) = ? AND YEAR(s.departure_time) = ?";
            $params[] = $filters['month'];
            $params[] = $filters['year'];
        } elseif (!empty($filters['year'])) {
            $where[] = "YEAR(s.departure_time) = ?";
            $params[] = $filters['year'];
        }
        
        // Filter theo trạng thái
        if (!empty($filters['status'])) {
            $where[] = "s.status = ?";
            $params[] = $filters['status'];
        }
        
        // Search theo tên tour
        if (!empty($filters['search'])) {
            $where[] = "t.title LIKE ?";
            $params[] = "%{$filters['search']}%";
        }
        
        // Search theo ngày khởi hành
        if (!empty($filters['search_date'])) {
            $where[] = "DATE(s.departure_time) = ?";
            $params[] = $filters['search_date'];
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $sql = "SELECT 
                    s.*,
                    t.title AS tour_name,
                    (SELECT GROUP_CONCAT(DISTINCT g.fullname SEPARATOR ', ')
                     FROM departure_staff_assignments dsa
                     LEFT JOIN guides g ON dsa.staff_id = g.id
                     WHERE dsa.departure_id = s.id AND dsa.staff_type = 'guide'
                    ) AS guide_names,
                    (SELECT GROUP_CONCAT(DISTINCT 
                        CASE 
                            WHEN sa.service_type = 'transport' THEN CONCAT('Xe: ', COALESCE(td.vehicle_type, ''), IF(td.vehicle_number IS NOT NULL, CONCAT(' - ', td.vehicle_number), ''))
                            WHEN sa.service_type = 'flight' THEN CONCAT('Máy bay: ', COALESCE(fd.airline, ''), IF(fd.flight_number IS NOT NULL, CONCAT(' - ', fd.flight_number), ''))
                            WHEN sa.service_type = 'hotel' THEN CONCAT('Khách sạn: ', COALESCE(hd.hotel_name, ''))
                            ELSE COALESCE(sa.service_name, '')
                        END
                        SEPARATOR '; ')
                     FROM departure_service_allocations sa
                     LEFT JOIN departure_transport_details td ON sa.id = td.service_allocation_id AND sa.service_type = 'transport'
                     LEFT JOIN departure_flight_details fd ON sa.id = fd.service_allocation_id AND sa.service_type = 'flight'
                     LEFT JOIN departure_hotel_details hd ON sa.id = hd.service_allocation_id AND sa.service_type = 'hotel'
                     WHERE sa.departure_id = s.id
                    ) AS vehicles,
                    CASE 
                        WHEN s.end_date IS NOT NULL THEN DATEDIFF(s.end_date, DATE(s.departure_time))
                        ELSE 1
                    END AS days_count,
                    CASE 
                        WHEN s.end_date IS NOT NULL AND DATEDIFF(s.end_date, DATE(s.departure_time)) > 0 
                        THEN DATEDIFF(s.end_date, DATE(s.departure_time)) - 1
                        ELSE 0
                    END AS nights_count
                FROM departures s
                LEFT JOIN tours t ON s.tour_id = t.id
                {$whereClause}
                ORDER BY s.departure_time DESC";
        
        return !empty($params) ? pdo_query($sql, ...$params) : pdo_query($sql);
    }

    // Lấy danh sách lịch trình nhóm theo tour (mỗi tour chỉ hiển thị 1 lần)
    public function getAllGroupedByTour($search = '') {
        if (!empty($search)) {
            $sql = "SELECT 
                        t.id AS tour_id,
                        t.title AS tour_name,
                        COUNT(s.id) AS schedule_count,
                        MIN(s.departure_time) AS earliest_departure,
                        MAX(s.departure_time) AS latest_departure,
                        SUM(COALESCE(s.seats_available, 0)) AS total_seats_available,
                        SUM(COALESCE(s.seats_booked, 0)) AS total_seats_booked,
                        GROUP_CONCAT(s.id ORDER BY s.departure_time DESC SEPARATOR ',') AS schedule_ids
                    FROM tours t
                    LEFT JOIN departures s ON t.id = s.tour_id
                    WHERE t.title LIKE ?
                    GROUP BY t.id, t.title
                    HAVING schedule_count > 0
                    ORDER BY t.title ASC";
            return pdo_query($sql, "%{$search}%");
        } else {
            $sql = "SELECT 
                        t.id AS tour_id,
                        t.title AS tour_name,
                        COUNT(s.id) AS schedule_count,
                        MIN(s.departure_time) AS earliest_departure,
                        MAX(s.departure_time) AS latest_departure,
                        SUM(COALESCE(s.seats_available, 0)) AS total_seats_available,
                        SUM(COALESCE(s.seats_booked, 0)) AS total_seats_booked,
                        GROUP_CONCAT(s.id ORDER BY s.departure_time DESC SEPARATOR ',') AS schedule_ids
                    FROM tours t
                    LEFT JOIN departures s ON t.id = s.tour_id
                    GROUP BY t.id, t.title
                    HAVING schedule_count > 0
                    ORDER BY t.title ASC";
            return pdo_query($sql);
        }
    }

    // Lấy tất cả lịch trình của một tour cụ thể
    public function getByTourId($tourId) {
        $sql = "SELECT s.*, t.title AS tour_name 
                FROM departures s
                LEFT JOIN tours t ON s.tour_id = t.id
                WHERE s.tour_id = ?
                ORDER BY s.departure_time DESC";
        return pdo_query($sql, $tourId);
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
        
        // Chuyển đổi chuỗi rỗng thành NULL cho end_date và end_time
        $endDate = isset($data["end_date"]) && trim($data["end_date"]) !== '' ? $data["end_date"] : null;
        $endTime = isset($data["end_time"]) && trim($data["end_time"]) !== '' ? $data["end_time"] : null;
        
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
            $endDate,
            $endTime,
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
        // Chuyển đổi chuỗi rỗng thành NULL cho end_date và end_time
        $endDate = isset($data["end_date"]) && trim($data["end_date"]) !== '' ? $data["end_date"] : null;
        $endTime = isset($data["end_time"]) && trim($data["end_time"]) !== '' ? $data["end_time"] : null;
        
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
            $endDate,
            $endTime,
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
