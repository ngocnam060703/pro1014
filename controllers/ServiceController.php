<?php
require_once "models/ServiceModel.php";
require_once "models/TourModel.php";

class ServiceController {

    private $serviceModel;
    private $tourModel;

    public function __construct() {
        $this->serviceModel = new ServiceModel();
        $this->tourModel = new TourModel();
    }

    public function list() {
        $listService = $this->serviceModel->getAll();
        include "views/admin/service_list.php";
    }

    public function create() {
        $trips = $this->tourModel->getAllTours();
        include "views/admin/service_create.php";
    }

    public function store() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $trip_id = $_POST["trip_id"] ?? $_POST["trip"] ?? null;
        $service_name = trim($_POST["service_name"] ?? "");
        
        if (empty($trip_id) || empty($service_name)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
            header("Location: index.php?act=service-create");
            exit();
        }
        
        $data = [
            "trip" => $trip_id,
            "service_name" => $service_name,
            "status" => $_POST["status"] ?? "Hoạt động",
            "note" => trim($_POST["notes"] ?? $_POST["note"] ?? "")
        ];
        
        try {
            $this->serviceModel->insert($data);
            $_SESSION['message'] = "Thêm dịch vụ thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
        }
        
        header("Location: index.php?act=service");
        exit();
    }

    public function edit() {
        $id = $_GET["id"];
        $service = $this->serviceModel->getById($id);
        $trips = $this->tourModel->getAllTours();
        include "views/admin/service_edit.php";
    }

    public function update() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_POST["id"] ?? null;
        if (!$id) {
            $_SESSION['error'] = "Không tìm thấy dịch vụ!";
            header("Location: index.php?act=service");
            exit();
        }
        
        $trip_id = $_POST["trip_id"] ?? $_POST["trip"] ?? null;
        $service_name = trim($_POST["service_name"] ?? "");
        
        if (empty($trip_id) || empty($service_name)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
            header("Location: index.php?act=service-edit&id=" . $id);
            exit();
        }
        
        $data = [
            "trip" => $trip_id,
            "service_name" => $service_name,
            "status" => $_POST["status"] ?? "Hoạt động",
            "note" => trim($_POST["notes"] ?? $_POST["note"] ?? "")
        ];
        
        try {
            $this->serviceModel->update($id, $data);
            $_SESSION['message'] = "Cập nhật dịch vụ thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
        }
        
        header("Location: index.php?act=service");
        exit();
    }

    public function delete() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_GET["id"] ?? null;
        if (!$id) {
            $_SESSION['error'] = "Không tìm thấy dịch vụ!";
            header("Location: index.php?act=service");
            exit();
        }
        
        try {
            $this->serviceModel->delete($id);
            $_SESSION['message'] = "Xóa dịch vụ thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
        }
        
        header("Location: index.php?act=service");
        exit();
    }
}
