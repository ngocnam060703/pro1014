<?php
require_once "models/GuideIncidentModel.php";
require_once "models/GuideModel.php";
require_once "models/DepartureModel.php";

class GuideIncidentController {

    private $incident;
    private $guide;
    private $departure;

    public function __construct() {
        $this->incident   = new GuideIncidentModel();
        $this->guide      = new GuideModel();
        $this->departure  = new DepartureModel();
    }

    // LIST
    public function index() {
        $incidents = $this->incident->all();
        include "views/admin/guide_incident_list.php";
    }

    // CREATE FORM
    public function create() {
        $guides      = $this->guide->all();
        $departures  = $this->departure->all();
        include "views/admin/guide_incident_create.php";
    }

    // STORE
    public function store() {
        $data = [
            "departure_id" => $_POST["departure_id"],
            "guide_id"     => $_POST["guide_id"],
            "incident_type"=> $_POST["incident_type"],
            "severity"     => $_POST["severity"],
            "description"  => $_POST["description"],
            "solution"     => $_POST["solution"],
            "photos"       => $_POST["photos"] ?? null
        ];

        $this->incident->store($data);

        header("Location: index.php?act=guide-incident");
        exit();
    }

    // EDIT FORM
    public function edit() {
        $id = $_GET["id"];
        $incident    = $this->incident->find($id);
        $guides      = $this->guide->all();
        $departures  = $this->departure->all();

        include "views/admin/guide_incident_edit.php";
    }

    // UPDATE
    public function update() {
        $id = $_POST["id"];

        $data = [
            "departure_id" => $_POST["departure_id"],
            "guide_id"     => $_POST["guide_id"],
            "incident_type"=> $_POST["incident_type"],
            "severity"     => $_POST["severity"],
            "description"  => $_POST["description"],
            "solution"     => $_POST["solution"],
            "photos"       => $_POST["photos"] ?? null
        ];

        $this->incident->updateData($id, $data);

        header("Location: index.php?act=guide-incident");
        exit();
    }

    // DELETE
    public function delete() {
        $id = $_GET["id"];
        $this->incident->delete($id);
        header("Location: index.php?act=guide-incident");
        exit();
    }

    // DETAIL
    public function detail() {
        $id = $_GET["id"];
        $incident = $this->incident->findDetail($id);
        include "views/admin/guide_incident_detail.php";
    }
}
