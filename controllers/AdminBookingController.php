<?php
require_once "models/BookingModel.php";

class AdminBookingController {

    private $model;

    public function __construct() {
        $this->model = new BookingModel();
    }

    // Danh sách booking
    public function bookingList() {
        $bookings = $this->model->all();
        include "views/admin/booking_list.php";
    }

    // Tạo booking mới - hiển thị form
    public function bookingCreate() {
        include "views/admin/booking_create.php";
    }

    // Lưu booking mới
    public function bookingStore() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = $_POST;
        
        // Kiểm tra ngày đặt tour không được quá khứ
        if (!empty($data['booking_date'])) {
            $bookingDate = strtotime($data['booking_date']);
            $now = strtotime(date('Y-m-d'));
            
            if ($bookingDate < $now) {
                $_SESSION['error'] = "Vui lòng chọn ngày đặt tour trong tương lai.";
                header("Location: index.php?act=booking-create");
                exit();
            }
        }
        
        // Kiểm tra ngày khởi hành không được quá khứ
        if (!empty($data['departure_date'])) {
            $departureDate = strtotime($data['departure_date']);
            $now = strtotime(date('Y-m-d'));
            
            if ($departureDate < $now) {
                $_SESSION['error'] = "Vui lòng chọn ngày khởi hành trong tương lai.";
                header("Location: index.php?act=booking-create");
                exit();
            }
        }
        
        // Đảm bảo booking_type có giá trị mặc định
        if (!isset($data['booking_type']) || empty($data['booking_type'])) {
            $data['booking_type'] = 'individual';
        }
        
        // Chuyển các giá trị rỗng thành NULL cho các cột có thể NULL
        if (empty($data['departure_id'])) {
            $data['departure_id'] = null;
        } else {
            $data['departure_id'] = (int)$data['departure_id'];
        }
        
        if (empty($data['departure_date'])) {
            $data['departure_date'] = null;
        }
        
        if (empty($data['customer_address'])) {
            $data['customer_address'] = null;
        }
        
        if (empty($data['company_name'])) {
            $data['company_name'] = null;
        }
        
        if (empty($data['tax_code'])) {
            $data['tax_code'] = null;
        }
        
        if (empty($data['special_requests'])) {
            $data['special_requests'] = null;
        }
        
        if (empty($data['notes'])) {
            $data['notes'] = null;
        }
        
        // Tính tổng số người
        $data['num_people'] = (int)($data['num_adults'] ?? 0) + 
                              (int)($data['num_children'] ?? 0) + 
                              (int)($data['num_infants'] ?? 0);
        
        // Lấy ID người tạo (nếu có session)
        if (isset($_SESSION['user_id'])) {
            $data['created_by'] = $_SESSION['user_id'];
        } else {
            $data['created_by'] = null;
        }
        
        // Xử lý guests nếu có
        if (isset($data['guests']) && is_array($data['guests'])) {
            // Loại bỏ các guest rỗng
            $data['guests'] = array_filter($data['guests'], function($guest) {
                return !empty($guest['full_name']);
            });
        }
        
        // Validate dữ liệu cơ bản
        if (empty($data['tour_id'])) {
            $_SESSION['error'] = 'Vui lòng chọn tour';
            header("Location: index.php?act=booking-create");
            exit;
        }
        
        if (empty($data['customer_name']) || empty($data['customer_email']) || empty($data['customer_phone'])) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin khách hàng';
            header("Location: index.php?act=booking-create");
            exit;
        }
        
        if (empty($data['booking_date'])) {
            $_SESSION['error'] = 'Vui lòng chọn ngày đặt tour';
            header("Location: index.php?act=booking-create");
            exit;
        }
        
        $result = $this->model->createBooking($data);
        
        if ($result['success']) {
            $_SESSION['message'] = $result['message'] . " Mã booking: " . $result['booking_code'];
            header("Location: index.php?act=booking-detail&id=" . $result['booking_id']);
        } else {
            $_SESSION['error'] = $result['message'];
            header("Location: index.php?act=booking-create");
        }
        exit;
    }

    // Kiểm tra chỗ trống (AJAX)
    public function bookingCheckAvailability() {
        header('Content-Type: application/json');
        
        $tour_id = $_GET['tour_id'] ?? 0;
        $departure_id = $_GET['departure_id'] ?? null;
        $departure_date = $_GET['departure_date'] ?? null;
        $num_people = (int)($_GET['num_people'] ?? 1);
        
        $result = $this->model->checkAvailability($tour_id, $departure_id, $departure_date, $num_people);
        echo json_encode($result);
        exit;
    }

    // Tính giá (AJAX)
    public function bookingCalculatePrice() {
        header('Content-Type: application/json');
        
        $tour_id = $_GET['tour_id'] ?? 0;
        $num_adults = (int)($_GET['num_adults'] ?? 0);
        $num_children = (int)($_GET['num_children'] ?? 0);
        $num_infants = (int)($_GET['num_infants'] ?? 0);
        $departure_date = $_GET['departure_date'] ?? null;
        
        $result = $this->model->calculatePrice($tour_id, $num_adults, $num_children, $num_infants, $departure_date);
        echo json_encode(['success' => true, ...$result]);
        exit;
    }

    // Chi tiết booking
    public function bookingDetail() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->model->find($id);
        
        if (!$booking) {
            header("Location: index.php?act=booking");
            exit;
        }
        
        // Lấy danh sách khách nếu là đoàn
        $guests = [];
        if (isset($booking['booking_type']) && $booking['booking_type'] == 'group') {
            $guests = $this->model->getBookingGuests($id);
        }
        
        // Lấy lịch sử thay đổi trạng thái
        require_once "models/BookingStatusHistoryModel.php";
        $historyModel = new BookingStatusHistoryModel();
        $statusHistory = $historyModel->getHistoryByBookingId($id);
        
        include "views/admin/booking_detail.php";
    }

    // Cập nhật trạng thái (cũ - giữ lại để tương thích)
    public function updateStatus() {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'pending';
        $payment_status = $_POST['payment_status'] ?? null;
        $change_reason = $_POST['change_reason'] ?? null;
        $changed_by = $_SESSION['user_id'] ?? null;
        
        $result = $this->model->changeStatus($id, $status, $payment_status, $change_reason, $changed_by);
        
        if ($result['success']) {
            $_SESSION['message'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header("Location: index.php?act=booking-detail&id=" . $id);
        exit;
    }

    // Xóa booking
    public function delete() {
        $id = $_GET['id'] ?? 0;
        $this->model->delete($id);
        header("Location: index.php?act=booking");
        exit;
    }
}
