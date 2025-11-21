<?php
require_once __DIR__ . "/../commons/env.php";
require_once __DIR__ . "/../commons/function.php";

class UserModel {

    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        return pdo_query($sql);
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return pdo_query_one($sql, $username);
    }

    public function insertUser($data) {
        $sql = "INSERT INTO users(username,password,full_name,phone,email,role,status)
                VALUES(?,?,?,?,?,?,?)";
        pdo_execute($sql, 
            $data['username'], 
            $data['password'], 
            $data['full_name'], 
            $data['phone'], 
            $data['email'], 
            $data['role'], 
            $data['status']
        );
    }

    public function updateUser($id, $data) {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        $sql = "UPDATE users SET " . implode(',', $fields) . " WHERE id = ?";
        pdo_execute($sql, ...$params);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        pdo_execute($sql, $id);
    }
}
