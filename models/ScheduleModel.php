<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class ScheduleModel {

    // Lấy tất cả lịch cùng tên tour
    public function getAll() {
        $sql = "SELECT s.*, t.title AS tour_name 
                FROM departures s
                LEFT JOIN tours t ON s.tour_id = t.id
                ORDER BY s.departure_time DESC";
        return pdo_query($sql);
    }

    // Lấy 1 lịch theo id
    public function getById($id) {
        $sql = "SELECT s.*, t.title AS tour_name
                FROM departures s
                LEFT JOIN tours t ON s.tour_id = t.id
                WHERE s.id = ?";
        return pdo_query_one($sql, $id);
    }

    // Thêm lịch
    public function insert($data) {
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

    // Cập nhật lịch
    public function update($id, $data) {
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

    // Xóa lịch
    public function delete($id) {
        $sql = "DELETE FROM departures WHERE id=?";
        return pdo_execute($sql, $id);
    }

    // Lấy danh sách tất cả tour (dùng cho dropdown)
    public function getAllTours() {
        $sql = "SELECT * FROM tours ORDER BY title ASC";
        return pdo_query($sql);
    }
}
