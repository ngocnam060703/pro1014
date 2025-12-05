<?php
require_once "models/UserModel.php";
require_once "models/OrderModel.php";
require_once "models/TourModel.php";

class AdminController {

    private $userModel;
    private $orderModel;
    private $tourModel;

    public function __construct() {

        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->tourModel = new TourModel();

        // Kiểm tra quyền truy cập vào admin (ngoại trừ login)
        $currentAction = $_GET['act'] ?? '';
        $publicActions = ['login', 'loginForm'];

        if (!isset($_SESSION['user']) && !in_array($currentAction, $publicActions)) {
            header("Location: index.php?act=loginForm");
            exit;
        }

        // if (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin' && !in_array($currentAction, $publicActions)) {
        //     echo "<h3 style='color:red;text-align:center;margin-top:30px'>❌ Bạn không có quyền truy cập trang ADMIN</h3>";
        //     exit;
        // }
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

            if ($user['role'] === "admin") {
                $_SESSION['user'] = $user;
                header("Location: index.php?act=dashboard");
                exit;
            } elseif ($user['role'] === "user") {
                $_SESSION['user'] = $user;
                header("Location: index.php?act=hdv_dashboard");
                exit;
            } else {
                $error = "Bạn không có quyền truy cập!";
                include "views/admin/login.php";
                return;
            }
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
        require_once "models/BookingModel.php";
        $bookingModel = new BookingModel();
        
        $totalTours = count($this->tourModel->getAllTours());
        // Sử dụng BookingModel thay vì OrderModel
        $ordersToday = $bookingModel->getOrdersCountToday();
        $revenueToday = $bookingModel->getRevenueToday();
        $totalUsers = count($this->userModel->getAllUsers());
        
        // Lấy dữ liệu cho biểu đồ (đảm bảo luôn có mảng)
        $revenueData = $bookingModel->getRevenueLast7Days() ?: [];
        $ordersData = $bookingModel->getOrdersCountLast7Days() ?: [];
        $topTours = $bookingModel->getTopTours(5) ?: [];
        $statusDistribution = $bookingModel->getBookingStatusDistribution() ?: [];

        include "views/admin/dashboard.php";
    }

    // -----------------------------
    // ACCOUNT MANAGEMENT (CRUD)
    // -----------------------------

    // Danh sách tài khoản
    public function accountList() {
        $users = $this->userModel->getAllUsers();
        include "views/admin/account_list.php";
    }

    // Form thêm tài khoản
    public function accountCreate() {
        include "views/admin/account_add.php";
    }

    // Lưu tài khoản mới
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

    // Form sửa tài khoản
    public function accountEdit() {
        $id = $_GET['id'] ?? 0;
        $user = $this->userModel->getUserById($id);
        include "views/admin/account_edit.php";
    }

    // Cập nhật tài khoản
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

    // Xóa tài khoản
    public function accountDelete() {
        $id = $_GET['id'] ?? 0;
        $this->userModel->deleteUser($id);
        header("Location: index.php?act=account");
        exit;
    }
}
