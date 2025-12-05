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

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return pdo_query_one($sql, $email);
    }

    public function insertUser($data) {
        // Kiểm tra username đã tồn tại
        if ($this->getUserByUsername($data['username'])) {
            throw new Exception("Tên đăng nhập '{$data['username']}' đã tồn tại!");
        }
        
        // Kiểm tra email đã tồn tại
        if ($this->getUserByEmail($data['email'])) {
            throw new Exception("Email '{$data['email']}' đã tồn tại!");
        }
        
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
        // Kiểm tra username đã tồn tại (trừ user hiện tại)
        if (isset($data['username'])) {
            $existingUser = $this->getUserByUsername($data['username']);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception("Tên đăng nhập '{$data['username']}' đã tồn tại!");
            }
        }
        
        // Kiểm tra email đã tồn tại (trừ user hiện tại)
        if (isset($data['email'])) {
            $existingUser = $this->getUserByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception("Email '{$data['email']}' đã tồn tại!");
            }
        }
        
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
