<?php
require_once "models/RevenueReportModel.php";
require_once "models/TourModel.php";

class RevenueReportController {
    private $reportModel;
    private $tourModel;

    public function __construct() {
        $this->reportModel = new RevenueReportModel();
        $this->tourModel = new TourModel();
    }

    // Báo cáo tổng hợp theo tour
    public function index() {
        $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $periodType = $_GET['period'] ?? 'monthly';
        
        // Lấy danh sách tour
        $tours = $this->tourModel->getAllTours();
        
        // Lấy báo cáo
        if ($periodType == 'by_tour') {
            $reportData = $this->reportModel->getProfitByTour($tourId, $startDate, $endDate);
        } else {
            $reportData = $this->reportModel->getReportByPeriod($periodType, $startDate, $endDate);
        }
        
        // Lấy tổng hợp
        $summary = $this->reportModel->getTotalSummary($startDate, $endDate);
        
        // Top tour theo lợi nhuận
        $topTours = $this->reportModel->getTopToursByProfit(10, $startDate, $endDate);
        
        include "views/admin/revenue_report.php";
    }

    // Báo cáo chi tiết theo tour
    public function tourDetail() {
        $tourId = (int)$_GET['tour_id'];
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        $tour = $this->tourModel->find($tourId);
        if (!$tour) {
            header("Location: index.php?act=revenue-report");
            exit();
        }
        
        // Lấy báo cáo chi tiết
        $reportData = $this->reportModel->getProfitByTour($tourId, $startDate, $endDate);
        $tourData = !empty($reportData) ? $reportData[0] : [
            'tour_id' => $tourId,
            'tour_name' => $tour['title'],
            'total_revenue' => 0,
            'total_expenses' => 0,
            'total_profit' => 0,
            'booking_count' => 0,
            'guest_count' => 0
        ];
        
        // Lấy chi phí theo loại
        $expenses = $this->reportModel->getExpensesByTour($tourId, $startDate, $endDate);
        
        // Lấy doanh thu theo thời gian
        $revenueByPeriod = $this->reportModel->getReportByPeriod('monthly', $startDate, $endDate);
        $revenueByPeriod = array_filter($revenueByPeriod, function($item) use ($tourId) {
            return $item['tour_id'] == $tourId;
        });
        
        include "views/admin/revenue_report_detail.php";
    }
}

