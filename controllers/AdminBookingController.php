<?php
require_once "models/BookingModel.php";

class AdminBookingController {

    private $model;

    public function __construct() {
        $this->model = new BookingModel();
    }

    // Danh sách booking
    public function bookingList() {
        $bookings = $this->model->all();
        include "views/admin/booking_list.php";
    }

    // Chi tiết booking
    public function bookingDetail() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->model->find($id);
        include "views/admin/booking_detail.php";
    }

    // Cập nhật trạng thái
    public function updateStatus() {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $this->model->updateStatus($id, $status);
        header("Location: index.php?act=booking");
        exit;
    }

    // Xóa booking
    public function delete() {
        $id = $_GET['id'] ?? 0;
        $this->model->delete($id);
        header("Location: index.php?act=booking");
        exit;
    }
}
