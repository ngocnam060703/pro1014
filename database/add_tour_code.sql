-- ============================================
-- THÊM CỘT MÃ TOUR (TOUR_CODE) VÀO BẢNG TOURS
-- ============================================
-- Mô tả: Thêm trường tour_code để quản lý mã tour duy nhất
-- ============================================

USE travel_system;

DELIMITER $$

DROP PROCEDURE IF EXISTS AddTourCodeColumn$$
CREATE PROCEDURE AddTourCodeColumn()
BEGIN
    DECLARE columnExists INT DEFAULT 0;
    
    -- Kiểm tra xem cột tour_code đã tồn tại chưa
    SELECT COUNT(*) INTO columnExists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tours'
      AND COLUMN_NAME = 'tour_code';
    
    -- Nếu cột chưa tồn tại thì thêm vào
    IF columnExists = 0 THEN
        ALTER TABLE `tours` 
        ADD COLUMN `tour_code` varchar(50) DEFAULT NULL COMMENT 'Mã tour (duy nhất)';
        
        -- Tạo unique index cho tour_code
        ALTER TABLE `tours` 
        ADD UNIQUE KEY `unique_tour_code` (`tour_code`);
        
        SELECT '✅ Đã thêm trường tour_code vào bảng tours thành công!' as message;
    ELSE
        SELECT 'ℹ Trường tour_code đã tồn tại trong bảng tours.' as message;
    END IF;
END$$

DELIMITER ;

-- Chạy procedure để thêm cột
CALL AddTourCodeColumn();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS AddTourCodeColumn;



