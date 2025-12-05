<?php
require_once __DIR__ . "/../commons/function.php";

class GuideFeedbackModel {
    
    // Lấy tất cả phản hồi của một HDV
    public function getByGuide($guide_id) {
        $sql = "SELECT 
                    gf.*,
                    t.title AS tour_name,
                    d.departure_time
                FROM guide_feedback gf
                LEFT JOIN departures d ON gf.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE gf.guide_id = ?
                ORDER BY gf.created_at DESC";
        return pdo_query($sql, $guide_id);
    }
    
    // Lấy phản hồi theo departure
    public function getByDeparture($departure_id) {
        $sql = "SELECT * FROM guide_feedback 
                WHERE departure_id = ?
                ORDER BY created_at DESC";
        return pdo_query($sql, $departure_id);
    }
    
    // Thêm phản hồi
    public function store($data) {
        $sql = "INSERT INTO guide_feedback 
                (guide_id, departure_id, feedback_type, provider_name, rating, comment, suggestions)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data['guide_id'],
            $data['departure_id'],
            $data['feedback_type'],
            $data['provider_name'] ?? null,
            $data['rating'] ?? null,
            $data['comment'] ?? null,
            $data['suggestions'] ?? null
        );
    }
    
    // Cập nhật phản hồi
    public function update($id, $data) {
        $sql = "UPDATE guide_feedback 
                SET feedback_type = ?, provider_name = ?, rating = ?, comment = ?, suggestions = ?
                WHERE id = ?";
        return pdo_execute(
            $sql,
            $data['feedback_type'],
            $data['provider_name'] ?? null,
            $data['rating'] ?? null,
            $data['comment'] ?? null,
            $data['suggestions'] ?? null,
            $id
        );
    }
    
    // Xóa phản hồi
    public function delete($id) {
        $sql = "DELETE FROM guide_feedback WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}

