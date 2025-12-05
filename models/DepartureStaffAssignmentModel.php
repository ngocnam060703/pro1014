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
}

