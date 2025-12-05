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
        $filters = [];
        if (isset($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        $data = $this->guide->all($filters);
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
            "fullname" => $_POST["fullname"] ?? '',
            "phone" => $_POST["phone"] ?? '',
            "email" => $_POST["email"] ?? '',
            "certificate" => $_POST["certificate"] ?? null,
            "account_id" => $_POST["account_id"] ?? '',
            "password" => $password_hash,
            "date_of_birth" => !empty($_POST["date_of_birth"]) ? $_POST["date_of_birth"] : null,
            "photo" => $_POST["photo"] ?? null,
            "address" => $_POST["address"] ?? null,
            "languages" => $_POST["languages"] ?? null,
            "experience_years" => isset($_POST["experience_years"]) ? (int)$_POST["experience_years"] : 0,
            "experience_description" => $_POST["experience_description"] ?? null,
            "health_status" => $_POST["health_status"] ?? 'good',
            "health_notes" => $_POST["health_notes"] ?? null,
            "specializations" => $_POST["specializations"] ?? null,
            "status" => $_POST["status"] ?? 'active',
            "notes" => $_POST["notes"] ?? null
        ];

        // Xử lý phân loại
        if (isset($_POST["categories"]) && is_array($_POST["categories"])) {
            $data["categories"] = $_POST["categories"];
        }

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
        if (!$guide) {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Không tìm thấy hướng dẫn viên!";
            header("Location: index.php?act=guide");
            exit;
        }
        
        // Lấy phân loại
        $categories = $this->guide->getCategories($id);
        include "views/admin/guide_edit.php";
    }

    public function update() {
        $id = $_POST["id"];
        $data = [
            "fullname" => $_POST["fullname"] ?? '',
            "phone" => $_POST["phone"] ?? '',
            "email" => $_POST["email"] ?? '',
            "certificate" => $_POST["certificate"] ?? null,
            "date_of_birth" => !empty($_POST["date_of_birth"]) ? $_POST["date_of_birth"] : null,
            "photo" => $_POST["photo"] ?? null,
            "address" => $_POST["address"] ?? null,
            "languages" => $_POST["languages"] ?? null,
            "experience_years" => isset($_POST["experience_years"]) ? (int)$_POST["experience_years"] : 0,
            "experience_description" => $_POST["experience_description"] ?? null,
            "health_status" => $_POST["health_status"] ?? 'good',
            "health_notes" => $_POST["health_notes"] ?? null,
            "specializations" => $_POST["specializations"] ?? null,
            "status" => $_POST["status"] ?? 'active',
            "notes" => $_POST["notes"] ?? null
        ];

        // Xử lý phân loại
        if (isset($_POST["categories"]) && is_array($_POST["categories"])) {
            $data["categories"] = $_POST["categories"];
        }

        $this->guide->updateData($id, $data);

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Cập nhật nhân viên thành công!";
        header("Location: index.php?act=guide");
        exit;
    }

    /* ====================
       ADMIN - DETAIL
    ==================== */
    public function detail() {
        $id = $_GET["id"];
        $guide = $this->guide->find($id);
        if (!$guide) {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Không tìm thấy hướng dẫn viên!";
            header("Location: index.php?act=guide");
            exit;
        }
        
        // Lấy các thông tin liên quan
        $categories = $this->guide->getCategories($id);
        $tourHistory = $this->guide->getTourHistory($id, 10);
        $certificates = $this->guide->getCertificates($id);
        
        include "views/admin/guide_detail.php";
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