<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class TourItineraryModel {

    // Lấy tất cả lịch trình chi tiết của tour
    public function getByTourId($tourId) {
        $sql = "SELECT * FROM tour_itinerary_detail 
                WHERE tour_id = ? 
                ORDER BY day_number ASC";
        return pdo_query($sql, $tourId);
    }

    // Lấy lịch trình theo ID
    public function find($id) {
        $sql = "SELECT * FROM tour_itinerary_detail WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    // Thêm lịch trình mới
    public function insert($data) {
        $sql = "INSERT INTO tour_itinerary_detail(
                    tour_id, day_number, title, schedule, destinations, 
                    attractions, travel_time, rest_time, meal_time, 
                    description, activities, meals, accommodation, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return pdo_execute(
            $sql,
            $data['tour_id'],
            $data['day_number'],
            $data['title'] ?? null,
            $data['schedule'] ?? null,
            $data['destinations'] ?? null,
            $data['attractions'] ?? null,
            $data['travel_time'] ?? null,
            $data['rest_time'] ?? null,
            $data['meal_time'] ?? null,
            $data['description'] ?? null,
            $data['activities'] ?? null,
            $data['meals'] ?? null,
            $data['accommodation'] ?? null,
            $data['notes'] ?? null
        );
    }

    // Cập nhật lịch trình
    public function update($id, $data) {
        $sql = "UPDATE tour_itinerary_detail SET
                    day_number = ?, title = ?, schedule = ?, destinations = ?,
                    attractions = ?, travel_time = ?, rest_time = ?, meal_time = ?,
                    description = ?, activities = ?, meals = ?, accommodation = ?, notes = ?
                WHERE id = ?";
        
        return pdo_execute(
            $sql,
            $data['day_number'],
            $data['title'] ?? null,
            $data['schedule'] ?? null,
            $data['destinations'] ?? null,
            $data['attractions'] ?? null,
            $data['travel_time'] ?? null,
            $data['rest_time'] ?? null,
            $data['meal_time'] ?? null,
            $data['description'] ?? null,
            $data['activities'] ?? null,
            $data['meals'] ?? null,
            $data['accommodation'] ?? null,
            $data['notes'] ?? null,
            $id
        );
    }

    // Xóa lịch trình
    public function delete($id) {
        $sql = "DELETE FROM tour_itinerary_detail WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    // Xóa tất cả lịch trình của tour
    public function deleteByTourId($tourId) {
        $sql = "DELETE FROM tour_itinerary_detail WHERE tour_id = ?";
        return pdo_execute($sql, $tourId);
    }

    // Lấy số ngày tối đa của tour
    public function getMaxDayNumber($tourId) {
        $sql = "SELECT MAX(day_number) as max_day FROM tour_itinerary_detail WHERE tour_id = ?";
        $result = pdo_query_one($sql, $tourId);
        return (int)($result['max_day'] ?? 0);
    }
}



