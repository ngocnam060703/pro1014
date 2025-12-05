<?php
require_once __DIR__ . "/../commons/function.php";

class GuideIncidentModel {

    // ==========================
    // Lấy tất cả sự cố
    // ==========================
    public function all() {
        $sql = "SELECT 
                    gi.*, 
                    g.fullname AS guide_name, 
                    CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_incidents gi
                LEFT JOIN guides g ON gi.guide_id = g.id
                LEFT JOIN departures d ON gi.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                ORDER BY gi.created_at DESC";
        return pdo_query($sql);
    }

    // ==========================
    // Lấy sự cố theo ID (dùng cho edit)
    // ==========================
    public function find($id) {
        $sql = "SELECT 
                    gi.*, 
                    g.fullname AS guide_name, 
                    CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_incidents gi
                LEFT JOIN guides g ON gi.guide_id = g.id
                LEFT JOIN departures d ON gi.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE gi.id = ?";
        return pdo_query_one($sql, $id);
    }

    // ==========================
    // Lấy chi tiết sự cố (detail view)
    // ==========================
    public function findDetail($id) {
        $sql = "SELECT 
                    gi.*, 
                    g.fullname AS guide_name, 
                    g.phone AS guide_phone,
                    g.email AS guide_email,
                    t.title AS tour_title,
                    d.departure_time,
                    CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_incidents gi
                LEFT JOIN guides g ON gi.guide_id = g.id
                LEFT JOIN departures d ON gi.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE gi.id = ?";
        return pdo_query_one($sql, $id);
    }

    // ==========================
    // Thêm sự cố mới
    // ==========================
    public function store($data) {
        $sql = "INSERT INTO guide_incidents 
                (departure_id, guide_id, incident_type, severity, description, solution, photos)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        return pdo_execute(
            $sql,
            $data["departure_id"],
            $data["guide_id"],
            $data["incident_type"],
            $data["severity"],
            $data["description"],
            $data["solution"],
            $data["photos"]
        );
    }

    // ==========================
    // Cập nhật sự cố
    // ==========================
    public function updateData($id, $data) {
        $sql = "UPDATE guide_incidents 
                SET departure_id=?, 
                    guide_id=?, 
                    incident_type=?, 
                    severity=?, 
                    description=?, 
                    solution=?, 
                    photos=? 
                WHERE id=?";

        return pdo_execute(
            $sql,
            $data["departure_id"],
            $data["guide_id"],
            $data["incident_type"],
            $data["severity"],
            $data["description"],
            $data["solution"],
            $data["photos"],
            $id
        );
    }

    // ==========================
    // Xóa sự cố
    // ==========================
    public function delete($id) {
        $sql = "DELETE FROM guide_incidents WHERE id=?";
        return pdo_execute($sql, $id);
    }

    // ==========================
    // Lấy sự cố theo guide_id
    // ==========================
    public function getByGuide($guide_id) {
        $sql = "SELECT 
                    gi.*, 
                    t.title AS tour_name,
                    d.departure_time,
                    CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_incidents gi
                LEFT JOIN departures d ON gi.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE gi.guide_id = ?
                ORDER BY gi.created_at DESC";
        return pdo_query($sql, $guide_id);
    }
}
