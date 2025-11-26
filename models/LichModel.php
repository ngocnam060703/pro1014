<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class LichModel {

    public function getAllDepartures() {
        $sql = "SELECT * FROM departures ORDER BY id DESC";
        return pdo_query($sql);
    }

    public function getDepartureById($id) {
        $sql = "SELECT * FROM departures WHERE id=?";
        return pdo_query_one($sql, $id);
    }

    public function getDeparturesByTour($tour_id) {
        $sql = "SELECT * FROM departures WHERE tour_id=? ORDER BY departure_time ASC";
        return pdo_query($sql, $tour_id);
    }

    // Alias để tương thích với code cũ
    public function getLichByTour($tour_id) {
        return $this->getDeparturesByTour($tour_id);
    }

    public function insertDeparture($data) {
        $sql = "INSERT INTO departures(tour_id, departure_time, meeting_point, seats_available, notes)
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

    public function updateDeparture($id, $data) {
        $sql = "UPDATE departures 
                SET tour_id=?, departure_time=?, meeting_point=?, seats_available=?, notes=? 
                WHERE id=?";
        return pdo_execute(
            $sql,
            $data["tour_id"],
            $data["departure_time"],
            $data["meeting_point"],
            $data["seats_available"],
            $data["notes"],
            $id
        );
    }

    public function deleteDeparture($id) {
        $sql = "DELETE FROM departures WHERE id=?";
        return pdo_execute($sql, $id);
    }
}
