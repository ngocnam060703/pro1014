-- ============================================
-- THÊM CÁC CỘT GIÁ VÀO BẢNG TOURS
-- ============================================
-- Thêm các trường: adult_price, child_price, infant_price, surcharge
-- ============================================

DELIMITER $$

DROP PROCEDURE IF EXISTS AddTourPricingColumns$$

CREATE PROCEDURE AddTourPricingColumns()
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    
    -- Kiểm tra và thêm cột adult_price
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tours'
    AND COLUMN_NAME = 'adult_price';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tours` 
        ADD COLUMN `adult_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá người lớn';
    END IF;
    
    -- Kiểm tra và thêm cột child_price
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tours'
    AND COLUMN_NAME = 'child_price';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tours` 
        ADD COLUMN `child_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ em';
    END IF;
    
    -- Kiểm tra và thêm cột infant_price
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tours'
    AND COLUMN_NAME = 'infant_price';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tours` 
        ADD COLUMN `infant_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ nhỏ';
    END IF;
    
    -- Kiểm tra và thêm cột surcharge
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tours'
    AND COLUMN_NAME = 'surcharge';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tours` 
        ADD COLUMN `surcharge` decimal(15,2) DEFAULT 0 COMMENT 'Phụ phí (nếu có)';
    END IF;
    
    -- Cập nhật giá từ price sang adult_price nếu adult_price = 0
    UPDATE `tours` 
    SET `adult_price` = `price` 
    WHERE `adult_price` = 0 AND `price` > 0;
    
    SELECT '✅ Đã thêm các cột giá vào bảng tours thành công!' as message;
END$$

DELIMITER ;

-- Chạy procedure để thêm các cột
CALL AddTourPricingColumns();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS AddTourPricingColumns;



