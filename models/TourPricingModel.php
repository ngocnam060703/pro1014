<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class TourPricingModel {

    public function getPricingByTourId($tour_id) {
        $sql = "SELECT * FROM tour_pricing WHERE tour_id = ? AND is_active = 1 ORDER BY price_type ASC, start_date ASC";
        return pdo_query($sql, $tour_id);
    }

    public function getAllPricingByTourId($tour_id) {
        $sql = "SELECT * FROM tour_pricing WHERE tour_id = ? ORDER BY price_type ASC, start_date ASC";
        return pdo_query($sql, $tour_id);
    }

    public function getPricingById($id) {
        $sql = "SELECT * FROM tour_pricing WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function getActivePricingByDate($tour_id, $date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        $sql = "SELECT * FROM tour_pricing 
                WHERE tour_id = ? 
                AND is_active = 1 
                AND (start_date IS NULL OR start_date <= ?) 
                AND (end_date IS NULL OR end_date >= ?)
                ORDER BY price_type ASC";
        return pdo_query($sql, $tour_id, $date, $date);
    }

    public function insertPricing($data) {
        $sql = "INSERT INTO tour_pricing(tour_id, price_type, price, currency, start_date, end_date, min_quantity, max_quantity, description, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data["tour_id"],
            $data["price_type"],
            $data["price"],
            $data["currency"] ?? 'VND',
            $data["start_date"] ?? null,
            $data["end_date"] ?? null,
            $data["min_quantity"] ?? 1,
            $data["max_quantity"] ?? null,
            $data["description"] ?? null,
            $data["is_active"] ?? 1
        );
    }

    public function updatePricing($id, $data) {
        $sql = "UPDATE tour_pricing SET price_type=?, price=?, currency=?, start_date=?, end_date=?, min_quantity=?, max_quantity=?, description=?, is_active=? WHERE id=?";
        return pdo_execute(
            $sql,
            $data["price_type"],
            $data["price"],
            $data["currency"] ?? 'VND',
            $data["start_date"] ?? null,
            $data["end_date"] ?? null,
            $data["min_quantity"] ?? 1,
            $data["max_quantity"] ?? null,
            $data["description"] ?? null,
            $data["is_active"] ?? 1,
            $id
        );
    }

    public function deletePricing($id) {
        $sql = "DELETE FROM tour_pricing WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}

