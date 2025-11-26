<?php
require_once "models/UserModel.php";
require_once "models/OrderModel.php";
require_once "models/TourModel.php";
require_once "models/ReportModel.php"; // thêm ReportModel

class AdminController {

    private $userModel;
    private $orderModel;
    private $tourModel;
    private $reportModel; // thêm biến report

    public function __construct() {
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->tourModel = new TourModel();
        $this->reportModel = new ReportModel(); // khởi tạo report

        // Kiểm tra quyền truy cập vào admin (ngoại trừ login)
        $currentAction = $_GET['act'] ?? '';

        if (!isset($_SESSION['user']) && !in_array($currentAction, ['login', 'loginForm'])) {
            header("Location: index.php?act=loginForm");
            exit;
        }
    }

    // -----------------------------
    // LOGIN / LOGOUT
    // -----------------------------
    public function loginForm() {
        include "views/admin/login.php";
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->getUserByUsername($username);

            if (!$user || ($password !== $user['password'] && !password_verify($password, $user['password']))) {
                $error = "Sai tài khoản hoặc mật khẩu!";
                include "views/admin/login.php";
                return;
            }

            if ($user['role'] !== "admin") {
                $error = "Bạn không có quyền truy cập!";
                include "views/admin/login.php";
                return;
            }

            $_SESSION['user'] = $user;
            header("Location: index.php?act=dashboard");
            exit;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?act=loginForm");
        exit;
    }

    // -----------------------------
    // DASHBOARD
    // -----------------------------
    public function dashboard() {
        $totalTours = count($this->tourModel->getAllTours());
        $ordersToday = $this->orderModel->getOrdersCountToday();
        $revenueToday = $this->orderModel->getRevenueToday();
        $totalUsers = count($this->userModel->getAllUsers());

        include "views/admin/dashboard.php";
    }

    // -----------------------------
    // ACCOUNT MANAGEMENT (CRUD)
    // -----------------------------
    public function accountList() {
        $users = $this->userModel->getAllUsers();
        include "views/admin/account_list.php";
    }

    public function accountCreate() {
        include "views/admin/account_add.php";
    }

    public function accountStore() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];

            $this->userModel->insertUser($data);
            header("Location: index.php?act=account");
            exit;
        }
    }

    public function accountEdit() {
        $id = $_GET['id'] ?? 0;
        $user = $this->userModel->getUserById($id);
        include "views/admin/account_edit.php";
    }

    public function accountUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'username' => $_POST['username'],
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $this->userModel->updateUser($id, $data);
            header("Location: index.php?act=account");
            exit;
        }
    }

    public function accountDelete() {
        $id = $_GET['id'] ?? 0;
        $this->userModel->deleteUser($id);
        header("Location: index.php?act=account");
        exit;
    }

    // -----------------------------
    // REPORT MANAGEMENT
    // -----------------------------
    public function report() {
        $data = $this->reportModel->getAllOrdersReport(); // lấy báo cáo
        include "views/admin/report_list.php";
    }
}
