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

    public function tourExistsByTitle($title, $excludeId = null) {
        // Chuẩn hóa title: trim và so sánh không phân biệt hoa thường
        $normalizedTitle = trim($title);
        
        if ($excludeId) {
            // So sánh không phân biệt hoa thường
            $sql = "SELECT COUNT(*) as count FROM tours WHERE LOWER(TRIM(title)) = LOWER(?) AND id != ?";
            $result = pdo_query_one($sql, $normalizedTitle, $excludeId);
        } else {
            // So sánh không phân biệt hoa thường
            $sql = "SELECT COUNT(*) as count FROM tours WHERE LOWER(TRIM(title)) = LOWER(?)";
            $result = pdo_query_one($sql, $normalizedTitle);
        }
        return $result['count'] > 0;
    }

    public function tourCodeExists($tourCode, $excludeId = null) {
        if (empty($tourCode)) {
            return false;
        }
        
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM tours WHERE tour_code = ? AND id != ?";
            $result = pdo_query_one($sql, $tourCode, $excludeId);
        } else {
            $sql = "SELECT COUNT(*) as count FROM tours WHERE tour_code = ?";
            $result = pdo_query_one($sql, $tourCode);
        }
        return $result['count'] > 0;
    }

    public function insertTour($data) {
        // Sử dụng adult_price làm giá mặc định nếu không có price
        $price = $data["adult_price"] ?? $data["price"] ?? 0;
        
        $sql = "INSERT INTO tours(tour_code, title, description, itinerary, price, adult_price, child_price, infant_price, surcharge, slots, departure, status, category)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        pdo_execute(
            $sql,
            $data["tour_code"] ?? null,
            $data["title"],
            $data["description"],
            $data["itinerary"],
            $price,
            $data["adult_price"] ?? 0,
            $data["child_price"] ?? 0,
            $data["infant_price"] ?? 0,
            $data["surcharge"] ?? 0,
            $data["slots"],
            $data["departure"],
            $data["status"],
            $data["category"] ?? 'domestic'
        );
        
        // Trả về ID của tour vừa tạo
        return pdo_last_insert_id();
    }

    public function updateTour($id, $data) {
        // Sử dụng adult_price làm giá mặc định nếu không có price
        $price = $data["adult_price"] ?? $data["price"] ?? 0;
        
        $sql = "UPDATE tours SET tour_code=?, title=?, description=?, itinerary=?, price=?, adult_price=?, child_price=?, infant_price=?, surcharge=?, slots=?, departure=?, status=?, category=? WHERE id=?";
        return pdo_execute(
            $sql,
            $data["tour_code"] ?? null,
            $data["title"],
            $data["description"],
            $data["itinerary"],
            $price,
            $data["adult_price"] ?? 0,
            $data["child_price"] ?? 0,
            $data["infant_price"] ?? 0,
            $data["surcharge"] ?? 0,
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
