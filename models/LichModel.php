<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class LichModel {

    public function getLichByTour($tour_id) {
        $sql = "SELECT * FROM departures WHERE tour_id = ? ORDER BY departure_time ASC";
        return pdo_query($sql, $tour_id);
    }

    public function getLichById($id) {
        $sql = "SELECT * FROM departures WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function insertLich($data) {
        $sql = "INSERT INTO departures(tour_id, departure_time, meeting_point, seats_available ,notes)
                VALUES (?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data["tour_id"],
            $data["departure_time"],
            $data["meeting_point"],
            $data["seats_available"],
            $data["notes"]
        );
    }

    public function updateLich($id, $data) {
        $sql = "UPDATE departures 
                SET departure_time=?, meeting_point=?, seats_available=?, notes=? 
                WHERE id=?";
        return pdo_execute(
            $sql,
            $data["departure_time"],
            $data["meeting_point"],
            $data["seats_available"],
            $data["notes"],
            $id
        );
    }  

    public function deleteLich($id) {
        $sql = "DELETE FROM departures WHERE id=?";
        return pdo_execute($sql, $id);
    }
}
