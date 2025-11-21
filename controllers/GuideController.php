<?php
require_once "models/GuideModel.php";

class GuideController {

    private $guide;

    public function __construct() {
        $this->guide = new GuideModel();
    }

    // ====================
    // LIST
    // ====================
    public function index() {
        $data = $this->guide->all();
        include "views/admin/guide_list.php";
    }

    // ====================
    // CREATE
    // ====================
    public function create() {
        include "views/admin/guide_create.php";
    }

    public function store() {
        $data = [
            "name" => $_POST["name"],
            "phone" => $_POST["phone"],
            "email" => $_POST["email"],
            "status" => $_POST["status"]
        ];

        $this->guide->store($data);

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Thêm nhân viên thành công!";
        header("Location: index.php?act=guide");
    }

    // ====================
    // EDIT
    // ====================
    public function edit() {
        $id = $_GET["id"];
        $guide = $this->guide->find($id);
        include "views/admin/guide_edit.php";
    }

    public function update() {
        $id = $_POST["id"];
        $data = [
            "name" => $_POST["name"],
            "phone" => $_POST["phone"],
            "email" => $_POST["email"],
            "status" => $_POST["status"]
        ];

        $this->guide->updateData($id, $data);

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Cập nhật nhân viên thành công!";
        header("Location: index.php?act=guide");
    }

    // ====================
    // DELETE
    // ====================
    public function delete() {
        $id = $_GET["id"];
        $this->guide->delete($id);

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Xóa nhân viên thành công!";
        header("Location: index.php?act=guide");
    }
}
