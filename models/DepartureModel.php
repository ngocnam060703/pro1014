<?php
require_once __DIR__ . "/../commons/function.php";

class DepartureModel {

    // Lấy toàn bộ lịch khởi hành
    public function all() {
        $sql = "SELECT 
                    d.id,
                    d.tour_id,
                    d.departure_time AS start_date,
                    t.title AS tour_name
                FROM departures d
                LEFT JOIN tours t ON d.tour_id = t.id
                ORDER BY d.departure_time ASC";
        return pdo_query($sql);
    }

    // Tìm 1 lịch khởi hành theo ID
    public function find($id) {
        $sql = "SELECT 
                    d.id,
                    d.tour_id,
                    d.departure_time AS start_date,
                    t.title AS tour_name
                FROM departures d
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE d.id = ?";
        return pdo_query_one($sql, $id);
    }
}
