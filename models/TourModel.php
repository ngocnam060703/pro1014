<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class TourModel {

    public function getAllTours() {
        $sql = "SELECT * FROM tours ORDER BY id DESC";
        return pdo_query($sql);
    }

    public function getToursByCategory($category) {
        $sql = "SELECT * FROM tours WHERE category = ? ORDER BY id DESC";
        return pdo_query($sql, $category);
    }

    public function getTourById($id) {
        $sql = "SELECT * FROM tours WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function insertTour($data) {
        $sql = "INSERT INTO tours(title, description, itinerary, price, slots, departure, status, category)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data["title"],
            $data["description"],
            $data["itinerary"],
            $data["price"],
            $data["slots"],
            $data["departure"],
            $data["status"],
            $data["category"] ?? 'domestic'
        );
    }

    public function updateTour($id, $data) {
        $sql = "UPDATE tours SET title=?, description=?, itinerary=?, price=?, slots=?, departure=?, status=?, category=? WHERE id=?";
        return pdo_execute(
            $sql,
            $data["title"],
            $data["description"],
            $data["itinerary"],
            $data["price"],
            $data["slots"],
            $data["departure"],
            $data["status"],
            $data["category"] ?? 'domestic',
            $id
        );
    }

    public function deleteTour($id) {
        $sql = "DELETE FROM tours WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}
