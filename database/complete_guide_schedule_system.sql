-- ============================================
-- HỆ THỐNG LỊCH LÀM VIỆC HDV - TẤT CẢ MÃ SQL
-- ============================================
-- File này chứa TẤT CẢ các SQL cần thiết cho hệ thống lịch làm việc HDV
-- Chạy file này để thiết lập hoàn chỉnh hệ thống
-- ============================================

-- ============================================
-- 1. CẬP NHẬT HỆ THỐNG PHÂN CÔNG HDV
-- ============================================
-- Thêm assigned_by, reason, cập nhật status enum, tạo bảng log

DELIMITER $$

DROP PROCEDURE IF EXISTS UpdateGuideAssignTable$$
CREATE PROCEDURE UpdateGuideAssignTable()
BEGIN
    -- Thêm cột assigned_by nếu chưa có
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_assign' 
        AND COLUMN_NAME = 'assigned_by'
    ) THEN
        ALTER TABLE `guide_assign` 
        ADD COLUMN `assigned_by` int(11) DEFAULT NULL COMMENT 'ID người phân công' AFTER `assigned_at`;
    END IF;

    -- Thêm cột reason nếu chưa có
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_assign' 
        AND COLUMN_NAME = 'reason'
    ) THEN
        ALTER TABLE `guide_assign` 
        ADD COLUMN `reason` text DEFAULT NULL COMMENT 'Lý do phân công' AFTER `note`;
    END IF;

    -- Cập nhật status enum để thêm 'paused' (Tạm dừng)
    ALTER TABLE `guide_assign` 
    MODIFY COLUMN `status` enum('scheduled','in_progress','completed','pending','paused','cancelled') 
    DEFAULT 'scheduled' COMMENT 'Trạng thái phân công';
END$$

DELIMITER ;

CALL UpdateGuideAssignTable();
DROP PROCEDURE IF EXISTS UpdateGuideAssignTable;

-- ============================================
-- 2. TẠO BẢNG GUIDE_ASSIGN_LOG
-- ============================================
-- Lưu nhật ký thay đổi phân công HDV

CREATE TABLE IF NOT EXISTS `guide_assign_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL COMMENT 'ID phân công',
  `old_guide_id` int(11) DEFAULT NULL COMMENT 'HDV cũ',
  `new_guide_id` int(11) DEFAULT NULL COMMENT 'HDV mới',
  `old_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái cũ',
  `new_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái mới',
  `old_note` text DEFAULT NULL COMMENT 'Ghi chú cũ',
  `new_note` text DEFAULT NULL COMMENT 'Ghi chú mới',
  `change_type` enum('created','guide_changed','status_changed','note_changed','deleted') NOT NULL COMMENT 'Loại thay đổi',
  `changed_by` int(11) DEFAULT NULL COMMENT 'ID người thay đổi',
  `change_reason` text DEFAULT NULL COMMENT 'Lý do thay đổi',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `changed_by` (`changed_by`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_assign_log_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `guide_assign` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhật ký thay đổi phân công HDV';

-- ============================================
-- 3. TẠO BẢNG GUIDE_CHECKIN
-- ============================================
-- Lưu thông tin check-in/nhận tour của HDV

CREATE TABLE IF NOT EXISTS `guide_checkin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL COMMENT 'ID hướng dẫn viên',
  `departure_id` int(11) NOT NULL COMMENT 'ID lịch khởi hành',
  `checked_in_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian check-in',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_guide_departure` (`guide_id`, `departure_id`),
  KEY `guide_id` (`guide_id`),
  KEY `departure_id` (`departure_id`),
  CONSTRAINT `fk_checkin_guide` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_checkin_departure` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu thông tin check-in của HDV';

-- ============================================
-- 4. ĐẢM BẢO BẢNG GUIDE_ASSIGN TỒN TẠI
-- ============================================
-- Tạo bảng guide_assign nếu chưa có (cơ bản)

CREATE TABLE IF NOT EXISTS `guide_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL,
  `departure_id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `meeting_point` varchar(255) DEFAULT NULL,
  `max_people` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `reason` text DEFAULT NULL COMMENT 'Lý do phân công',
  `status` enum('scheduled','in_progress','completed','pending','paused','cancelled') DEFAULT 'scheduled',
  `assigned_at` timestamp NULL DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL COMMENT 'ID người phân công',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_id` (`guide_id`),
  KEY `departure_id` (`departure_id`),
  KEY `tour_id` (`tour_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng phân công HDV cho lịch khởi hành';

-- ============================================
-- 5. TẠO INDEX ĐỂ TỐI ƯU HIỆU SUẤT (Tùy chọn)
-- ============================================
-- Lưu ý: Chạy từng câu lệnh riêng, bỏ qua lỗi nếu index đã tồn tại

-- Index cho guide_assign (chạy từng câu, bỏ qua lỗi nếu đã tồn tại)
-- Nếu index chưa tồn tại, bỏ comment và chạy:

-- CREATE INDEX `idx_guide_assign_guide_status` ON `guide_assign` (`guide_id`, `status`);
-- CREATE INDEX `idx_guide_assign_departure` ON `guide_assign` (`departure_id`);
-- CREATE INDEX `idx_guide_assign_tour` ON `guide_assign` (`tour_id`);

-- ============================================
-- HOÀN TẤT
-- ============================================

SELECT '✅ Đã thiết lập hoàn chỉnh hệ thống lịch làm việc HDV!' as message;
SELECT '✅ Bảng guide_assign đã được cập nhật!' as message;
SELECT '✅ Bảng guide_assign_log đã được tạo!' as message;
SELECT '✅ Bảng guide_checkin đã được tạo!' as message;

