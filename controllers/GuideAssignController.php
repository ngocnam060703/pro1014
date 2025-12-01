<?php
require_once "models/GuideAssignModel.php";
require_once "models/GuideModel.php";
require_once "models/TourModel.php";
require_once "models/LichModel.php";

if(session_status() == PHP_SESSION_NONE) session_start();

class GuideAssignController {

    private $assign;
    private $guide;
    private $tour;
    private $lich;

    public function __construct() {
        $this->assign = new GuideAssignModel();
        $this->guide = new GuideModel();
        $this->tour = new TourModel();
        $this->lich = new LichModel();
    }

    // ====================
    // LIST PHÂN CÔNG
    // ====================
    public function index() {
        $data = $this->assign->all();
        include "views/admin/guide_assign_list.php";
    }

    // ====================
    // CREATE PHÂN CÔNG
    // ====================
    public function create() {
        $guides = $this->guide->all();
        $tours = $this->tour->getAllTours();
        $departures = $this->lich->getAllDepartures(); // hàm cần thêm trong LichModel
        include "views/admin/guide_assign_create.php";
    }

    public function store() {
    if(!empty($_POST)) {
        $data = [
            "guide_id" => $_POST["guide_id"],      // HDV được chọn
            "tour_id" => $_POST["tour_id"],        // Tour được chọn
            "departure_date" => $_POST["departure_date"], 
            "meeting_point" => $_POST["meeting_point"] ?? '',
            "max_people" => $_POST["max_people"] ?? 0,
            "note" => $_POST["note"] ?? '',
            "status" => 'scheduled',               // mặc định Chưa bắt đầu
            "assigned_at" => date("Y-m-d H:i:s")
        ];
        $this->assign->store($data);
        $_SESSION['message'] = "Phân công HDV thành công!";
    }
    header("Location: index.php?act=guide-assign");
}


    // ====================
    // EDIT PHÂN CÔNG
    // ====================
    public function edit() {
        $id = $_GET["id"] ?? 0;
        if($id) {
            $assign = $this->assign->find($id);
            $guides = $this->guide->all();
            $tours = $this->tour->getAllTours();
            $departures = $this->lich->getAllDepartures();
            include "views/admin/guide_assign_edit.php";
        } else {
            $_SESSION['message'] = "Không tìm thấy phân công HDV!";
            header("Location: index.php?act=guide-assign");
        }
    }

    public function update() {
        $id = $_POST["id"] ?? 0;
        if($id) {
            $data = [
                "guide_id" => $_POST["guide_id"] ?? null,
                "tour_id" => $_POST["tour_id"] ?? null,
                "departure_id" => $_POST["departure_id"] ?? null,
                "departure_date" => $_POST["departure_date"] ?? null,
                "meeting_point" => $_POST["meeting_point"] ?? '',
                "max_people" => $_POST["max_people"] ?? 0,
                "note" => $_POST["note"] ?? '',
                "status" => $_POST["status"] ?? 'scheduled'
            ];
            $this->assign->updateData($id, $data);
            $_SESSION['message'] = "Cập nhật phân công HDV thành công!";
        }
        header("Location: index.php?act=guide-assign");
    }

    // ====================
    // DELETE PHÂN CÔNG
    // ====================
    public function delete() {
        $id = $_GET["id"] ?? 0;
        if($id) {
            $this->assign->delete($id);
            $_SESSION['message'] = "Xóa phân công HDV thành công!";
        }
        header("Location: index.php?act=guide-assign");
    }
}
