<?php
require_once "models/GuideModel.php";
require_once "models/hdv_model.php"; // model HDV để lấy tour, logs

class GuideController {

    private $guide;

    public function __construct() {
        $this->guide = new GuideModel();
    }

    /* ====================
       ADMIN - LIST
    ==================== */
    public function index() {
        $data = $this->guide->all();
        include "views/admin/guide_list.php";
    }

    /* ====================
       ADMIN - CREATE
    ==================== */
    public function create() {
        include "views/admin/guide_create.php";
    }

    public function store() {
        $password = $_POST['password'] ?? '';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            "fullname" => $_POST["fullname"],
            "phone" => $_POST["phone"],
            "email" => $_POST["email"],
            "certificate" => $_POST["certificate"] ?? null,
            "account_id" => $_POST["account_id"],
            "password" => $password_hash
        ];

        $this->guide->store($data);

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Thêm nhân viên thành công!";
        header("Location: index.php?act=guide");
        exit;
    }

    /* ====================
       ADMIN - EDIT
    ==================== */
    public function edit() {
        $id = $_GET["id"];
        $guide = $this->guide->find($id);
        include "views/admin/guide_edit.php";
    }

    public function update() {
        $id = $_POST["id"];
        $data = [
            "fullname" => $_POST["fullname"],
            "phone" => $_POST["phone"],
            "email" => $_POST["email"],
            "status" => $_POST["certificate"]
        ];

        $this->guide->updateData($id, $data);

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Cập nhật nhân viên thành công!";
        header("Location: index.php?act=guide");
    }

    /* ====================
       ADMIN - DELETE
    ==================== */
    public function delete() {
        $id = $_GET["id"];
        $this->guide->delete($id);

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Xóa nhân viên thành công!";
        header("Location: index.php?act=guide");
    }


    /* ====================
       HDV DASHBOARD - HOME
    ==================== */
    public function home() {
        $guide_id = $_SESSION['guide']['id'] ?? 0;

        // Lấy số tour được giao
        $count_tours = getCountToursByGuide($guide_id);

        // Lấy số nhật ký đã gửi
        $count_logs = getCountLogsByGuide($guide_id);

        include "views/hdv/dashboard.php";
    }

    /* ====================
       HDV LỊCH TRÌNH
    ==================== */
    public function schedule() {
        $guide_id = $_SESSION['guide']['id'] ?? 0;
$schedules = getScheduleByGuide($guide_id); // Hàm trong hdv_model.php
        include "views/hdv/lichtrinh.php";
    }

    /* ====================
       HDV NHẬT KÝ
    ==================== */
    public function journal() {
        $guide_id = $_SESSION['guide']['id'] ?? 0;
        $logs = getLogsByGuide($guide_id); // Hàm trong hdv_model.php
        include "views/hdv/nhatky.php";
    }

    /* ====================
       HDV DỮ LIỆU KHÁC
    ==================== */
    public function data() {
        $guide_id = $_SESSION['guide']['id'] ?? 0;
        // lấy dữ liệu khác nếu cần
        include "views/hdv/data.php";
    }
}