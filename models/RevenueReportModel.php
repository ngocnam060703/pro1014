<?php
require_once __DIR__ . "/../commons/function.php";

class RevenueReportModel {

    // Lấy doanh thu theo tour trong khoảng thời gian
    public function getRevenueByTour($tourId = null, $startDate = null, $endDate = null) {
        $sql = "SELECT 
                    t.id as tour_id,
                    t.title as tour_name,
                    COUNT(b.id) as booking_count,
                    SUM(CASE WHEN b.payment_status IN ('paid', 'partial') THEN b.total_price ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN b.payment_status = 'paid' THEN b.total_price ELSE 0 END) as paid_revenue,
                    SUM(CASE WHEN b.payment_status = 'partial' THEN b.deposit_amount ELSE 0 END) as partial_revenue,
                    SUM(b.num_adults + b.num_children + b.num_infants) as total_guests
                FROM bookings b
                INNER JOIN tours t ON b.tour_id = t.id
                WHERE b.status != 'cancelled'";
        
        $params = [];
        
        if ($tourId) {
            $sql .= " AND t.id = ?";
            $params[] = $tourId;
        }
        
        if ($startDate) {
            $sql .= " AND DATE(b.created_at) >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND DATE(b.created_at) <= ?";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY t.id, t.title ORDER BY total_revenue DESC";
        
        return pdo_query($sql, ...$params);
    }

    // Lấy chi phí theo tour trong khoảng thời gian
    public function getExpensesByTour($tourId = null, $startDate = null, $endDate = null) {
        $sql = "SELECT 
                    COALESCE(t.id, 0) as tour_id,
                    COALESCE(t.title, 'Chi phí chung') as tour_name,
                    expense_type,
                    COUNT(*) as expense_count,
                    SUM(amount) as total_expenses
                FROM tour_expenses e
                LEFT JOIN tours t ON e.tour_id = t.id
                WHERE 1=1";
        
        $params = [];
        
        if ($tourId) {
            $sql .= " AND (e.tour_id = ? OR e.tour_id IS NULL)";
            $params[] = $tourId;
        }
        
        if ($startDate) {
            $sql .= " AND (e.expense_date >= ? OR e.expense_date IS NULL)";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND (e.expense_date <= ? OR e.expense_date IS NULL)";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY COALESCE(t.id, 0), COALESCE(t.title, 'Chi phí chung'), expense_type";
        
        return pdo_query($sql, ...$params);
    }

    // Lấy tổng hợp doanh thu, chi phí, lợi nhuận theo tour
    public function getProfitByTour($tourId = null, $startDate = null, $endDate = null) {
        // Lấy doanh thu
        $revenueData = $this->getRevenueByTour($tourId, $startDate, $endDate);
        
        // Lấy chi phí
        $expenseData = $this->getExpensesByTour($tourId, $startDate, $endDate);
        
        // Tổng hợp theo tour
        $result = [];
        
        // Xử lý doanh thu
        foreach ($revenueData as $row) {
            $tourId = $row['tour_id'];
            if (!isset($result[$tourId])) {
                $result[$tourId] = [
                    'tour_id' => $tourId,
                    'tour_name' => $row['tour_name'],
                    'total_revenue' => 0,
                    'total_expenses' => 0,
                    'total_profit' => 0,
                    'booking_count' => 0,
                    'guest_count' => 0,
                    'expenses_by_type' => []
                ];
            }
            $result[$tourId]['total_revenue'] = (float)$row['total_revenue'];
            $result[$tourId]['booking_count'] = (int)$row['booking_count'];
            $result[$tourId]['guest_count'] = (int)$row['total_guests'];
        }
        
        // Xử lý chi phí
        foreach ($expenseData as $row) {
            $tourId = $row['tour_id'];
            if (!isset($result[$tourId])) {
                $result[$tourId] = [
                    'tour_id' => $tourId,
                    'tour_name' => $row['tour_name'],
                    'total_revenue' => 0,
                    'total_expenses' => 0,
                    'total_profit' => 0,
                    'booking_count' => 0,
                    'guest_count' => 0,
                    'expenses_by_type' => []
                ];
            }
            $expenseAmount = (float)$row['total_expenses'];
            $result[$tourId]['total_expenses'] += $expenseAmount;
            $result[$tourId]['expenses_by_type'][$row['expense_type']] = $expenseAmount;
        }
        
        // Tính lợi nhuận
        foreach ($result as &$row) {
            $row['total_profit'] = $row['total_revenue'] - $row['total_expenses'];
            $row['profit_margin'] = $row['total_revenue'] > 0 
                ? ($row['total_profit'] / $row['total_revenue']) * 100 
                : 0;
        }
        
        // Sắp xếp theo lợi nhuận
        usort($result, function($a, $b) {
            return $b['total_profit'] <=> $a['total_profit'];
        });
        
        return $result;
    }

    // Lấy báo cáo theo thời gian (tháng, quý, năm)
    public function getReportByPeriod($periodType = 'monthly', $startDate = null, $endDate = null) {
        $periodFormat = [
            'daily' => '%Y-%m-%d',
            'monthly' => '%Y-%m',
            'quarterly' => '%Y-Q%q',
            'yearly' => '%Y'
        ];
        
        $format = $periodFormat[$periodType] ?? '%Y-%m';
        
        // Doanh thu theo kỳ
        $sql = "SELECT 
                    DATE_FORMAT(b.created_at, ?) as period,
                    t.id as tour_id,
                    t.title as tour_name,
                    SUM(CASE WHEN b.payment_status IN ('paid', 'partial') THEN b.total_price ELSE 0 END) as revenue,
                    COUNT(b.id) as booking_count
                FROM bookings b
                INNER JOIN tours t ON b.tour_id = t.id
                WHERE b.status != 'cancelled'";
        
        $params = [$format];
        
        if ($startDate) {
            $sql .= " AND DATE(b.created_at) >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND DATE(b.created_at) <= ?";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY period, t.id, t.title ORDER BY period DESC, revenue DESC";
        
        $revenueData = pdo_query($sql, ...$params);
        
        // Chi phí theo kỳ
        $expenseFormat = [
            'daily' => '%Y-%m-%d',
            'monthly' => '%Y-%m',
            'quarterly' => '%Y-Q%q',
            'yearly' => '%Y'
        ];
        
        $expFormat = $expenseFormat[$periodType] ?? '%Y-%m';
        
        $sql = "SELECT 
                    DATE_FORMAT(COALESCE(expense_date, created_at), ?) as period,
                    COALESCE(t.id, 0) as tour_id,
                    COALESCE(t.title, 'Chi phí chung') as tour_name,
                    SUM(amount) as expenses
                FROM tour_expenses e
                LEFT JOIN tours t ON e.tour_id = t.id
                WHERE 1=1";
        
        $params = [$expFormat];
        
        if ($startDate) {
            $sql .= " AND (expense_date >= ? OR expense_date IS NULL)";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND (expense_date <= ? OR expense_date IS NULL)";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY period, COALESCE(t.id, 0), COALESCE(t.title, 'Chi phí chung')";
        
        $expenseData = pdo_query($sql, ...$params);
        
        // Tổng hợp
        $result = [];
        
        foreach ($revenueData as $row) {
            $key = $row['period'] . '_' . $row['tour_id'];
            if (!isset($result[$key])) {
                $result[$key] = [
                    'period' => $row['period'],
                    'tour_id' => $row['tour_id'],
                    'tour_name' => $row['tour_name'],
                    'revenue' => 0,
                    'expenses' => 0,
                    'profit' => 0,
                    'booking_count' => 0
                ];
            }
            $result[$key]['revenue'] = (float)$row['revenue'];
            $result[$key]['booking_count'] = (int)$row['booking_count'];
        }
        
        foreach ($expenseData as $row) {
            $key = $row['period'] . '_' . $row['tour_id'];
            if (!isset($result[$key])) {
                $result[$key] = [
                    'period' => $row['period'],
                    'tour_id' => $row['tour_id'],
                    'tour_name' => $row['tour_name'],
                    'revenue' => 0,
                    'expenses' => 0,
                    'profit' => 0,
                    'booking_count' => 0
                ];
            }
            $result[$key]['expenses'] = (float)$row['expenses'];
        }
        
        // Tính lợi nhuận
        foreach ($result as &$row) {
            $row['profit'] = $row['revenue'] - $row['expenses'];
        }
        
        return array_values($result);
    }

    // Lấy top tour theo lợi nhuận
    public function getTopToursByProfit($limit = 10, $startDate = null, $endDate = null) {
        $data = $this->getProfitByTour(null, $startDate, $endDate);
        return array_slice($data, 0, $limit);
    }

    // Lấy tổng doanh thu, chi phí, lợi nhuận
    public function getTotalSummary($startDate = null, $endDate = null) {
        // Tổng doanh thu
        $sql = "SELECT 
                    SUM(CASE WHEN payment_status IN ('paid', 'partial') THEN total_price ELSE 0 END) as total_revenue,
                    COUNT(*) as total_bookings
                FROM bookings
                WHERE status != 'cancelled'";
        
        $params = [];
        if ($startDate) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $endDate;
        }
        
        $revenue = pdo_query_one($sql, ...$params);
        
        // Tổng chi phí
        $sql = "SELECT SUM(amount) as total_expenses
                FROM tour_expenses
                WHERE 1=1";
        
        $params = [];
        if ($startDate) {
            $sql .= " AND (expense_date >= ? OR expense_date IS NULL)";
            $params[] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND (expense_date <= ? OR expense_date IS NULL)";
            $params[] = $endDate;
        }
        
        $expenses = pdo_query_one($sql, ...$params);
        
        $totalRevenue = (float)($revenue['total_revenue'] ?? 0);
        $totalExpenses = (float)($expenses['total_expenses'] ?? 0);
        $totalProfit = $totalRevenue - $totalExpenses;
        
        return [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'total_profit' => $totalProfit,
            'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
            'total_bookings' => (int)($revenue['total_bookings'] ?? 0)
        ];
    }
}

