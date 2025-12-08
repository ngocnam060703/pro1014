-- ============================================
-- CẬP NHẬT TRẠNG THÁI LỊCH KHỞI HÀNH
-- ============================================
-- File này cập nhật enum status của bảng departures
-- để thêm các trạng thái mới: open, upcoming
-- ============================================

USE travel_system;

DELIMITER $$

-- Stored procedure để kiểm tra và cập nhật enum
DROP PROCEDURE IF EXISTS UpdateDepartureStatusEnum$$
CREATE PROCEDURE UpdateDepartureStatusEnum()
BEGIN
    DECLARE enumExists INT DEFAULT 0;
    
    -- Kiểm tra xem enum đã có giá trị 'open' chưa
    SELECT COUNT(*) INTO enumExists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'departures'
      AND COLUMN_NAME = 'status'
      AND COLUMN_TYPE LIKE '%open%';
    
    -- Nếu chưa có, cập nhật enum
    IF enumExists = 0 THEN
        ALTER TABLE `departures` 
        MODIFY COLUMN `status` enum(
            'open',           -- Đang mở bán
            'upcoming',       -- Sắp khởi hành
            'scheduled',      -- Đã lên lịch (giữ để tương thích)
            'confirmed',      -- Đã xác nhận (giữ để tương thích)
            'in_progress',    -- Đang chạy
            'completed',      -- Đã hoàn thành
            'cancelled'       -- Đã hủy
        ) DEFAULT 'open' COMMENT 'Trạng thái lịch khởi hành';
    END IF;
END$$

DELIMITER ;

-- Chạy stored procedure
CALL UpdateDepartureStatusEnum();

-- Xóa stored procedure
DROP PROCEDURE IF EXISTS UpdateDepartureStatusEnum;

-- Map các giá trị cũ sang giá trị mới (tùy chọn - bỏ comment nếu muốn chuyển đổi)
-- scheduled -> open
-- UPDATE `departures` SET `status` = 'open' WHERE `status` = 'scheduled';

-- confirmed -> upcoming  
-- UPDATE `departures` SET `status` = 'upcoming' WHERE `status` = 'confirmed';

-- Đảm bảo các cột cần thiết tồn tại
DELIMITER $$

DROP PROCEDURE IF EXISTS AddColumnIfNotExists$$
CREATE PROCEDURE AddColumnIfNotExists(
    IN tableName VARCHAR(255),
    IN columnName VARCHAR(255),
    IN columnDefinition TEXT
)
BEGIN
    DECLARE columnExists INT DEFAULT 0;
    
    SELECT COUNT(*) INTO columnExists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = tableName
      AND COLUMN_NAME = columnName;
    
    IF columnExists = 0 THEN
        SET @sql = CONCAT('ALTER TABLE `', tableName, '` ADD COLUMN `', columnName, '` ', columnDefinition);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

-- Đảm bảo cột end_date tồn tại
CALL AddColumnIfNotExists('departures', 'end_date', 'date DEFAULT NULL COMMENT ''Ngày kết thúc''');

-- Đảm bảo các cột số chỗ tồn tại
CALL AddColumnIfNotExists('departures', 'total_seats', 'int(11) DEFAULT 0 COMMENT ''Tổng số chỗ''');
CALL AddColumnIfNotExists('departures', 'seats_available', 'int(11) DEFAULT 0 COMMENT ''Số chỗ còn trống''');
CALL AddColumnIfNotExists('departures', 'seats_booked', 'int(11) DEFAULT 0 COMMENT ''Số chỗ đã đặt''');

-- Xóa stored procedure
DROP PROCEDURE IF EXISTS AddColumnIfNotExists;
