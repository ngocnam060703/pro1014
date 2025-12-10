<?php
// Test script để kiểm tra phân công HDV
require_once "commons/function.php";

if (session_status() == PHP_SESSION_NONE) session_start();

// Lấy guide_id từ session hoặc từ GET
$guideId = $_GET['guide_id'] ?? $_SESSION['guide']['id'] ?? 0;

if (!$guideId) {
    die("Vui lòng cung cấp guide_id hoặc đăng nhập HDV");
}

echo "<h2>Test Phân công HDV - Guide ID: $guideId</h2>";

// 1. Kiểm tra phân công trong guide_assign
echo "<h3>1. Phân công trong guide_assign:</h3>";
$sql1 = "SELECT * FROM guide_assign WHERE guide_id = ? ORDER BY id DESC LIMIT 10";
$assignments = pdo_query($sql1, $guideId);
echo "<pre>";
print_r($assignments);
echo "</pre>";

// 2. Kiểm tra với JOIN
echo "<h3>2. Phân công với JOIN (giống query thực tế):</h3>";
$sql2 = "SELECT 
            ga.*,
            t.id as tour_id,
            t.title as tour_name,
            d.departure_time,
            d.end_date,
            ga.assigned_at,
            ga.assigned_by
        FROM guide_assign ga
        INNER JOIN departures d ON ga.departure_id = d.id
        INNER JOIN tours t ON d.tour_id = t.id
        WHERE ga.guide_id = ? AND ga.status != 'cancelled'
        ORDER BY ga.assigned_at DESC, ga.id DESC";
$assignmentsWithJoin = pdo_query($sql2, $guideId);
echo "<pre>";
print_r($assignmentsWithJoin);
echo "</pre>";

// 3. Kiểm tra phân công mới nhất
echo "<h3>3. Phân công mới nhất:</h3>";
$sql3 = "SELECT * FROM guide_assign WHERE guide_id = ? ORDER BY assigned_at DESC, id DESC LIMIT 1";
$latest = pdo_query_one($sql3, $guideId);
echo "<pre>";
print_r($latest);
echo "</pre>";

// 4. Đếm tổng số phân công
echo "<h3>4. Tổng số phân công:</h3>";
$sql4 = "SELECT COUNT(*) as total FROM guide_assign WHERE guide_id = ? AND status != 'cancelled'";
$total = pdo_query_one($sql4, $guideId);
echo "<pre>";
print_r($total);
echo "</pre>";

// 5. Kiểm tra session
echo "<h3>5. Session hiện tại:</h3>";
echo "<pre>";
print_r($_SESSION['guide'] ?? []);
echo "</pre>";

