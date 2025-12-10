-- ============================================
-- CẤU TRÚC BẢNG guide_assign
-- ============================================
-- LƯU Ý: Đảm bảo đã chọn đúng database trước khi chạy các query này
-- Hoặc thay DATABASE() bằng tên database của bạn, ví dụ: 'pro1014'

-- 1. Xem cấu trúc bảng (DESCRIBE)
DESCRIBE guide_assign;

-- 2. Xem câu lệnh CREATE TABLE hiện tại
SHOW CREATE TABLE guide_assign;

-- 3. Xem tất cả dữ liệu trong bảng
SELECT * FROM guide_assign ORDER BY id DESC;

-- 4. Đếm số bản ghi
SELECT COUNT(*) as total_records FROM guide_assign;

-- 5. Xem các cột và kiểu dữ liệu chi tiết
SELECT 
    COLUMN_NAME as 'Tên cột',
    DATA_TYPE as 'Kiểu dữ liệu',
    CHARACTER_MAXIMUM_LENGTH as 'Độ dài tối đa',
    IS_NULLABLE as 'Cho phép NULL',
    COLUMN_DEFAULT as 'Giá trị mặc định',
    COLUMN_KEY as 'Khóa',
    EXTRA as 'Thông tin thêm'
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'guide_assign'
ORDER BY ORDINAL_POSITION;

-- 6. Xem các ràng buộc (constraints) và khóa ngoại
SELECT 
    tc.CONSTRAINT_NAME as 'Tên ràng buộc',
    tc.CONSTRAINT_TYPE as 'Loại',
    kcu.TABLE_NAME as 'Bảng',
    kcu.COLUMN_NAME as 'Cột',
    kcu.REFERENCED_TABLE_NAME as 'Bảng tham chiếu',
    kcu.REFERENCED_COLUMN_NAME as 'Cột tham chiếu'
FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu 
    ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME 
    AND tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA
    AND tc.TABLE_NAME = kcu.TABLE_NAME
WHERE tc.TABLE_SCHEMA = DATABASE()
  AND tc.TABLE_NAME = 'guide_assign'
ORDER BY tc.CONSTRAINT_TYPE, tc.CONSTRAINT_NAME;

-- 7. Xem các chỉ mục (indexes)
-- Cách 1: Sử dụng SHOW INDEX (cần chọn database trước)
SHOW INDEX FROM guide_assign;

-- Cách 2: Sử dụng INFORMATION_SCHEMA (không cần chọn database)
SELECT 
    INDEX_NAME as 'Tên chỉ mục',
    COLUMN_NAME as 'Tên cột',
    SEQ_IN_INDEX as 'Thứ tự',
    NON_UNIQUE as 'Không duy nhất',
    INDEX_TYPE as 'Loại chỉ mục',
    COLLATION as 'Collation'
FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'guide_assign'
ORDER BY INDEX_NAME, SEQ_IN_INDEX;

-- 7b. Xem foreign keys (cách đơn giản hơn)
SELECT 
    CONSTRAINT_NAME as 'Tên ràng buộc',
    TABLE_NAME as 'Bảng',
    COLUMN_NAME as 'Cột',
    REFERENCED_TABLE_NAME as 'Bảng tham chiếu',
    REFERENCED_COLUMN_NAME as 'Cột tham chiếu'
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'guide_assign'
  AND REFERENCED_TABLE_NAME IS NOT NULL;

-- ============================================
-- CẤU TRÚC BẢNG guide_assign (theo code)
-- ============================================

-- Cấu trúc ban đầu (từ install_tables.php):
/*
CREATE TABLE IF NOT EXISTS `guide_assign` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `guide_id` int(11) NOT NULL COMMENT 'ID của hướng dẫn viên',
    `departure_id` int(11) NOT NULL COMMENT 'ID của lịch khởi hành',
    `tour_id` int(11) DEFAULT NULL COMMENT 'ID của tour',
    `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành',
    `meeting_point` varchar(255) DEFAULT NULL COMMENT 'Điểm hẹn',
    `max_people` int(11) DEFAULT NULL COMMENT 'Số người tối đa',
    `note` text DEFAULT NULL COMMENT 'Ghi chú',
    `status` enum('scheduled','in_progress','completed','pending','cancelled') DEFAULT 'scheduled' COMMENT 'Trạng thái',
    `assigned_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian phân công',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    PRIMARY KEY (`id`),
    KEY `guide_id` (`guide_id`),
    KEY `departure_id` (`departure_id`),
    KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/

-- Cấu trúc đầy đủ (có thể đã được cập nhật thêm các cột):
/*
CREATE TABLE IF NOT EXISTS `guide_assign` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `guide_id` int(11) NOT NULL COMMENT 'ID của hướng dẫn viên',
    `departure_id` int(11) NOT NULL COMMENT 'ID của lịch khởi hành',
    `tour_id` int(11) DEFAULT NULL COMMENT 'ID của tour',
    `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành',
    `meeting_point` varchar(255) DEFAULT NULL COMMENT 'Điểm hẹn',
    `max_people` int(11) DEFAULT NULL COMMENT 'Số người tối đa',
    `note` text DEFAULT NULL COMMENT 'Ghi chú',
    `reason` varchar(255) DEFAULT NULL COMMENT 'Lý do',
    `status` enum('scheduled','in_progress','completed','pending','cancelled','assigned') DEFAULT 'scheduled' COMMENT 'Trạng thái',
    `assigned_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian phân công',
    `assigned_by` int(11) DEFAULT NULL COMMENT 'ID người phân công (user_id)',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
    PRIMARY KEY (`id`),
    KEY `idx_guide_id` (`guide_id`),
    KEY `idx_departure_id` (`departure_id`),
    KEY `idx_tour_id` (`tour_id`),
    KEY `idx_status` (`status`),
    KEY `idx_assigned_at` (`assigned_at`),
    CONSTRAINT `fk_guide_assign_guide` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_guide_assign_departure` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_guide_assign_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_guide_assign_user` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/

-- ============================================
-- CÁC QUERY KIỂM TRA DỮ LIỆU
-- ============================================

-- 8. Xem 10 phân công mới nhất
SELECT 
    ga.id,
    ga.guide_id,
    g.fullname as guide_name,
    ga.departure_id,
    ga.tour_id,
    t.title as tour_name,
    ga.status,
    ga.assigned_at,
    ga.assigned_by,
    u.full_name as assigned_by_name
FROM guide_assign ga
LEFT JOIN guides g ON ga.guide_id = g.id
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
LEFT JOIN users u ON ga.assigned_by = u.id
ORDER BY ga.id DESC
LIMIT 10;

-- 9. Kiểm tra phân công của Guide ID = 5
SELECT 
    ga.*,
    g.fullname as guide_name,
    t.title as tour_name,
    d.departure_time
FROM guide_assign ga
LEFT JOIN guides g ON ga.guide_id = g.id
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
WHERE ga.guide_id = 5
ORDER BY ga.id DESC;

-- 10. Thống kê theo trạng thái
SELECT 
    status,
    COUNT(*) as count
FROM guide_assign
GROUP BY status
ORDER BY count DESC;

-- 11. Kiểm tra các cột có thể bị NULL
SELECT 
    COUNT(*) as total,
    COUNT(guide_id) as has_guide_id,
    COUNT(departure_id) as has_departure_id,
    COUNT(tour_id) as has_tour_id,
    COUNT(assigned_at) as has_assigned_at,
    COUNT(assigned_by) as has_assigned_by
FROM guide_assign;
