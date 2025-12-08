<?php
require_once __DIR__ . "/../commons/function.php";

class DepartureStaffAssignmentModel {

    // Lấy tất cả phân bổ nhân sự của một lịch khởi hành
    public function getByDepartureId($departureId) {
        $sql = "SELECT sa.*, 
                g.fullname as guide_name, g.phone as guide_phone
                FROM departure_staff_assignments sa
                LEFT JOIN guides g ON sa.staff_type = 'guide' AND sa.staff_id = g.id
                WHERE sa.departure_id = ?
                ORDER BY sa.staff_type, sa.id";
        return pdo_query($sql, $departureId);
    }

    // Lấy một phân bổ theo ID
    public function find($id) {
        $sql = "SELECT sa.*, 
                g.fullname as guide_name, g.phone as guide_phone
                FROM departure_staff_assignments sa
                LEFT JOIN guides g ON sa.staff_type = 'guide' AND sa.staff_id = g.id
                WHERE sa.id = ?";
        return pdo_query_one($sql, $id);
    }

    // Thêm phân bổ nhân sự
    public function insert($data) {
        $sql = "INSERT INTO departure_staff_assignments 
                (departure_id, staff_type, staff_id, staff_name, staff_phone, 
                 role, responsibilities, start_date, end_date, status, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data['departure_id'],
            $data['staff_type'],
            $data['staff_id'] ?? null,
            $data['staff_name'] ?? null,
            $data['staff_phone'] ?? null,
            $data['role'] ?? null,
            $data['responsibilities'] ?? null,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['status'] ?? 'assigned',
            $data['notes'] ?? null
        );
    }

    // Cập nhật phân bổ nhân sự
    public function update($id, $data) {
        $sql = "UPDATE departure_staff_assignments 
                SET staff_type = ?, staff_id = ?, staff_name = ?, staff_phone = ?,
                    role = ?, responsibilities = ?, start_date = ?, end_date = ?,
                    status = ?, notes = ?
                WHERE id = ?";
        return pdo_execute(
            $sql,
            $data['staff_type'],
            $data['staff_id'] ?? null,
            $data['staff_name'] ?? null,
            $data['staff_phone'] ?? null,
            $data['role'] ?? null,
            $data['responsibilities'] ?? null,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['status'] ?? 'assigned',
            $data['notes'] ?? null,
            $id
        );
    }

    // Xóa phân bổ nhân sự
    public function delete($id) {
        $sql = "DELETE FROM departure_staff_assignments WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    // Lấy danh sách HDV (để chọn)
    public function getAvailableGuides($departureId = null) {
        $sql = "SELECT id, fullname, phone, email, languages, experience_years
                FROM guides
                WHERE status = 'active'
                ORDER BY fullname";
        return pdo_query($sql);
    }

    // Kiểm tra hướng dẫn viên có trùng lịch không (qua departure_staff_assignments)
    public function hasScheduleConflict($guideId, $departureId, $excludeAssignmentId = null) {
        // Lấy thông tin departure hiện tại
        $currentDeparture = pdo_query_one(
            "SELECT departure_time, end_date, end_time FROM departures WHERE id = ?",
            $departureId
        );
        
        if (!$currentDeparture) {
            return false;
        }
        
        $currentStart = strtotime($currentDeparture['departure_time']);
        $currentEnd = !empty($currentDeparture['end_date']) 
            ? strtotime($currentDeparture['end_date'] . ' ' . ($currentDeparture['end_time'] ?? '23:59:59'))
            : $currentStart + 86400; // Nếu không có end_date, mặc định 1 ngày
        
        // Lấy tất cả departure mà hướng dẫn viên đã được phân công qua departure_staff_assignments
        $excludeClause = $excludeAssignmentId ? "AND sa.id != ?" : "";
        $params = [$guideId, 'guide', $departureId];
        if ($excludeAssignmentId) {
            $params[] = $excludeAssignmentId;
        }
        
        $sql = "SELECT d.id, d.departure_time, d.end_date, d.end_time, t.title as tour_name
                FROM departure_staff_assignments sa
                INNER JOIN departures d ON sa.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE sa.staff_id = ? AND sa.staff_type = ? AND sa.departure_id != ? $excludeClause
                AND sa.status != 'cancelled'";
        
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
        
        // Kiểm tra cả trong guide_assign table
        $sql2 = "SELECT d.id, d.departure_time, d.end_date, d.end_time, t.title as tour_name
                 FROM guide_assign ga
                 INNER JOIN departures d ON ga.departure_id = d.id
                 INNER JOIN tours t ON d.tour_id = t.id
                 WHERE ga.guide_id = ? AND ga.departure_id != ?
                 AND ga.status != 'cancelled'";
        
        $guideAssignments = pdo_query($sql2, $guideId, $departureId);
        
        foreach ($guideAssignments as $assignment) {
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

