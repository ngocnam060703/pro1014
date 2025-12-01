<?php
require_once "inc/pdo.php"; // kết nối database

// Lấy số tour HDV đã được phân công
function getCountToursByGuide($guide_id) {
    global $pdo;
    $sql = "SELECT COUNT(*) FROM guide_assign WHERE guide_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$guide_id]);
    return $stmt->fetchColumn();
}

// Lấy số nhật ký HDV đã gửi
function getCountLogsByGuide($guide_id) {
    global $pdo;
    $sql = "SELECT COUNT(*) FROM diaries WHERE guide_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$guide_id]);
    return $stmt->fetchColumn();
}

// Lấy lịch trình của HDV
function getScheduleByGuide($guide_id) {
    global $pdo;
    $sql = "SELECT * FROM schedules WHERE guide_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$guide_id]);
    return $stmt->fetchAll();
}

// Lấy nhật ký của HDV
function getLogsByGuide($guide_id) {
    global $pdo;
    $sql = "SELECT * FROM diaries WHERE guide_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$guide_id]);
    return $stmt->fetchAll();
}
// Lấy danh sách tour HDV được phân công
// Lấy danh sách tour HDV được phân công, bao gồm ngày khởi hành, điểm tập trung, số người tối đa, ghi chú
function getToursByGuide($guide_id) {
    global $pdo;
    $sql = "SELECT 
                t.id AS tour_id,
                t.title AS tour_name,
                t.departure AS departure_location,
                ga.departure_date,
                ga.meeting_point,
                ga.max_people,
                ga.note
            FROM guide_assign ga
            INNER JOIN tours t ON t.id = ga.tour_id
            WHERE ga.guide_id = ?
            ORDER BY ga.departure_date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$guide_id]);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tạo trạng thái tự động
    $today = date('Y-m-d');
    foreach ($tours as &$tour) {
        $start = $tour['departure_date']; // ngày khởi hành HDV
        $tour['status'] = ($today < $start) ? 'Chưa bắt đầu' : 'Đang diễn ra';
    }
    return $tours;
}




// Lấy tất cả file của HDV
function getGuideFiles($guide_id){
    global $pdo;
    $sql = "SELECT * FROM guide_files WHERE guide_id = ? ORDER BY uploaded_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$guide_id]);
    return $stmt->fetchAll();
}

// Thêm file mới
function addGuideFile($guide_id, $filename, $description = ''){
    global $pdo;
    $sql = "INSERT INTO guide_files (guide_id, filename, description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$guide_id, $filename, $description]);
}