<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class TourModel {

    public function getAllTours() {
        $sql = "SELECT * FROM tours ORDER BY id DESC";
        return pdo_query($sql);
    }

    public function getTourById($id) {
        $sql = "SELECT * FROM tours WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function insertTour($data) {
        $sql = "INSERT INTO tours(title, description, itinerary, price, slots, departure, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        return pdo_execute(
            $sql,
            $data["title"],
            $data["description"],
            $data["itinerary"],
            $data["price"],
            $data["slots"],
            $data["departure"],
            $data["status"]
        );
    }

    public function updateTour($id, $data) {
        $sql = "UPDATE tours SET title=?, description=?, itinerary=?, price=?, slots=?, departure=?, status=? WHERE id=?";
        return pdo_execute(
            $sql,
            $data["title"],
            $data["description"],
            $data["itinerary"],
            $data["price"],
            $data["slots"],
            $data["departure"],
            $data["status"],
            $id
        );
    }

    public function deleteTour($id) {
        $sql = "DELETE FROM tours WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}
class LichModel {

    // Bảng chuẩn hóa: departures
    // Các trường: id, tour_id, departure_date, departure_time, meeting_point, slots, note

    // Lấy lịch theo tour (Giữ nguyên)
    public function getLichByTour($tour_id) {
        $sql = "SELECT * FROM departures WHERE tour_id = ?";
        return pdo_query($sql, $tour_id);
    }

    // Lấy 1 lịch
    public function getLichById($id) {
        // SỬA: lich_khoi_hanh -> departures
        $sql = "SELECT * FROM departures WHERE id = ?"; 
        return pdo_query_one($sql, $id);
    }

    // Thêm lịch
    public function insertLich($data) {
        // SỬA: lich_khoi_hanh -> departures
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

    // Sửa lịch
    public function updateLich($id, $data) {
        // SỬA: tên bảng và chuẩn hóa các trường dữ liệu
        $sql = "UPDATE departures 
                SET  departure_time=?, meeting_point=?, seats_available=?, notes=? 
                WHERE id=?";

        return pdo_execute($sql,
            
            $data["departure_time"],
            $data["meeting_point"],
            $data["seats_available"],
            $data["notes"],
            $id
        );
    }

    // Xoá lịch
    public function deleteLich($id) {
        // SỬA: lich_khoi_hanh -> departures
        $sql = "DELETE FROM departures WHERE id=?";
        return pdo_execute($sql, $id);
    }
}