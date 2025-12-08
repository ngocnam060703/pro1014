<?php
require_once __DIR__ . "/../commons/function.php";

class GuideJournalModel {

    public function all() {
        $sql = "SELECT gj.*, g.fullname AS guide_name, 
                       CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_journal gj
                LEFT JOIN guides g ON gj.guide_id = g.id
                LEFT JOIN departures d ON gj.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                ORDER BY gj.id DESC";

        return pdo_query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM guide_journal WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

   public function store($data) {
    $sql = "INSERT INTO guide_journal 
            (day_number, guide_id, departure_id, note, activities, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())";

    return pdo_execute($sql,
        $data['day_number'] ?? null,
        $data['guide_id'],
        $data['departure_id'],
        $data['note'],
        $data['activities'] ?? null
    );
}

    public function updateData($id, $data) {
    $sql = "UPDATE guide_journal
            SET 
                day_number = ?, 
                guide_id = ?, 
                departure_id = ?, 
                note = ?, 
                activities = ?, 
                updated_at = NOW()
            WHERE id = ?";

    return pdo_execute($sql,
        $data['day_number'] ?? null,
        $data['guide_id'],
        $data['departure_id'],
        $data['note'],
        $data['activities'] ?? null,
        $id
    );
}



    public function delete($id) {
        $sql = "DELETE FROM guide_journal WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    // Lấy nhật ký theo guide_id
    public function getByGuide($guide_id) {
        $sql = "SELECT 
                    gj.*, 
                    t.title AS tour_name,
                    d.departure_time,
                    CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_journal gj
                LEFT JOIN departures d ON gj.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE gj.guide_id = ?
                ORDER BY gj.id DESC";
        return pdo_query($sql, $guide_id);
    }
}
