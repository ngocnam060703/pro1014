<?php
/**
 * SCRIPT DEBUG PHÂN CÔNG HDV
 * Truy cập: http://localhost/pro1014/debug_guide_assign.php?guide_id=5
 */

require_once __DIR__ . '/commons/function.php';

// Lấy guide_id từ GET hoặc session
$guide_id = isset($_GET['guide_id']) ? (int)$_GET['guide_id'] : null;

if (!$guide_id) {
    // Thử lấy từ session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['guide_id'])) {
        $guide_id = (int)$_SESSION['guide_id'];
    } else {
        die("❌ Vui lòng cung cấp guide_id: ?guide_id=5");
    }
}

echo "<h1>🔍 DEBUG PHÂN CÔNG HDV - Guide ID: {$guide_id}</h1>";
echo "<hr>";

// 1. Kiểm tra Guide có tồn tại không
echo "<h2>1. Kiểm tra Guide có tồn tại</h2>";
$guide_check = pdo_query_one("SELECT id, fullname, status FROM guides WHERE id = ?", $guide_id);
if ($guide_check) {
    echo "✅ Guide tồn tại: <strong>{$guide_check['fullname']}</strong> (Status: {$guide_check['status']})<br>";
} else {
    echo "❌ Guide ID {$guide_id} KHÔNG TỒN TẠI trong bảng guides!<br>";
    die();
}
echo "<hr>";

