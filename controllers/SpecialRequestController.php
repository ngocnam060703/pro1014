<?php
require_once "models/CustomerSpecialRequestModel.php";
require_once "models/BookingModel.php";
require_once "models/TourModel.php";

class SpecialRequestController {
    
    private $requestModel;
    private $bookingModel;
    private $tourModel;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->requestModel = new CustomerSpecialRequestModel();
        $this->bookingModel = new BookingModel();
        $this->tourModel = new TourModel();
    }
    
    // Danh sách tất cả yêu cầu đặc biệt
    public function index() {
        $requests = $this->requestModel->getAllWithDetails();
        $pendingCount = $this->requestModel->getPendingCount();
        include "views/admin/special_request_list.php";
    }
    
    // Form thêm yêu cầu
    public function create() {
        $bookings = $this->bookingModel->all();
        include "views/admin/special_request_create.php";
    }
    
    // Lưu yêu cầu mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'booking_id' => $_POST['booking_id'],
                'request_type' => $_POST['request_type'],
                'description' => $_POST['description'],
                'status' => $_POST['status'] ?? 'pending',
                'notes' => $_POST['notes'] ?? null
            ];
            
            try {
                $this->requestModel->store($data);
                $_SESSION['message'] = "Đã thêm yêu cầu đặc biệt thành công!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
        header("Location: index.php?act=special-request");
        exit();
    }
    
    // Form sửa yêu cầu
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $request = $this->requestModel->find($id);
        if (!$request) {
            $_SESSION['error'] = "Không tìm thấy yêu cầu!";
            header("Location: index.php?act=special-request");
            exit();
        }
        include "views/admin/special_request_edit.php";
    }
    
    // Cập nhật yêu cầu
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = [
                'request_type' => $_POST['request_type'],
                'description' => $_POST['description'],
                'status' => $_POST['status'],
                'notes' => $_POST['notes'] ?? null
            ];
            
            try {
                $this->requestModel->update($id, $data);
                $_SESSION['message'] = "Đã cập nhật yêu cầu thành công!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
        header("Location: index.php?act=special-request");
        exit();
    }
    
    // Xóa yêu cầu
    public function delete() {
        $id = $_GET['id'] ?? 0;
        try {
            $this->requestModel->delete($id);
            $_SESSION['message'] = "Đã xóa yêu cầu thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
        }
        header("Location: index.php?act=special-request");
        exit();
    }
    
    // Cập nhật trạng thái nhanh
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'];
            $notes = $_POST['notes'] ?? null;
            
            try {
                $this->requestModel->updateStatus($id, $status, $notes);
                $_SESSION['message'] = "Đã cập nhật trạng thái thành công!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "index.php?act=special-request"));
        exit();
    }
}

