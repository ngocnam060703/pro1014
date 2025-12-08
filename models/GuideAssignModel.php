<?php
require_once __DIR__ . "/../commons/function.php";

class GuideAssignModel {

    // Lấy tất cả phân công với thông tin chi tiết
    public function all() {
        $sql = "SELECT ga.*, 
                       g.fullname AS guide_name, 
                       t.title AS tour_title,
                       d.departure_time,
                       d.end_date,
                       d.end_time,
                       d.status AS departure_status,
                       u.full_name AS assigned_by_name,
                       (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') AS booked_guests
                FROM guide_assign ga
                LEFT JOIN guides g ON ga.guide_id = g.id
                LEFT JOIN departures d ON ga.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                LEFT JOIN users u ON ga.assigned_by = u.id
                ORDER BY ga.id DESC";
        return pdo_query($sql);
    }

    // Lấy phân công với bộ lọc và tìm kiếm
    public function getAllWithFilters($filters = []) {
        $where = [];
        $params = [];

        // Lọc theo HDV
        if (!empty($filters['guide_id'])) {
            $where[] = "ga.guide_id = ?";
            $params[] = $filters['guide_id'];
        }

        // Lọc theo tour
        if (!empty($filters['tour_id'])) {
            $where[] = "ga.tour_id = ?";
            $params[] = $filters['tour_id'];
        }

        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $where[] = "ga.status = ?";
            $params[] = $filters['status'];
        }

        // Lọc theo ngày (ngày khởi hành)
        if (!empty($filters['date'])) {
            $where[] = "DATE(d.departure_time) = ?";
            $params[] = $filters['date'];
        }

        // Tìm kiếm theo tên HDV hoặc tên tour
        if (!empty($filters['search'])) {
            $where[] = "(g.fullname LIKE ? OR t.title LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        $sql = "SELECT ga.*, 
                       g.fullname AS guide_name, 
                       t.title AS tour_title,
                       d.departure_time,
                       d.end_date,
                       d.end_time,
                       d.status AS departure_status,
                       u.full_name AS assigned_by_name,
                       (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') AS booked_guests
                FROM guide_assign ga
                LEFT JOIN guides g ON ga.guide_id = g.id
                LEFT JOIN departures d ON ga.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                LEFT JOIN users u ON ga.assigned_by = u.id
                $whereClause
                ORDER BY ga.id DESC";

        return pdo_query($sql, ...$params);
    }

    // Lấy phân công theo ID với thông tin chi tiết
    public function find($id) {
        $sql = "SELECT ga.*, 
                       g.fullname AS guide_name,
                       g.phone AS guide_phone,
                       g.email AS guide_email,
                       t.title AS tour_title,
                       t.description AS tour_description,
                       d.departure_time,
                       d.end_date,
                       d.end_time,
                       d.meeting_point AS departure_meeting_point,
                       d.status AS departure_status,
                       d.total_seats,
                       d.seats_booked,
                       d.seats_available,
                       u.full_name AS assigned_by_name,
                       (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') AS booked_guests
                FROM guide_assign ga
                LEFT JOIN guides g ON ga.guide_id = g.id
                LEFT JOIN departures d ON ga.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                LEFT JOIN users u ON ga.assigned_by = u.id
                WHERE ga.id = ?";
        return pdo_query_one($sql, $id);
    }

    // Lấy lịch trình theo HDV
    public function getByGuide($guide_id) {
        $sql = "SELECT 
                    ga.id,
                    ga.departure_id,
                    t.title AS tour_name,
                    d.departure_time,
                    d.meeting_point
                FROM guide_assign ga
                INNER JOIN departures d ON ga.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE ga.guide_id = ?
                ORDER BY d.departure_time ASC";
        return pdo_query($sql, $guide_id);
    }

    // Lấy lịch trình theo HDV với filter (đồng bộ với admin)
    public function getByGuideWithFilters($guide_id, $filters = []) {
        $where = ["ga.guide_id = ?"];
        $params = [$guide_id];
        
        // Filter theo tháng
        if (!empty($filters['month'])) {
            $monthYear = explode('-', $filters['month']);
            if (count($monthYear) == 2) {
                $where[] = "MONTH(d.departure_time) = ? AND YEAR(d.departure_time) = ?";
                $params[] = (int)$monthYear[1];
                $params[] = (int)$monthYear[0];
            }
        }
        
        // Filter theo ngày
        if (!empty($filters['date'])) {
            $where[] = "DATE(d.departure_time) = ?";
            $params[] = $filters['date'];
        }
        
        // Filter theo trạng thái
        if (!empty($filters['status'])) {
            $where[] = "ga.status = ?";
            $params[] = $filters['status'];
        }
        
        // Search theo tên tour
        if (!empty($filters['search'])) {
            $where[] = "t.title LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT 
                    ga.*,
                    t.id as tour_id,
                    t.title as tour_name,
                    t.tour_code,
                    t.description as tour_description,
                    d.departure_time,
                    d.end_date,
                    d.end_time,
                    d.meeting_point,
                    d.status as departure_status,
                    d.total_seats,
                    d.seats_booked,
                    (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') as booked_guests,
                    DATEDIFF(COALESCE(d.end_date, DATE(d.departure_time)), DATE(d.departure_time)) + 1 as duration_days
                FROM guide_assign ga
                INNER JOIN departures d ON ga.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE $whereClause
                ORDER BY d.departure_time DESC";
        
        return pdo_query($sql, ...$params);
    }

    // Thêm phân công
    public function store($data) {
        $sql = "INSERT INTO guide_assign 
                (guide_id, departure_id, tour_id, departure_date, meeting_point, max_people, note, reason, status, assigned_at, assigned_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        pdo_execute(
            $sql,
            $data["guide_id"],
            $data["departure_id"],
            $data["tour_id"],
            $data["departure_date"],
            $data["meeting_point"],
            $data["max_people"],
            $data["note"] ?? null,
            $data["reason"] ?? null,
            $data["status"],
            $data["assigned_at"],
            $data["assigned_by"] ?? null
        );
        return pdo_last_insert_id();
    }

    // Cập nhật phân công
    public function updateData($id, $data) {
        $sql = "UPDATE guide_assign SET 
                    guide_id = ?, 
                    departure_id = ?, 
                    tour_id = ?, 
                    departure_date = ?, 
                    meeting_point = ?, 
                    max_people = ?, 
                    note = ?, 
                    reason = ?,
                    status = ?
                WHERE id = ?";
        return pdo_execute(
            $sql,
            $data["guide_id"],
            $data["departure_id"],
            $data["tour_id"],
            $data["departure_date"],
            $data["meeting_point"],
            $data["max_people"],
            $data["note"] ?? null,
            $data["reason"] ?? null,
            $data["status"],
            $id
        );
    }

    // Kiểm tra tour đã kết thúc chưa
    public function isTourCompleted($departure_id) {
        $sql = "SELECT status, end_date FROM departures WHERE id = ?";
        $departure = pdo_query_one($sql, $departure_id);
        
        if (!$departure) {
            return false;
        }
        
        // Tour đã hoàn thành nếu status = 'completed' hoặc end_date đã qua
        if ($departure['status'] == 'completed') {
            return true;
        }
        
        if (!empty($departure['end_date'])) {
            $endDate = strtotime($departure['end_date']);
            $today = strtotime(date('Y-m-d'));
            return $endDate < $today;
        }
        
        return false;
    }

    // Kiểm tra phân công có đang chạy không
    public function isAssignmentRunning($id) {
        $sql = "SELECT status FROM guide_assign WHERE id = ?";
        $assignment = pdo_query_one($sql, $id);
        return $assignment && $assignment['status'] == 'in_progress';
    }

    // Kiểm tra phân công đã kết thúc chưa
    public function isAssignmentCompleted($id) {
        $sql = "SELECT status FROM guide_assign WHERE id = ?";
        $assignment = pdo_query_one($sql, $id);
        return $assignment && $assignment['status'] == 'completed';
    }

    // Kiểm tra tour chưa khởi hành
    public function isTourNotStarted($departure_id) {
        $sql = "SELECT departure_time FROM departures WHERE id = ?";
        $departure = pdo_query_one($sql, $departure_id);
        
        if (!$departure) {
            return false;
        }
        
        $departureTime = strtotime($departure['departure_time']);
        $now = time();
        return $departureTime > $now;
    }

    // Xóa
    public function delete($id) {
        $sql = "DELETE FROM guide_assign WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    // Kiểm tra hướng dẫn viên có trùng lịch không
    public function hasScheduleConflict($guideId, $departureId, $excludeAssignId = null) {
        // Lấy thông tin departure hiện tại
        $currentDeparture = pdo_query_one(
            "SELECT departure_time, end_date, end_time FROM departures WHERE id = ?",
            $departureId
        );
        
        if (!$currentDeparture) {
            return ['has_conflict' => false];
        }
        
        $currentStart = strtotime($currentDeparture['departure_time']);
        $currentEnd = !empty($currentDeparture['end_date']) 
            ? strtotime($currentDeparture['end_date'] . ' ' . ($currentDeparture['end_time'] ?? '23:59:59'))
            : $currentStart + 86400; // Nếu không có end_date, mặc định 1 ngày
        
        // Kiểm tra trong guide_assign table
        $excludeClause = $excludeAssignId ? "AND ga.id != ?" : "";
        $params = [$guideId, $departureId];
        if ($excludeAssignId) {
            $params[] = $excludeAssignId;
        }
        
        $sql = "SELECT d.id, d.departure_time, d.end_date, d.end_time, t.title as tour_name
                FROM guide_assign ga
                INNER JOIN departures d ON ga.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE ga.guide_id = ? AND ga.departure_id != ? $excludeClause
                AND ga.status != 'cancelled'";
        
        $existingAssignments = pdo_query($sql, ...$params);
        
        // Kiểm tra overlap với từng departure đã được phân công
        foreach ($existingAssignments as $assignment) {
            $assignStart = strtotime($assignment['departure_time']);
            $assignEnd = !empty($assignment['end_date'])
                ? strtotime($assignment['end_date'] . ' ' . ($assignment['end_time'] ?? '23:59:59'))
                : $assignStart + 86400;
            
            // Kiểm tra overlap: (start1 <= end2) AND (end1 >= start2)
            if ($currentStart <= $assignEnd && $currentEnd >= $assignStart) {
                return [
                    'has_conflict' => true,
                    'conflict_departure' => $assignment,
                    'message' => "Hướng dẫn viên đã được phân công cho tour '{$assignment['tour_name']}' trong khoảng thời gian này."
                ];
            }
        }
        
        // Kiểm tra trong departure_staff_assignments table
        $sql2 = "SELECT d.id, d.departure_time, d.end_date, d.end_time, t.title as tour_name
                 FROM departure_staff_assignments sa
                 INNER JOIN departures d ON sa.departure_id = d.id
                 INNER JOIN tours t ON d.tour_id = t.id
                 WHERE sa.staff_id = ? AND sa.staff_type = 'guide' AND sa.departure_id != ?
                 AND sa.status != 'cancelled'";
        
        $staffAssignments = pdo_query($sql2, $guideId, $departureId);
        
        foreach ($staffAssignments as $assignment) {
            $assignStart = strtotime($assignment['departure_time']);
            $assignEnd = !empty($assignment['end_date'])
                ? strtotime($assignment['end_date'] . ' ' . ($assignment['end_time'] ?? '23:59:59'))
                : $assignStart + 86400;
            
            if ($currentStart <= $assignEnd && $currentEnd >= $assignStart) {
                return [
                    'has_conflict' => true,
                    'conflict_departure' => $assignment,
                    'message' => "Hướng dẫn viên đã được phân công cho tour '{$assignment['tour_name']}' trong khoảng thời gian này."
                ];
            }
        }
        
        return ['has_conflict' => false];
    }
}
