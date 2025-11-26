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
        $data = [
            "trip" => $_POST["trip"],
            "service_name" => $_POST["service_name"],
            "status" => $_POST["status"],
            "note" => $_POST["note"]
        ];
        $this->serviceModel->insert($data);
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
        $id = $_POST["id"];
        $data = [
            "trip" => $_POST["trip"],
            "service_name" => $_POST["service_name"],
            "status" => $_POST["status"],
            "note" => $_POST["note"]
        ];
        $this->serviceModel->update($id, $data);
        header("Location: index.php?act=service");
        exit();
    }

    public function delete() {
        $id = $_GET["id"];
        $this->serviceModel->delete($id);
        header("Location: index.php?act=service");
        exit();
    }
}