// 2. Kiểm tra tất cả phân công của Guide (kể cả cancelled)
echo "<h2>2. Tất cả phân công của Guide ID {$guide_id}</h2>";
$all_assigns = pdo_query("SELECT * FROM guide_assign WHERE guide_id = ? ORDER BY id DESC", $guide_id);
echo "Tổng số phân công (kể cả cancelled): <strong>" . count($all_assigns) . "</strong><br>";
if (count($all_assigns) > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Departure ID</th><th>Tour ID</th><th>Status</th><th>Assigned At</th><th>Assigned By</th></tr>";
    foreach ($all_assigns as $assign) {
        echo "<tr>";
        echo "<td>{$assign['id']}</td>";
        echo "<td>{$assign['departure_id']}</td>";
        echo "<td>{$assign['tour_id']}</td>";
        echo "<td>{$assign['status']}</td>";
        echo "<td>" . ($assign['assigned_at'] ?? 'NULL') . "</td>";
        echo "<td>" . ($assign['assigned_by'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ KHÔNG CÓ PHÂN CÔNG NÀO trong bảng guide_assign cho Guide ID {$guide_id}!<br>";
}
echo "<hr>";

// 3. Kiểm tra phân công chưa hủy (status != 'cancelled')
echo "<h2>3. Phân công chưa hủy (status != 'cancelled')</h2>";
$active_assigns = pdo_query("SELECT * FROM guide_assign WHERE guide_id = ? AND status != 'cancelled' ORDER BY id DESC", $guide_id);
echo "Số phân công chưa hủy: <strong>" . count($active_assigns) . "</strong><br>";
if (count($active_assigns) > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Departure ID</th><th>Tour ID</th><th>Status</th><th>Assigned At</th></tr>";
    foreach ($active_assigns as $assign) {
        echo "<tr>";
        echo "<td>{$assign['id']}</td>";
        echo "<td>{$assign['departure_id']}</td>";
        echo "<td>{$assign['tour_id']}</td>";
        echo "<td>{$assign['status']}</td>";
        echo "<td>" . ($assign['assigned_at'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ KHÔNG CÓ PHÂN CÔNG CHƯA HỦY nào!<br>";
}
echo "<hr>";

// 4. Kiểm tra query giống hệ thống (với LEFT JOIN)
echo "<h2>4. Query giống hệ thống (với LEFT JOIN departures và tours)</h2>";
$system_query = "SELECT 
    ga.*,
    t.id as tour_id,
    t.title as tour_name,
    d.departure_time,
    d.end_date,
    d.end_time,
    d.status as departure_status,
    ga.assigned_at,
    ga.assigned_by
FROM guide_assign ga
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
WHERE ga.guide_id = ? 
  AND ga.status != 'cancelled'
ORDER BY 
    ga.assigned_at DESC,
    ga.id DESC";

$system_results = pdo_query($system_query, $guide_id);
echo "Số kết quả từ query hệ thống: <strong>" . count($system_results) . "</strong><br>";

if (count($system_results) > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>GA ID</th><th>Departure ID</th><th>Tour Name</th><th>Departure Time</th><th>Status</th><th>Assigned At</th></tr>";
    foreach ($system_results as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['departure_id']}</td>";
        echo "<td>" . ($row['tour_name'] ?? 'NULL') . "</td>";
        echo "<td>" . ($row['departure_time'] ?? 'NULL') . "</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>" . ($row['assigned_at'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ Query hệ thống trả về 0 kết quả!<br>";
    
    // Kiểm tra xem có phải do LEFT JOIN không?
    if (count($active_assigns) > 0) {
        echo "<br>⚠️ <strong>PHÁT HIỆN VẤN ĐỀ:</strong> Có {$active_assigns[0]['id']} phân công nhưng query với JOIN trả về 0!<br>";
        echo "Có thể do:<br>";
        echo "- Departure ID {$active_assigns[0]['departure_id']} không tồn tại trong bảng departures<br>";
        echo "- Hoặc Tour ID không tồn tại<br>";
        
        // Kiểm tra departure
        if (isset($active_assigns[0]['departure_id'])) {
            $dep_check = pdo_query_one("SELECT id, tour_id, departure_time FROM departures WHERE id = ?", $active_assigns[0]['departure_id']);
            if ($dep_check) {
                echo "✅ Departure ID {$active_assigns[0]['departure_id']} TỒN TẠI (Tour ID: {$dep_check['tour_id']})<br>";
            } else {
                echo "❌ Departure ID {$active_assigns[0]['departure_id']} KHÔNG TỒN TẠI!<br>";
            }
        }
    }
}
echo "<hr>";

// 5. Kiểm tra phân công mới nhất (tất cả HDV)
echo "<h2>5. 10 phân công mới nhất (tất cả HDV)</h2>";
$latest_assigns = pdo_query("SELECT ga.*, g.fullname as guide_name FROM guide_assign ga LEFT JOIN guides g ON ga.guide_id = g.id ORDER BY ga.id DESC LIMIT 10");
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Guide ID</th><th>Guide Name</th><th>Departure ID</th><th>Status</th><th>Assigned At</th></tr>";
foreach ($latest_assigns as $assign) {
    $highlight = ($assign['guide_id'] == $guide_id) ? "style='background-color: yellow;'" : "";
    echo "<tr {$highlight}>";
    echo "<td>{$assign['id']}</td>";
    echo "<td>{$assign['guide_id']}</td>";
    echo "<td>" . ($assign['guide_name'] ?? 'NULL') . "</td>";
    echo "<td>{$assign['departure_id']}</td>";
    echo "<td>{$assign['status']}</td>";
    echo "<td>" . ($assign['assigned_at'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<hr>";

// 6. Kiểm tra session
echo "<h2>6. Thông tin Session</h2>";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo "Session ID: " . session_id() . "<br>";
echo "Guide ID từ session: " . (isset($_SESSION['guide_id']) ? $_SESSION['guide_id'] : 'KHÔNG CÓ') . "<br>";
echo "User ID từ session: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'KHÔNG CÓ') . "<br>";
echo "Role từ session: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'KHÔNG CÓ') . "<br>";
echo "<hr>";

// 7. Tóm tắt
echo "<h2>📊 TÓM TẮT</h2>";
echo "<ul>";
echo "<li>Guide ID: <strong>{$guide_id}</strong></li>";
echo "<li>Tổng phân công (kể cả cancelled): <strong>" . count($all_assigns) . "</strong></li>";
echo "<li>Phân công chưa hủy: <strong>" . count($active_assigns) . "</strong></li>";
echo "<li>Kết quả query hệ thống: <strong>" . count($system_results) . "</strong></li>";
echo "</ul>";

if (count($all_assigns) == 0) {
    echo "<div style='background: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin-top: 20px;'>";
    echo "<strong>❌ VẤN ĐỀ:</strong> Không có phân công nào trong database cho Guide ID {$guide_id}!<br>";
    echo "Cần kiểm tra:<br>";
    echo "1. Admin đã tạo phân công chưa?<br>";
    echo "2. Guide ID trong form phân công có đúng không?<br>";
    echo "3. Form có submit thành công không?<br>";
    echo "</div>";
} elseif (count($active_assigns) == 0) {
    echo "<div style='background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin-top: 20px;'>";
    echo "<strong>⚠️ VẤN ĐỀ:</strong> Tất cả phân công đều bị cancelled!<br>";
    echo "</div>";
} elseif (count($system_results) == 0 && count($active_assigns) > 0) {
    echo "<div style='background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin-top: 20px;'>";
    echo "<strong>⚠️ VẤN ĐỀ:</strong> Có phân công nhưng query với JOIN trả về 0!<br>";
    echo "Có thể do departure hoặc tour không tồn tại.<br>";
    echo "</div>";
} else {
    echo "<div style='background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50; margin-top: 20px;'>";
    echo "<strong>✅ OK:</strong> Có " . count($system_results) . " phân công hợp lệ!<br>";
    echo "</div>";
}
?>

