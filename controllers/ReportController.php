<?php
require_once "models/ReportModel.php";

if(session_status() == PHP_SESSION_NONE) session_start();

class ReportController {

    private $report;

    public function __construct() {
        $this->report = new ReportModel();
    }

    // Trang báo cáo đơn hàng
    public function index() {
        $reports = $this->report->getAllOrdersReport();
        $totalRevenue = $this->report->getTotalRevenue();
        $totalOrders = $this->report->getTotalOrders();

        include "views/admin/report_list.php";
    }
}