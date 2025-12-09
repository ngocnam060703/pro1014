-- ============================================
-- CẬP NHẬT HỆ THỐNG PHÂN CÔNG HDV
-- ============================================

-- 1. Cập nhật bảng guide_assign: thêm assigned_by, reason, cập nhật status enum
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

-- 2. Tạo bảng guide_assign_log để lưu nhật ký thay đổi
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

SELECT '✅ Đã cập nhật hệ thống phân công HDV thành công!' as message;



