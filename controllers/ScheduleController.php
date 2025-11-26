<?php
require_once "models/ScheduleModel.php";

class ScheduleController {
    private $model;

    public function __construct() {
        $this->model = new ScheduleModel();
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
        $this->model->delete($id);
        header("Location: index.php?act=schedule");
        exit();
    }
}
