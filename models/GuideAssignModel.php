<?php
require_once __DIR__ . "/../commons/function.php";

class GuideAssignModel {

    // Lấy tất cả phân công
    public function all() {
        $sql = "SELECT ga.*, 
                       g.fullname AS guide_name, 
                       t.title AS tour_title
                FROM guide_assign ga
                LEFT JOIN guides g ON ga.guide_id = g.id
                LEFT JOIN departures d ON ga.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                ORDER BY ga.id DESC";
        return pdo_query($sql);
    }

    // Lấy phân công theo ID
    public function find($id) {
        $sql = "SELECT * FROM guide_assign WHERE id = ?";
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

    // Thêm phân công
    public function store($data) {
        $sql = "INSERT INTO guide_assign 
                (guide_id, departure_id, tour_id, departure_date, meeting_point, max_people, note, status, assigned_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data["guide_id"],
            $data["departure_id"],
            $data["tour_id"],
            $data["departure_date"],
            $data["meeting_point"],
            $data["max_people"],
            $data["note"],
            $data["status"],
            $data["assigned_at"]
        );
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
            $data["note"],
            $data["status"],
            $id
        );
    }

    // Xóa
    public function delete($id) {
        $sql = "DELETE FROM guide_assign WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}
