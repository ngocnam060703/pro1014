<?php
require_once "models/TourModel.php";
require_once "models/LichModel.php";

class TourController {

    private $tourModel;
    private $lichModel;

    public function __construct() {
        $this->tourModel = new TourModel();
        $this->lichModel = new LichModel();
    }

    // Tour
    public function index() {
        $listTour = $this->tourModel->getAllTours();
        include "views/admin/tour_list.php";
    }

    public function create() {
        include "views/admin/tour_create.php";
    }

    public function store() {
        $data = $_POST;
        $this->tourModel->insertTour($data);
        $_SESSION['message'] = "Tour đã được thêm thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    public function edit() {
        $id = $_GET["id"] ?? null;
        if (!$id) header("Location: index.php?act=tour");
        $tour = $this->tourModel->getTourById($id);
        include "views/admin/tour_edit.php";
    }

    public function update() {
        $id = $_POST["id"] ?? null;
        if (!$id) header("Location: index.php?act=tour");
        $data = $_POST;
        $this->tourModel->updateTour($id, $data);
        $_SESSION['message'] = "Tour đã được cập nhật thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    public function delete() {
        $id = $_GET["id"] ?? null;
        if ($id) $this->tourModel->deleteTour($id);
        $_SESSION['message'] = "Tour đã được xóa thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    // Lịch
    public function lichList() {
        $tour_id = (int)$_GET["tour_id"];
        $tour = $this->tourModel->getTourById($tour_id);
        $listLich = $this->lichModel->getLichByTour($tour_id);
        include "views/admin/lich_list.php";
    }

    public function lichCreate() {
        $tour_id = (int)$_GET["tour_id"];
        $tour = $this->tourModel->getTourById($tour_id);
        include "views/admin/lich_create.php";
    }

    public function lichStore() {
        $data = $_POST;
        if(isset($data['departure_time'])) $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
        $this->lichModel->insertLich($data);
        $tour_id = (int)$_POST["tour_id"];
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }

    public function lichEdit() {
        $id = (int)$_GET["id"];
        $lich = $this->lichModel->getLichById($id);
        $tour = $this->tourModel->getTourById((int)$lich["tour_id"]);
        include "views/admin/lich_edit.php";
    }

    public function lichUpdate() {
        $id = (int)$_POST["id"];
        $data = $_POST;
        $this->lichModel->updateLich($id, $data);
        $tour_id = (int)$_POST["tour_id"];
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }

    public function lichDelete() {
        $id = (int)$_GET["id"];
        $lich = $this->lichModel->getLichById($id);
        $this->lichModel->deleteLich($id);
        $tour_id = (int)$lich["tour_id"];
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }
}
