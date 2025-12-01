<?php
require_once __DIR__ . "/../commons/function.php";

class GuideAssignModel {

    public function all() {
        $sql = "SELECT ga.*, g.fullname AS guide_name, t.title AS tour_title
                FROM guide_assign ga
                LEFT JOIN guides g ON ga.guide_id = g.id
                LEFT JOIN tours t ON ga.tour_id = t.id
                ORDER BY ga.id DESC";
        return pdo_query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM guide_assign WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function store($data) {
        $sql = "INSERT INTO guide_assign 
                (guide_id, tour_id, departure_date, meeting_point, max_people, note, status, assigned_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute($sql,
            $data['guide_id'],
            $data['tour_id'],
            $data['departure_date'],
            $data['meeting_point'],
            $data['max_people'],
            $data['note'],
            $data['status'],
            $data['assigned_at']
        );
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guide_assign SET 
                    guide_id = ?, 
                    tour_id = ?, 
                    departure_date = ?, 
                    meeting_point = ?, 
                    max_people = ?, 
                    note = ?, 
                    status = ?
                WHERE id = ?";
        return pdo_execute($sql,
            $data['guide_id'],
            $data['tour_id'],
            $data['departure_date'],
            $data['meeting_point'],
            $data['max_people'],
            $data['note'],
            $data['status'],
            $id
        );
    }

    public function delete($id) {
        $sql = "DELETE FROM guide_assign WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}