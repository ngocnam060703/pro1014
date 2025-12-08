<?php
require_once "models/ScheduleModel.php";
require_once "models/DepartureStaffAssignmentModel.php";
require_once "models/DepartureServiceAllocationModel.php";

class ScheduleController {
    private $model;
    private $staffModel;
    private $serviceModel;

    public function __construct() {
        $this->model = new ScheduleModel();
        $this->staffModel = new DepartureStaffAssignmentModel();
        $this->serviceModel = new DepartureServiceAllocationModel();
    }

    // Danh sách
    public function scheduleList() {
        // Tìm kiếm theo mã lịch trình (ID)
        $searchId = !empty($_GET['search_id']) ? trim($_GET['search_id']) : '';
        
        if (!empty($searchId)) {
            // Nếu có mã lịch trình, tìm kiếm theo ID
            $listSchedule = $this->model->searchById($searchId);
        } else {
            // Nếu không có, hiển thị tất cả
            $listSchedule = $this->model->getAllWithDetails([]);
        }
        
        include "views/admin/Schedule_list.php";
    }

    // Form thêm
    public function scheduleCreate() {
        $listTour = $this->model->getAllTours();
        include "views/admin/Schedule_create.php";
    }

    // Xử lý thêm
    public function scheduleStore() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = $_POST;
        
        // Kiểm tra ngày khởi hành không được quá khứ
        if (!empty($data['departure_time'])) {
            $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
            $departureDateTime = strtotime($data['departure_time']);
            $now = time();
            
            if ($departureDateTime < $now) {
                $_SESSION['error'] = "Vui lòng chọn ngày khởi hành trong tương lai.";
                header("Location: index.php?act=schedule-create");
                exit();
            }
        }
        
        // Kiểm tra ngày kết thúc phải >= ngày hiện tại và >= ngày khởi hành
        if (!empty($data['end_date'])) {
            $endDate = strtotime($data['end_date']);
            $now = strtotime(date('Y-m-d')); // So sánh chỉ phần ngày
            
            if ($endDate < $now) {
                $_SESSION['error'] = "Ngày kết thúc phải >= ngày hiện tại!";
                header("Location: index.php?act=schedule-create");
                exit();
            }
            
            if (!empty($data['departure_time'])) {
                $departureDate = strtotime(date('Y-m-d', strtotime($data['departure_time'])));
                if ($endDate < $departureDate) {
                    $_SESSION['error'] = "Ngày kết thúc phải >= ngày khởi hành!";
                    header("Location: index.php?act=schedule-create");
                    exit();
                }
            }
        }
        
        // Kiểm tra tổng số chỗ (7-50 chỗ)
        $totalSeats = (int)($data['total_seats'] ?? 0);
        if ($totalSeats < 7 || $totalSeats > 50) {
            $_SESSION['error'] = "Tổng số chỗ phải từ 7 đến 50 chỗ (phù hợp với các loại xe khách hiện nay).";
            header("Location: index.php?act=schedule-create");
            exit();
        }
        
