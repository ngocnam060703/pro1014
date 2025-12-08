-- ============================================
-- CẬP NHẬT HỆ THỐNG NHẬT KÝ HDV
-- ============================================
-- Script này sẽ cập nhật bảng guide_journal và tạo các bảng liên quan
-- ============================================

DELIMITER $$

DROP PROCEDURE IF EXISTS UpdateGuideJournalSystem$$
CREATE PROCEDURE UpdateGuideJournalSystem()
BEGIN
    -- 1. Cập nhật bảng guide_journal với các trường mới
    
    -- Thêm cột journal_date (ngày thực hiện)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'journal_date'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `journal_date` date NOT NULL DEFAULT (CURDATE()) COMMENT 'Ngày thực hiện' AFTER `departure_id`;
    END IF;

    -- Thêm cột activities (hoạt động đã thực hiện)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'activities'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `activities` text DEFAULT NULL COMMENT 'Hoạt động đã thực hiện' AFTER `journal_date`;
    END IF;

    -- Thêm cột completed_attractions (các điểm tham quan hoàn thành)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'completed_attractions'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `completed_attractions` text DEFAULT NULL COMMENT 'Các điểm tham quan hoàn thành' AFTER `activities`;
    END IF;

    -- Thêm cột travel_time (thời gian di chuyển)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'travel_time'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `travel_time` varchar(255) DEFAULT NULL COMMENT 'Thời gian di chuyển' AFTER `completed_attractions`;
    END IF;

    -- Thêm cột customer_status (tình trạng khách)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'customer_status'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `customer_status` text DEFAULT NULL COMMENT 'Tình trạng khách' AFTER `travel_time`;
    END IF;

    -- Thêm cột important_notes (ghi chú quan trọng)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'important_notes'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `important_notes` text DEFAULT NULL COMMENT 'Ghi chú quan trọng' AFTER `customer_status`;
    END IF;

    -- Thêm cột status (trạng thái: pending, approved)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'status'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `status` enum('pending','approved') NOT NULL DEFAULT 'pending' COMMENT 'Trạng thái duyệt' AFTER `important_notes`;
    END IF;

    -- Thêm cột approved_by (người duyệt)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'approved_by'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `approved_by` int(11) DEFAULT NULL COMMENT 'ID người duyệt' AFTER `status`;
    END IF;

    -- Thêm cột approved_at (thời gian duyệt)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND COLUMN_NAME = 'approved_at'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD COLUMN `approved_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian duyệt' AFTER `approved_by`;
    END IF;

    -- Xóa dữ liệu trùng lặp trước khi thêm unique constraint
    -- Lưu ý: Nếu có lỗi duplicate, chạy script fix_duplicate_journal_data.sql trước
    -- Giữ lại bản ghi mới nhất cho mỗi (guide_id, departure_id, journal_date)
    SET @has_duplicates = (
        SELECT COUNT(*) FROM (
            SELECT guide_id, departure_id, journal_date, COUNT(*) as cnt
            FROM guide_journal
            WHERE journal_date IS NOT NULL
            GROUP BY guide_id, departure_id, journal_date
            HAVING cnt > 1
        ) as dup_check
    );
    
    IF @has_duplicates > 0 THEN
        DELETE gj1 FROM guide_journal gj1
        INNER JOIN guide_journal gj2 
        WHERE gj1.id < gj2.id 
        AND gj1.guide_id = gj2.guide_id 
        AND gj1.departure_id = gj2.departure_id
        AND gj1.journal_date = gj2.journal_date;
    END IF;

    -- Thêm unique constraint: một ngày chỉ tạo 1 nhật ký cho mỗi tour
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND CONSTRAINT_NAME = 'unique_guide_departure_date'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD UNIQUE KEY `unique_guide_departure_date` (`guide_id`, `departure_id`, `journal_date`);
    END IF;

    -- Thêm index cho journal_date và status
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.STATISTICS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_journal' 
        AND INDEX_NAME = 'idx_journal_date_status'
    ) THEN
        ALTER TABLE `guide_journal` 
        ADD INDEX `idx_journal_date_status` (`journal_date`, `status`);
    END IF;
END$$

DELIMITER ;

CALL UpdateGuideJournalSystem();
DROP PROCEDURE IF EXISTS UpdateGuideJournalSystem;

-- ============================================
-- 2. TẠO BẢNG GUIDE_JOURNAL_INCIDENTS
-- ============================================
-- Lưu sự cố trong nhật ký

CREATE TABLE IF NOT EXISTS `guide_journal_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_id` int(11) NOT NULL COMMENT 'ID nhật ký',
  `incident_time` datetime DEFAULT NULL COMMENT 'Thời điểm sự cố',
  `description` text NOT NULL COMMENT 'Mô tả chi tiết',
  `affected_customers` text DEFAULT NULL COMMENT 'Khách bị ảnh hưởng',
  `solution` text DEFAULT NULL COMMENT 'Cách xử lý',
  `severity` enum('low','medium','high') NOT NULL DEFAULT 'low' COMMENT 'Mức độ: Nhẹ/Trung bình/Nghiêm trọng',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `journal_id` (`journal_id`),
  CONSTRAINT `fk_journal_incident_journal` FOREIGN KEY (`journal_id`) REFERENCES `guide_journal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sự cố trong nhật ký HDV';

-- ============================================
-- 3. TẠO BẢNG GUIDE_JOURNAL_PHOTOS
-- ============================================
-- Lưu ảnh/video của nhật ký

CREATE TABLE IF NOT EXISTS `guide_journal_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_id` int(11) NOT NULL COMMENT 'ID nhật ký',
  `incident_id` int(11) DEFAULT NULL COMMENT 'ID sự cố (nếu là ảnh sự cố)',
  `file_path` varchar(500) NOT NULL COMMENT 'Đường dẫn file',
  `file_name` varchar(255) NOT NULL COMMENT 'Tên file',
  `file_size` int(11) DEFAULT NULL COMMENT 'Dung lượng (bytes)',
  `file_type` varchar(50) DEFAULT NULL COMMENT 'image/video',
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `journal_id` (`journal_id`),
  KEY `incident_id` (`incident_id`),
  CONSTRAINT `fk_journal_photo_journal` FOREIGN KEY (`journal_id`) REFERENCES `guide_journal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_journal_photo_incident` FOREIGN KEY (`incident_id`) REFERENCES `guide_journal_incidents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Ảnh/video trong nhật ký HDV';

SELECT '✅ Đã cập nhật hệ thống nhật ký HDV thành công!' as message;

