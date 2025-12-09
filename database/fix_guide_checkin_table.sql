-- ============================================
-- SỬA BẢNG GUIDE_CHECKIN
-- ============================================
-- Script này sẽ chuyển đổi bảng guide_checkin từ phiên bản cũ sang phiên bản mới
-- Phiên bản mới: chỉ lưu check-in của HDV cho tour (không cần booking_id)

DELIMITER $$

DROP PROCEDURE IF EXISTS FixGuideCheckinTable$$
CREATE PROCEDURE FixGuideCheckinTable()
BEGIN
    -- Kiểm tra và xóa các cột không cần thiết nếu tồn tại
    -- (Chỉ xóa nếu bảng đang dùng cấu trúc cũ)
    
    -- Xóa cột booking_id nếu tồn tại (không cần cho check-in HDV)
    IF EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_checkin' 
        AND COLUMN_NAME = 'booking_id'
    ) THEN
        -- Xóa foreign key trước (nếu có)
        SET @fk_name = (SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                       WHERE TABLE_SCHEMA = DATABASE() 
                       AND TABLE_NAME = 'guide_checkin' 
                       AND COLUMN_NAME = 'booking_id' 
                       AND CONSTRAINT_NAME != 'PRIMARY'
                       LIMIT 1);
        IF @fk_name IS NOT NULL AND @fk_name != '' THEN
            SET @sql = CONCAT('ALTER TABLE guide_checkin DROP FOREIGN KEY ', @fk_name);
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        END IF;
        
        -- Xóa index booking_id (nếu có)
        SET @index_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
                            WHERE TABLE_SCHEMA = DATABASE() 
                            AND TABLE_NAME = 'guide_checkin' 
                            AND INDEX_NAME = 'booking_id');
        IF @index_exists > 0 THEN
            SET @sql = 'ALTER TABLE guide_checkin DROP INDEX booking_id';
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        END IF;
        
        -- Xóa cột
        ALTER TABLE `guide_checkin` DROP COLUMN `booking_id`;
    END IF;

    -- Xóa cột checkin_time nếu tồn tại (thay bằng checked_in_at)
    IF EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_checkin' 
        AND COLUMN_NAME = 'checkin_time'
    ) THEN
        ALTER TABLE `guide_checkin` DROP COLUMN `checkin_time`;
    END IF;

    -- Xóa cột checkin_location nếu tồn tại
    IF EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_checkin' 
        AND COLUMN_NAME = 'checkin_location'
    ) THEN
        ALTER TABLE `guide_checkin` DROP COLUMN `checkin_location`;
    END IF;

    -- Xóa cột status nếu tồn tại
    IF EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_checkin' 
        AND COLUMN_NAME = 'status'
    ) THEN
        ALTER TABLE `guide_checkin` DROP COLUMN `status`;
    END IF;

    -- Xóa cột notes nếu tồn tại
    IF EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_checkin' 
        AND COLUMN_NAME = 'notes'
    ) THEN
        ALTER TABLE `guide_checkin` DROP COLUMN `notes`;
    END IF;

    -- Thêm cột checked_in_at nếu chưa có
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_checkin' 
        AND COLUMN_NAME = 'checked_in_at'
    ) THEN
        ALTER TABLE `guide_checkin` 
        ADD COLUMN `checked_in_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian check-in' AFTER `departure_id`;
    END IF;

    -- Đảm bảo có unique constraint cho (guide_id, departure_id)
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'guide_checkin' 
        AND CONSTRAINT_NAME = 'unique_guide_departure'
    ) THEN
        ALTER TABLE `guide_checkin` 
        ADD UNIQUE KEY `unique_guide_departure` (`guide_id`, `departure_id`);
    END IF;
END$$

DELIMITER ;

CALL FixGuideCheckinTable();
DROP PROCEDURE IF EXISTS FixGuideCheckinTable;

SELECT '✅ Đã sửa bảng guide_checkin thành công!' as message;



