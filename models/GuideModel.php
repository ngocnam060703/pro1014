<?php

class GuideModel {

    public function all() {
        $sql = "SELECT * FROM guides ORDER BY id DESC";
        return pdo_query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM guides WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function store($data) {
        $sql = "INSERT INTO guides (name, phone, email, status) VALUES (?, ?, ?, ?)";
        return pdo_execute($sql, $data['name'], $data['phone'], $data['email'], $data['status']);
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guides 
                SET name = ?, phone = ?, email = ?, status = ?
                WHERE id = ?";
        return pdo_execute($sql, $data['name'], $data['phone'], $data['email'], $data['status'], $id);
    }

    public function delete($id) {
        $sql = "DELETE FROM guides WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}
