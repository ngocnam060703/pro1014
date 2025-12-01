<?php
require_once "models/Database.php";

class GuideModel {
    private $conn;

    public function __construct() {
        $this->conn = pdo_get_connection();
    }

    public function all() {
        $sql = "SELECT * FROM guides ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT * FROM guides WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByAccount($account) {
        $sql = "SELECT * FROM guides WHERE account_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$account]);
        return $stmt->fetch();
    }

    public function store($data) {
        $sql = "INSERT INTO guides (fullname, phone, email, certificate, account_id, password)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['fullname'],
            $data['phone'],
            $data['email'],
            $data['certificate'],
            $data['account_id'],
            $data['password']
        ]);
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guides 
                SET fullname = ?, phone = ?, email = ?, certificate = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['fullname'],
            $data['phone'],
            $data['email'],
            $data['certificate'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM guides WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}