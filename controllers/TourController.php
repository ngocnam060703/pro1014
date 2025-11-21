<?php

require_once "models/TourModel.php";

class TourController {

    private $tourModel;
    private $lichModel;

    public function __construct() {
        $this->tourModel = new TourModel();
        $this->lichModel = new LichModel();
    }

    // Danh sách tour
    public function index() {
        $listTour = $this->tourModel->getAllTours();
        include "views/admin/tour_list.php";
    }

    // Form thêm mới
    public function create() {
        include "views/admin/tour_create.php";
    }

    // Xử lý thêm
    public function store() {
        $data = $_POST;
        $this->tourModel->insertTour($data);

        $_SESSION['message'] = "Tour đã được thêm thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    // Form sửa
    public function edit() {
        $id = $_GET["id"] ?? null;
        if (!$id) {
            header("Location: index.php?act=tour");
            exit();
        }

        $tour = $this->tourModel->getTourById($id);
        include "views/admin/tour_edit.php";
    }

    // Xử lý cập nhật
    public function update() {
        $id = $_POST["id"] ?? null;
        if (!$id) {
            header("Location: index.php?act=tour");
            exit();
        }

        $data = $_POST;
        $this->tourModel->updateTour($id, $data);

        $_SESSION['message'] = "Tour đã được cập nhật thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    // Xóa
    public function delete() {
        $id = $_GET["id"] ?? null;
        if ($id) {
            $this->tourModel->deleteTour($id);
            $_SESSION['message'] = "Tour đã được xóa thành công!";
        }
        header("Location: index.php?act=tour");
        exit();
    }

      // Danh sách lịch của 1 tour
    public function lichList() {
        // Ép kiểu tour_id sang số nguyên (integer)
        $tour_id = (int)$_GET["tour_id"]; 
        $tour = $this->tourModel->getTourById($tour_id);
        $listLich = $this->lichModel->getLichByTour($tour_id);
        include "views/admin/lich_list.php";
    }

    // Form thêm lịch
    public function lichCreate() {
        // Ép kiểu tour_id sang số nguyên (integer)
        $tour_id = (int)$_GET["tour_id"]; 
        $tour = $this->tourModel->getTourById($tour_id);
        include "views/admin/lich_create.php";
    }

    // Xử lý thêm lịch
    public function lichStore() {
        $data = $_POST;
        if(isset($data['departure_time'])) {
        $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
    }
        $this->lichModel->insertLich($data);
        // Ép kiểu để URL ổn định
        $tour_id = (int)$_POST["tour_id"]; 
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }

    // Form sửa lịch
    public function lichEdit() {
        // Ép kiểu ID sang số nguyên (integer)
        $id = (int)$_GET["id"]; 
        $lich = $this->lichModel->getLichById($id);
        // Đảm bảo tour_id được lấy ra từ lịch cũng là số nguyên
        $tour = $this->tourModel->getTourById((int)$lich["tour_id"]); 
        include "views/admin/lich_edit.php";
    }

    // Xử lý cập nhật lịch
    public function lichUpdate() {
        // Ép kiểu ID sang số nguyên (integer)
        $id = (int)$_POST["id"];
        $data = $_POST;
        $this->lichModel->updateLich($id, $data);
        // Ép kiểu để URL ổn định
        $tour_id = (int)$_POST["tour_id"]; 
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }

    // Xóa lịch
    public function lichDelete() {
        // Ép kiểu ID sang số nguyên (integer)
        $id = (int)$_GET["id"];
        $lich = $this->lichModel->getLichById($id);
        $this->lichModel->deleteLich($id);
        // Ép kiểu để chuyển hướng ổn định
        $tour_id = (int)$lich["tour_id"]; 
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }
}
