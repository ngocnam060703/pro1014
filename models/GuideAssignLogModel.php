<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class GuideAssignLogModel {

    // Lấy tất cả nhật ký của một phân công
    public function getByAssignmentId($assignment_id) {
        $sql = "SELECT l.*, 
                       old_g.fullname AS old_guide_name,
                       new_g.fullname AS new_guide_name,
                       u.full_name AS changed_by_name
                FROM guide_assign_log l
                LEFT JOIN guides old_g ON l.old_guide_id = old_g.id
                LEFT JOIN guides new_g ON l.new_guide_id = new_g.id
                LEFT JOIN users u ON l.changed_by = u.id
                WHERE l.assignment_id = ?
                ORDER BY l.created_at DESC";
        return pdo_query($sql, $assignment_id);
    }

    // Lấy nhật ký theo ID
    public function find($id) {
        $sql = "SELECT * FROM guide_assign_log WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    // Thêm nhật ký thay đổi
    public function addLog($data) {
        $sql = "INSERT INTO guide_assign_log (
            assignment_id, old_guide_id, new_guide_id,
            old_status, new_status, old_note, new_note,
            change_type, changed_by, change_reason
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return pdo_execute(
            $sql,
            $data['assignment_id'],
            $data['old_guide_id'] ?? null,
            $data['new_guide_id'] ?? null,
            $data['old_status'] ?? null,
            $data['new_status'] ?? null,
            $data['old_note'] ?? null,
            $data['new_note'] ?? null,
            $data['change_type'],
            $data['changed_by'] ?? null,
            $data['change_reason'] ?? null
        );
    }

    // Lấy nhật ký gần đây nhất của một phân công
    public function getLatestLog($assignment_id) {
        $sql = "SELECT * FROM guide_assign_log 
                WHERE assignment_id = ? 
                ORDER BY created_at DESC 
                LIMIT 1";
        return pdo_query_one($sql, $assignment_id);
    }

    // Lấy tất cả các lần thay đổi HDV của một phân công
    public function getGuideChanges($assignment_id) {
        $sql = "SELECT l.*, 
                       old_g.fullname AS old_guide_name,
                       new_g.fullname AS new_guide_name,
                       u.full_name AS changed_by_name
                FROM guide_assign_log l
                LEFT JOIN guides old_g ON l.old_guide_id = old_g.id
                LEFT JOIN guides new_g ON l.new_guide_id = new_g.id
                LEFT JOIN users u ON l.changed_by = u.id
                WHERE l.assignment_id = ? AND l.change_type = 'guide_changed'
                ORDER BY l.created_at DESC";
        return pdo_query($sql, $assignment_id);
    }
}



