<?php
require_once "models/GuideIncidentModel.php";
require_once "models/GuideModel.php";

class GuideIncidentController {

    private $incident;
    private $guide;

    public function __construct() {
        $this->incident = new GuideIncidentModel();
        $this->guide = new GuideModel();
    }

    public function index() {
        $data = $this->incident->all();
        include "views/admin/guide_incident_list.php";
    }

    public function create() {
        $guides = $this->guide->all();
        $departures = pdo_query("SELECT * FROM departures");
        include "views/admin/guide_incident_create.php";
    }

    public function store() {
        $photoPath = null;
        if (!empty($_FILES["photos"]["name"])) {
            $photoPath = uploadFile($_FILES["photos"], "uploads/incidents/");
        }

        $data = [
            "departure_id" => $_POST["departure_id"],
            "guide_id" => $_POST["guide_id"],
            "incident_type" => $_POST["incident_type"],
            "severity" => $_POST["severity"],
            "description" => $_POST["description"],
            "solution" => $_POST["solution"],
            "photos" => $photoPath
        ];

        $this->incident->store($data);
        header("Location: index.php?act=guide-incident");
    }

    public function edit() {
        $id = $_GET["id"];
        $guides = $this->guide->all();
        $departures = pdo_query("SELECT * FROM departures");
        $incident = $this->incident->find($id);

        include "views/admin/guide_incident_edit.php";
    }

    public function update() {
        $id = $_POST["id"];

        $photoPath = $_POST["old_photo"];

        if (!empty($_FILES["photos"]["name"])) {
            deleteFile($photoPath);
            $photoPath = uploadFile($_FILES["photos"], "uploads/incidents/");
        }

        $data = [
            "departure_id" => $_POST["departure_id"],
            "guide_id" => $_POST["guide_id"],
            "incident_type" => $_POST["incident_type"],
            "severity" => $_POST["severity"],
            "description" => $_POST["description"],
            "solution" => $_POST["solution"],
            "photos" => $photoPath
        ];

        $this->incident->updateData($id, $data);
        header("Location: index.php?act=guide-incident");
    }

    public function delete() {
        $id = $_GET["id"];
        $item = $this->incident->find($id);

        if ($item["photos"] != null) deleteFile($item["photos"]);

        $this->incident->delete($id);
        header("Location: index.php?act=guide-incident");
    }
}
