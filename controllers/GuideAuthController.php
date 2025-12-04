<?php
require_once "models/GuideModel.php";

class GuideAuthController {
    private $guide;

    public function __construct() {
        $this->guide = new GuideModel();
    }

    // Hiển thị form login
    public function loginForm() {
        include "views/hdv/login.php";
    }

    // Xử lý login
    public function loginPost() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $account = trim($_POST['account_id'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($account === '' || $password === '') {
            header("Location: index.php?act=hdv_login&error=1");
            exit;
        }

        $guide = $this->guide->findByAccount($account);

        if (!$guide || !password_verify($password, $guide['password'])) {
            header("Location: index.php?act=hdv_login&error=1");
            exit;
        }

        // Lưu session HDV
        $_SESSION['guide_logged_in'] = true;
        unset($guide['password']); // tránh lưu password
        $_SESSION['guide'] = $guide;

        header("Location: index.php?act=hdv_home");
        exit;
    }

    // Hiển thị form đăng ký
    public function registerForm() {
        include "views/hdv/register.php";
    }

    // Xử lý đăng ký
    public function registerPost() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $certificate = trim($_POST['certificate'] ?? '');
        $account_id = trim($_POST['account_id'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($fullname === '' || $account_id === '' || $password === '') {
            $_SESSION['hdv_register_error'] = "Vui lòng điền đầy đủ thông tin bắt buộc.";
            header("Location: index.php?act=hdv_register");
            exit;
        }

        if ($password !== $password_confirm) {
            $_SESSION['hdv_register_error'] = "Mật khẩu xác nhận không khớp.";
            header("Location: index.php?act=hdv_register");
            exit;
        }

        $exists = $this->guide->findByAccount($account_id);
        if ($exists) {
            $_SESSION['hdv_register_error'] = "Tên đăng nhập đã tồn tại.";
            header("Location: index.php?act=hdv_register");
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'fullname' => $fullname,
            'phone' => $phone,
            'email' => $email,
            'certificate' => $certificate,
            'account_id' => $account_id,
            'password' => $password_hash
        ];

        $this->guide->store($data);

        $_SESSION['hdv_register_success'] = "Đăng ký thành công. Bạn có thể đăng nhập.";
header("Location: index.php?act=hdv_login");
        exit;
    }

    // Logout HDV
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        unset($_SESSION['guide_logged_in']);
        unset($_SESSION['guide']);
        header("Location: index.php?act=hdv_login");
        exit;
    }
}