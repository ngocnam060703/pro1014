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

    public function index() {
        $data = $this->assign->all();
        include "views/admin/guide_assign_list.php";
    }

    public function create() {
        $guides = $this->guide->all();
        $guides = $this->guide->all();
        $tours = $this->tours->getAllTours();
        $departures = pdo_query("SELECT * FROM departures");

        include "views/admin/guide_assign_create.php";
    }

    public function store() {
        $data = [
            "departure_id" => $_POST["departure_id"],
            "guide_id" => $_POST["guide_id"],
            "note" => $_POST["note"]
        ];

        $this->assign->store($data);
        header("Location: index.php?act=guide-assign");
    }

    public function edit() {
        $id = $_GET["id"];

        $assign = $this->assign->find($id);
        $guides = $this->guide->all();
        $departures = pdo_query("SELECT * FROM departures");

        include "views/admin/guide_assign_edit.php";
    }

    public function update() {
        $id = $_POST["id"];

        $data = [
            "departure_id" => $_POST["departure_id"],
            "guide_id" => $_POST["guide_id"],
            "note" => $_POST["note"]
        ];

        $this->assign->updateData($id, $data);
        header("Location: index.php?act=guide-assign");
    }

    public function delete() {
        $id = $_GET["id"];
        $this->assign->delete($id);

        header("Location: index.php?act=guide-assign");
    }
}
