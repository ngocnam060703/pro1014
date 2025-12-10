<?php
// Script kiểm tra phân công HDV
require_once "commons/function.php";

$guideId = $_GET['guide_id'] ?? 5; // Mặc định guide_id = 5

echo "<h2>Kiểm tra phân công HDV - Guide ID: $guideId</h2>";

// 1. Kiểm tra tất cả phân công (kể cả cancelled)
echo "<h3>1. Tất cả phân công (kể cả cancelled):</h3>";
$sql1 = "SELECT * FROM guide_assign WHERE guide_id = ? ORDER BY id DESC";
$all = pdo_query($sql1, $guideId);
echo "<pre>";
print_r($all);
echo "</pre>";
echo "<p><strong>Tổng số: " . count($all) . "</strong></p>";

// 2. Kiểm tra phân công chưa hủy
echo "<h3>2. Phân công chưa hủy (status != 'cancelled'):</h3>";
$sql2 = "SELECT * FROM guide_assign WHERE guide_id = ? AND status != 'cancelled' ORDER BY id DESC";
$active = pdo_query($sql2, $guideId);
echo "<pre>";
print_r($active);
echo "</pre>";
echo "<p><strong>Tổng số: " . count($active) . "</strong></p>";

// 3. Kiểm tra phân công mới nhất (tất cả guide)
echo "<h3>3. 10 phân công mới nhất (tất cả HDV):</h3>";
$sql3 = "SELECT ga.*, g.fullname as guide_name 
         FROM guide_assign ga
         LEFT JOIN guides g ON ga.guide_id = g.id
         ORDER BY ga.id DESC LIMIT 10";
$latest = pdo_query($sql3);
echo "<pre>";
print_r($latest);
echo "</pre>";

// 4. Kiểm tra guide có tồn tại không
echo "<h3>4. Thông tin HDV (Guide ID: $guideId):</h3>";
$sql4 = "SELECT * FROM guides WHERE id = ?";
$guide = pdo_query_one($sql4, $guideId);
echo "<pre>";
print_r($guide);
echo "</pre>";

// 5. Kiểm tra phân công với JOIN (giống query thực tế)
echo "<h3>5. Phân công với JOIN (query thực tế):</h3>";
$sql5 = "SELECT 
            ga.*,
            t.id as tour_id,
            t.title as tour_name,
            d.departure_time,
            d.end_date,
            ga.assigned_at,
            ga.assigned_by
        FROM guide_assign ga
        LEFT JOIN departures d ON ga.departure_id = d.id
        LEFT JOIN tours t ON d.tour_id = t.id
        WHERE ga.guide_id = ? AND ga.status != 'cancelled'
        ORDER BY ga.assigned_at DESC, ga.id DESC";
$withJoin = pdo_query($sql5, $guideId);
echo "<pre>";
print_r($withJoin);
echo "</pre>";
echo "<p><strong>Tổng số: " . count($withJoin) . "</strong></p>";

// 6. Kiểm tra xem có phân công nào bị lỗi (departure hoặc tour không tồn tại)
echo "<h3>6. Phân công có vấn đề (departure hoặc tour không tồn tại):</h3>";
$sql6 = "SELECT ga.*, 
                CASE WHEN d.id IS NULL THEN 'Departure không tồn tại' ELSE '' END as dep_error,
                CASE WHEN t.id IS NULL THEN 'Tour không tồn tại' ELSE '' END as tour_error
         FROM guide_assign ga
         LEFT JOIN departures d ON ga.departure_id = d.id
         LEFT JOIN tours t ON d.tour_id = t.id
         WHERE ga.guide_id = ? AND (d.id IS NULL OR t.id IS NULL)";
$errors = pdo_query($sql6, $guideId);
echo "<pre>";
print_r($errors);
echo "</pre>";
echo "<p><strong>Số phân công có lỗi: " . count($errors) . "</strong></p>";