        // Tự động tính số chỗ còn = tổng số chỗ - số chỗ đã đặt
        $seatsBooked = (int)($data['seats_booked'] ?? 0);
        $data['seats_available'] = max(0, $totalSeats - $seatsBooked);
        $data['total_seats'] = $totalSeats;
        $this->model->insert($data);
        $_SESSION['message'] = "Lịch trình đã được thêm thành công!";
        header("Location: index.php?act=schedule");
        exit();
    }

    // Form sửa
    public function scheduleEdit() {
        $id = (int)$_GET['id'];
        $schedule = $this->model->getById($id);
        $listTour = $this->model->getAllTours();
        include "views/admin/Schedule_edit.php";
    }

    // Xử lý cập nhật
    public function scheduleUpdate() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = (int)$_POST['id'];
        $data = $_POST;
        
        // Kiểm tra ngày khởi hành không được quá khứ
        if (!empty($data['departure_time'])) {
            $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
            $departureDateTime = strtotime($data['departure_time']);
            $now = time();
            
            if ($departureDateTime < $now) {
                $_SESSION['error'] = "Vui lòng chọn ngày khởi hành trong tương lai.";
                header("Location: index.php?act=schedule-edit&id=" . $id);
                exit();
            }
        }
        
        // Kiểm tra ngày kết thúc phải >= ngày hiện tại và >= ngày khởi hành
        if (!empty($data['end_date'])) {
            $endDate = strtotime($data['end_date']);
            $now = strtotime(date('Y-m-d')); // So sánh chỉ phần ngày
            
            if ($endDate < $now) {
                $_SESSION['error'] = "Ngày kết thúc phải >= ngày hiện tại!";
                header("Location: index.php?act=schedule-edit&id=" . $id);
                exit();
            }
            
            if (!empty($data['departure_time'])) {
                $departureDate = strtotime(date('Y-m-d', strtotime($data['departure_time'])));
                if ($endDate < $departureDate) {
                    $_SESSION['error'] = "Ngày kết thúc phải >= ngày khởi hành!";
                    header("Location: index.php?act=schedule-edit&id=" . $id);
                    exit();
                }
            }
        }
        
        // Kiểm tra tổng số chỗ (7-50 chỗ)
        $totalSeats = (int)($data['total_seats'] ?? 0);
        if ($totalSeats < 7 || $totalSeats > 50) {
            $_SESSION['error'] = "Tổng số chỗ phải từ 7 đến 50 chỗ (phù hợp với các loại xe khách hiện nay).";
            header("Location: index.php?act=schedule-edit&id=" . $id);
            exit();
        }
        
        // Tự động tính số chỗ còn = tổng số chỗ - số chỗ đã đặt
        $seatsBooked = (int)($data['seats_booked'] ?? 0);
        $data['seats_available'] = max(0, $totalSeats - $seatsBooked);
        $data['total_seats'] = $totalSeats;
        $this->model->update($id, $data);
        $_SESSION['message'] = "Lịch trình đã được cập nhật thành công!";
        header("Location: index.php?act=schedule");
        exit();
    }

    // Xóa
    public function scheduleDelete() {
        $id = (int)$_GET['id'];
        
        try {
            // Kiểm tra có thể xóa được không
            $check = $this->model->canDelete($id);
            if (!$check['can_delete']) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['error'] = "Không thể xóa lịch khởi hành: " . implode(" ", $check['errors']);
                header("Location: index.php?act=schedule");
                exit();
            }
            
            $this->model->delete($id);
            
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['message'] = "Xóa lịch khởi hành thành công!";
        } catch (Exception $e) {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: index.php?act=schedule");
        exit();
    }

    // Chi tiết lịch khởi hành và phân bổ
    public function scheduleDetail() {
        require_once "models/BookingModel.php";
        $bookingModel = new BookingModel();
        
        $id = (int)$_GET['id'];
        $schedule = $this->model->getById($id);
        if (!$schedule) {
            header("Location: index.php?act=schedule");
            exit();
        }
        
        $staffAssignments = $this->staffModel->getByDepartureId($id);
        $serviceAllocations = $this->serviceModel->getByDepartureId($id);
        $availableGuides = $this->staffModel->getAvailableGuides($id);
        $listTour = $this->model->getAllTours();
        $bookings = $bookingModel->getByDepartureId($id);
        
        include "views/admin/Schedule_detail.php";
    }

    // Thêm phân bổ nhân sự
    public function staffAssignmentStore() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = $_POST;
        
        // Kiểm tra ngày kết thúc phải >= ngày hiện tại
        if (!empty($data['end_date'])) {
            $endDate = strtotime($data['end_date']);
            $now = strtotime(date('Y-m-d'));
            
            if ($endDate < $now) {
                $_SESSION['error'] = "Ngày kết thúc phải >= ngày hiện tại!";
                header("Location: index.php?act=schedule-detail&id=" . $data['departure_id']);
                exit();
            }
        }
        
        // VALIDATION: Kiểm tra trùng lịch nếu là hướng dẫn viên
        if (isset($data['staff_type']) && $data['staff_type'] == 'guide' && !empty($data['staff_id'])) {
            $conflict = $this->staffModel->hasScheduleConflict(
                $data['staff_id'], 
                $data['departure_id']
            );
            
            if ($conflict['has_conflict']) {
                $_SESSION['error'] = $conflict['message'];
                header("Location: index.php?act=schedule-detail&id=" . $data['departure_id']);
                exit();
            }
        }
        
        $this->staffModel->insert($data);
        $_SESSION['message'] = "Thêm phân bổ nhân sự thành công!";
        header("Location: index.php?act=schedule-detail&id=" . $data['departure_id']);
        exit();
    }

    // Kiểm tra trùng lịch (AJAX)
    public function staffAssignmentCheckConflict() {
        header('Content-Type: application/json');
        
        $staffId = $_GET['staff_id'] ?? 0;
        $departureId = $_GET['departure_id'] ?? 0;
        $excludeId = $_GET['exclude_id'] ?? null;
        
        if (!$staffId || !$departureId) {
            echo json_encode(['has_conflict' => false]);
            exit();
        }
        
        $conflict = $this->staffModel->hasScheduleConflict($staffId, $departureId, $excludeId);
        echo json_encode($conflict);
        exit();
    }

    // Xóa phân bổ nhân sự
    public function staffAssignmentDelete() {
        $id = (int)$_GET['id'];
        $assignment = $this->staffModel->find($id);
        $departureId = $assignment['departure_id'];
        
        $this->staffModel->delete($id);
        
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Xóa phân bổ nhân sự thành công!";
        header("Location: index.php?act=schedule-detail&id=" . $departureId);
        exit();
    }

    // Thêm phân bổ dịch vụ
    public function serviceAllocationStore() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = $_POST;
        
        // Kiểm tra ngày kết thúc phải >= ngày hiện tại
        if (!empty($data['end_date'])) {
            $endDate = strtotime($data['end_date']);
            $now = strtotime(date('Y-m-d'));
            
            if ($endDate < $now) {
                $_SESSION['error'] = "Ngày kết thúc phải >= ngày hiện tại!";
                header("Location: index.php?act=schedule-detail&id=" . $data['departure_id']);
                exit();
            }
        }
        
        // Xử lý chi tiết theo loại dịch vụ
        if ($data['service_type'] == 'transport') {
            $data['transport_details'] = [
                'vehicle_type' => $data['vehicle_type'] ?? null,
                'vehicle_number' => $data['vehicle_number'] ?? null,
                'driver_name' => $data['driver_name'] ?? null,
                'driver_phone' => $data['driver_phone'] ?? null,
                'license_number' => $data['license_number'] ?? null,
                'capacity' => $data['capacity'] ?? null,
                'route' => $data['route'] ?? null,
                'pickup_location' => $data['pickup_location'] ?? null,
                'dropoff_location' => $data['dropoff_location'] ?? null,
                'notes' => $data['transport_notes'] ?? null
            ];
        } elseif ($data['service_type'] == 'hotel') {
            $data['hotel_details'] = [
                'hotel_name' => $data['hotel_name'] ?? null,
                'room_type' => $data['room_type'] ?? null,
                'room_number' => $data['room_number'] ?? null,
                'check_in_date' => $data['check_in_date'] ?? null,
                'check_out_date' => $data['check_out_date'] ?? null,
                'check_in_time' => $data['check_in_time'] ?? null,
                'check_out_time' => $data['check_out_time'] ?? null,
                'number_of_rooms' => $data['number_of_rooms'] ?? 1,
                'number_of_nights' => $data['number_of_nights'] ?? 1,
                'amenities' => $data['amenities'] ?? null,
                'notes' => $data['hotel_notes'] ?? null
            ];
        } elseif ($data['service_type'] == 'flight') {
            $departureDatetime = null;
            if (!empty($data['flight_departure_date']) && !empty($data['flight_departure_time'])) {
                $departureDatetime = $data['flight_departure_date'] . ' ' . $data['flight_departure_time'];
            }
            $arrivalDatetime = null;
            if (!empty($data['flight_arrival_date']) && !empty($data['flight_arrival_time'])) {
                $arrivalDatetime = $data['flight_arrival_date'] . ' ' . $data['flight_arrival_time'];
            }
            
            $data['flight_details'] = [
                'flight_number' => $data['flight_number'] ?? null,
                'airline' => $data['airline'] ?? null,
                'departure_airport' => $data['departure_airport'] ?? null,
                'arrival_airport' => $data['arrival_airport'] ?? null,
                'departure_datetime' => $departureDatetime,
                'arrival_datetime' => $arrivalDatetime,
                'class' => $data['flight_class'] ?? 'economy',
                'number_of_tickets' => $data['number_of_tickets'] ?? 1,
                'notes' => $data['flight_notes'] ?? null
            ];
        }
        
        $this->serviceModel->insert($data);
        
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Thêm phân bổ dịch vụ thành công!";
        header("Location: index.php?act=schedule-detail&id=" . $data['departure_id']);
        exit();
    }

    // Xóa phân bổ dịch vụ
    public function serviceAllocationDelete() {
        $id = (int)$_GET['id'];
        $allocation = $this->serviceModel->find($id);
        $departureId = $allocation['departure_id'];
        
        $this->serviceModel->delete($id);
        
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Xóa phân bổ dịch vụ thành công!";
        header("Location: index.php?act=schedule-detail&id=" . $departureId);
        exit();
    }

    // Xuất danh sách khách đi tour
    public function exportCustomers() {
        require_once "models/BookingModel.php";
        $bookingModel = new BookingModel();
        
        $id = (int)$_GET['id'];
        $schedule = $this->model->getById($id);
        if (!$schedule) {
            header("Location: index.php?act=schedule");
            exit();
        }
        
        $bookings = $bookingModel->getByDepartureId($id);
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="danh_sach_khach_' . $id . '_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Add BOM for UTF-8
        echo "\xEF\xBB\xBF";
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, [
            'STT',
            'Họ tên',
            'SĐT',
            'Email',
            'Số người lớn',
            'Số trẻ em',
            'Số em bé',
            'Tổng số khách',
            'Trạng thái thanh toán',
            'Ghi chú',
            'Thời gian đặt'
        ], ';');
        
        // Add data rows
        $stt = 1;
        foreach ($bookings as $booking) {
            $paymentStatus = $booking['payment_status'] ?? 'pending';
            $paymentStatusText = [
                'pending' => 'Chưa thanh toán',
                'partial' => 'Đã cọc',
                'paid' => 'Đã thanh toán',
                'refunded' => 'Đã hoàn tiền'
            ];
            
            fputcsv($output, [
                $stt++,
                $booking['customer_name'] ?? '',
                $booking['customer_phone'] ?? '',
                $booking['customer_email'] ?? '',
                $booking['num_adults'] ?? 0,
                $booking['num_children'] ?? 0,
                $booking['num_infants'] ?? 0,
                $booking['num_people'] ?? 0,
                $paymentStatusText[$paymentStatus] ?? 'N/A',
                $booking['notes'] ?? '',
                date('d/m/Y H:i', strtotime($booking['created_at']))
            ], ';');
        }
        
        fclose($output);
        exit();
    }
}
