<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class TourImageModel {

    public function getImagesByTourId($tour_id) {
        $sql = "SELECT * FROM tour_images WHERE tour_id = ? ORDER BY sort_order ASC, id ASC";
        return pdo_query($sql, $tour_id);
    }

    public function getImageById($id) {
        $sql = "SELECT * FROM tour_images WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function getPrimaryImage($tour_id) {
        $sql = "SELECT * FROM tour_images WHERE tour_id = ? AND is_primary = 1 LIMIT 1";
        return pdo_query_one($sql, $tour_id);
    }

    public function insertImage($data) {
        $sql = "INSERT INTO tour_images(tour_id, image_path, image_type, alt_text, sort_order, is_primary)
                VALUES (?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data["tour_id"],
            $data["image_path"],
            $data["image_type"] ?? 'gallery',
            $data["alt_text"] ?? null,
            $data["sort_order"] ?? 0,
            $data["is_primary"] ?? 0
        );
    }

    public function updateImage($id, $data) {
        $sql = "UPDATE tour_images SET image_path=?, image_type=?, alt_text=?, sort_order=?, is_primary=? WHERE id=?";
        return pdo_execute(
            $sql,
            $data["image_path"],
            $data["image_type"],
            $data["alt_text"] ?? null,
            $data["sort_order"] ?? 0,
            $data["is_primary"] ?? 0,
            $id
        );
    }

    public function deleteImage($id) {
        $sql = "DELETE FROM tour_images WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    public function setPrimaryImage($tour_id, $image_id) {
        // Bỏ primary của tất cả ảnh tour này
        $sql = "UPDATE tour_images SET is_primary = 0 WHERE tour_id = ?";
        pdo_execute($sql, $tour_id);
        
        // Set ảnh được chọn làm primary
        $sql = "UPDATE tour_images SET is_primary = 1 WHERE id = ? AND tour_id = ?";
        return pdo_execute($sql, $image_id, $tour_id);
    }
}

