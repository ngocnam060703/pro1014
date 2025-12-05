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
        $listSchedule = $this->model->getAll();
        include "views/admin/Schedule_list.php";
    }

    // Form thêm
    public function scheduleCreate() {
        $listTour = $this->model->getAllTours();
        include "views/admin/Schedule_create.php";
    }

    // Xử lý thêm
    public function scheduleStore() {
        $data = $_POST;
        if(isset($data['departure_time'])) {
            $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
        }
        $this->model->insert($data);
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
        $id = (int)$_POST['id'];
        $data = $_POST;
        if(isset($data['departure_time'])) {
            $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
        }
        $this->model->update($id, $data);
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
        
        include "views/admin/Schedule_detail.php";
    }

    // Thêm phân bổ nhân sự
    public function staffAssignmentStore() {
        $data = $_POST;
        $this->staffModel->insert($data);
        
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = "Thêm phân bổ nhân sự thành công!";
        header("Location: index.php?act=schedule-detail&id=" . $data['departure_id']);
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
        $data = $_POST;
        
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
}
