<?php

class GuideJournalModel {

    public function all() {
        $sql = "SELECT gj.*, g.name AS guide_name, d.title AS departure_name
                FROM guide_journal gj
                JOIN guides g ON gj.guide_id = g.id
                JOIN departures d ON gj.departure_id = d.id
                ORDER BY gj.id DESC";

        return pdo_query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM guide_journal WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function store($data) {
        $sql = "INSERT INTO guide_journal (guide_id, departure_id, note)
                VALUES (?, ?, ?)";
        return pdo_execute($sql,
            $data['guide_id'],
            $data['departure_id'],
            $data['note']
        );
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guide_journal
                SET guide_id=?, departure_id=?, note=?
                WHERE id=?";
        return pdo_execute($sql,
            $data['guide_id'],
            $data['departure_id'],
            $data['note'],
            $id
        );
    }

    public function delete($id) {
        $sql = "DELETE FROM guide_journal WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}
