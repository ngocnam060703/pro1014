<?php

class GuideIncidentModel {

    public function all() {
        $sql = "SELECT gi.*, g.name AS guide_name, d.title AS departure_name
                FROM guide_incident gi
                JOIN guides g ON gi.guide_id = g.id
                JOIN departures d ON gi.departure_id = d.id
                ORDER BY gi.id DESC";

        return pdo_query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM guide_incident WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function store($data) {
        $sql = "INSERT INTO guide_incident (departure_id, guide_id, incident_type, severity, description, solution, photos)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute($sql,
            $data['departure_id'],
            $data['guide_id'],
            $data['incident_type'],
            $data['severity'],
            $data['description'],
            $data['solution'],
            $data['photos']
        );
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guide_incident
                SET departure_id=?, guide_id=?, incident_type=?, severity=?, description=?, solution=?, photos=?
                WHERE id=?";
        return pdo_execute($sql,
            $data['departure_id'],
            $data['guide_id'],
            $data['incident_type'],
            $data['severity'],
            $data['description'],
            $data['solution'],
            $data['photos'],
            $id
        );
    }

    public function delete($id) {
        $sql = "DELETE FROM guide_incident WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}
