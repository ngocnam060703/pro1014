<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class ServiceModel {

    // Lấy tất cả dịch vụ kèm tên chuyến đi
    public function getAll() {
        $sql = "SELECT s.id, s.service_name, s.status, s.note AS notes, t.title AS trip_name
                FROM services s
                LEFT JOIN tours t ON s.trip = t.id
                ORDER BY s.id DESC";
        return pdo_query($sql);
    }

    // Lấy 1 dịch vụ theo id
    public function getById($id) {
        $sql = "SELECT * FROM services WHERE id=?";
        return pdo_query_one($sql, $id);
    }

    // Thêm dịch vụ
    public function insert($data) {
        $sql = "INSERT INTO services (trip, service_name, status, note)
                VALUES (?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $data["trip"],
            $data["service_name"],
            $data["status"],
            $data["note"]
        );
    }

    // Sửa dịch vụ
    public function update($id, $data) {
        $sql = "UPDATE services 
                SET trip=?, service_name=?, status=?, note=?
                WHERE id=?";
        return pdo_execute(
            $sql,
            $data["trip"],
            $data["service_name"],
            $data["status"],
            $data["note"],
            $id
        );
    }

    // Xóa dịch vụ
    public function delete($id) {
        $sql = "DELETE FROM services WHERE id=?";
        return pdo_execute($sql, $id);
    }
}
