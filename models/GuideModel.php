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
        $sql = "INSERT INTO guides (fullname, phone, email, certificate) VALUES (?, ?, ?, ?)";
        return pdo_execute($sql, $data['fullname'], $data['phone'], $data['email'], $data['certificate']);
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guides 
                SET fullname = ?, phone = ?, email = ?, certificate = ?
                WHERE id = ?";
        return pdo_execute($sql, $data['fullname'], $data['phone'], $data['email'], $data['certificate'], $id);
    }

    public function delete($id) {
        $sql = "DELETE FROM guides WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    public function login($username, $password) {
    $sql = "SELECT * FROM guides WHERE account_id = ? AND password = ?";
    return pdo_query_one($sql, $username, $password);
}

}
