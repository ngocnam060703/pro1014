<?php
require_once "models/GuideAssignModel.php";
require_once "models/GuideModel.php";

class GuideAssignController {

    private $assign;
    private $guide;
    private $tours;

    public function __construct() {
        $this->assign = new GuideAssignModel();
        $this->guide = new GuideModel();
        $this->tours = new TourModel();
    }

    // Hiển thị danh sách phân công
    public function index() {
        $data = $this->assign->all();
        include "views/admin/guide_assign_list.php";
    }

    // Form thêm phân công
    public function create() {
        $guides = $this->guide->all();
        $tours = $this->tours->getAllTours();
        $departures = pdo_query("SELECT * FROM departures");

        include "views/admin/guide_assign_create.php";
    }

    // Thêm phân công mới
    public function store() {
    $departure_id = $_POST["departure_id"];
    $guide_id = $_POST["guide_id"];
    $note = $_POST["note"];

    // Lấy thông tin departure
    $departure = pdo_query_one("SELECT * FROM departures WHERE id = ?", $departure_id);
    if (!$departure) {
        die("Departure not found!");
    }

    $data = [
        "guide_id"       => $guide_id,
        "departure_id"   => $departure_id,
        "tour_id"        => $departure["tour_id"],
        "departure_date" => $departure["departure_date"],  // thêm vào
        "note"           => $note,
        "assigned_at"    => date("Y-m-d H:i:s")
    ];

    $this->assign->store($data);
    header("Location: index.php?act=guide-assign");
}
    // Form chỉnh sửa
    public function edit() {
        $id = $_GET["id"];
        $assign = $this->assign->find($id);
        $guides = $this->guide->all();
        $departures = pdo_query("SELECT * FROM departures");

        include "views/admin/guide_assign_edit.php";
    }

    // Cập nhật phân công
    public function update() {
        $id = $_POST["id"];
        $departure_id = $_POST["departure_id"];
        $guide_id = $_POST["guide_id"];
        $note = $_POST["note"];

        $departure = pdo_query_one("SELECT * FROM departures WHERE id = ?", $departure_id);
        if (!$departure) {
            die("Departure not found!");
        }

        $data = [
            "guide_id"     => $guide_id,
            "departure_id" => $departure_id,
            "tour_id"      => $departure["tour_id"],
            "note"         => $note
        ];

        $this->assign->updateData($id, $data);
        header("Location: index.php?act=guide-assign");
    }

    // Xóa phân công
    public function delete() {
        $id = $_GET["id"];
        $this->assign->delete($id);

        header("Location: index.php?act=guide-assign");
    }
}
