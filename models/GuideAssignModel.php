<?php

class GuideAssignModel {

    public function all() {
        $sql = "SELECT ga.*, g.name AS guide_name, d.title AS departure_name
                FROM guide_assign ga
                JOIN guides g ON ga.guide_id = g.id
                JOIN departures d ON ga.departure_id = d.id
                ORDER BY ga.id DESC";

        return pdo_query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM guide_assign WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function store($data) {
        $sql = "INSERT INTO guide_assign (departure_id, guide_id, note)
                VALUES (?, ?, ?)";
        return pdo_execute($sql,
            $data['departure_id'],
            $data['guide_id'],
            $data['note']
        );
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guide_assign 
                SET departure_id = ?, guide_id = ?, note = ?
                WHERE id = ?";
        return pdo_execute($sql,
            $data['departure_id'],
            $data['guide_id'],
            $data['note'],
            $id
        );
    }

    public function delete($id) {
        $sql = "DELETE FROM guide_assign WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}
