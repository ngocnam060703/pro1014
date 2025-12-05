-- ============================================
-- THÊM TẤT CẢ CÁC CỘT CÒN THIẾU VÀO BẢNG BOOKINGS (AN TOÀN)
-- ============================================
-- Script này sẽ tự động kiểm tra và chỉ thêm các cột chưa tồn tại
-- Không báo lỗi nếu cột đã tồn tại
-- ============================================

DELIMITER $$

DROP PROCEDURE IF EXISTS AddMissingBookingColumns$$

CREATE PROCEDURE AddMissingBookingColumns()
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    
    -- Danh sách các cột cần thêm
    DECLARE columns_to_add TEXT DEFAULT 'departure_id,departure_date,booking_code,booking_type,customer_address,special_requests,notes,company_name,tax_code,num_adults,num_children,num_infants,base_price,adult_price,child_price,infant_price,discount_amount,discount_percentage,deposit_amount,remaining_amount,payment_status,confirmed_at,cancelled_at,cancellation_reason,created_by';
    DECLARE column_name VARCHAR(255);
    DECLARE column_def TEXT;
    DECLARE done INT DEFAULT 0;
    
    -- Cursor để duyệt qua các cột
    DECLARE cur CURSOR FOR 
        SELECT 'departure_id' as col, 'int(11) DEFAULT NULL COMMENT ''ID lịch khởi hành''' as def
        UNION SELECT 'departure_date', 'date DEFAULT NULL COMMENT ''Ngày khởi hành'''
        UNION SELECT 'booking_code', 'varchar(50) DEFAULT NULL COMMENT ''Mã booking tự động'''
        UNION SELECT 'booking_type', 'enum(''individual'',''group'') DEFAULT ''individual'' COMMENT ''Loại booking'''
        UNION SELECT 'customer_address', 'text DEFAULT NULL COMMENT ''Địa chỉ'''
        UNION SELECT 'special_requests', 'text DEFAULT NULL COMMENT ''Yêu cầu đặc biệt'''
        UNION SELECT 'notes', 'text DEFAULT NULL COMMENT ''Ghi chú'''
        UNION SELECT 'company_name', 'varchar(255) DEFAULT NULL COMMENT ''Tên công ty/tổ chức'''
        UNION SELECT 'tax_code', 'varchar(50) DEFAULT NULL COMMENT ''Mã số thuế'''
        UNION SELECT 'num_adults', 'int(11) DEFAULT 0 COMMENT ''Số lượng người lớn'''
        UNION SELECT 'num_children', 'int(11) DEFAULT 0 COMMENT ''Số lượng trẻ em'''
        UNION SELECT 'num_infants', 'int(11) DEFAULT 0 COMMENT ''Số lượng trẻ sơ sinh'''
        UNION SELECT 'base_price', 'decimal(15,2) DEFAULT 0 COMMENT ''Giá cơ bản'''
        UNION SELECT 'adult_price', 'decimal(15,2) DEFAULT 0 COMMENT ''Giá người lớn'''
        UNION SELECT 'child_price', 'decimal(15,2) DEFAULT 0 COMMENT ''Giá trẻ em'''
        UNION SELECT 'infant_price', 'decimal(15,2) DEFAULT 0 COMMENT ''Giá trẻ sơ sinh'''
        UNION SELECT 'discount_amount', 'decimal(15,2) DEFAULT 0 COMMENT ''Số tiền giảm giá'''
        UNION SELECT 'discount_percentage', 'decimal(5,2) DEFAULT 0 COMMENT ''Phần trăm giảm giá'''
        UNION SELECT 'deposit_amount', 'decimal(15,2) DEFAULT 0 COMMENT ''Số tiền đặt cọc'''
        UNION SELECT 'remaining_amount', 'decimal(15,2) DEFAULT 0 COMMENT ''Số tiền còn lại'''
        UNION SELECT 'payment_status', 'enum(''pending'',''partial'',''paid'',''refunded'') DEFAULT ''pending'' COMMENT ''Trạng thái thanh toán'''
        UNION SELECT 'confirmed_at', 'datetime DEFAULT NULL COMMENT ''Thời gian xác nhận'''
        UNION SELECT 'cancelled_at', 'datetime DEFAULT NULL COMMENT ''Thời gian hủy'''
        UNION SELECT 'cancellation_reason', 'text DEFAULT NULL COMMENT ''Lý do hủy'''
        UNION SELECT 'created_by', 'int(11) DEFAULT NULL COMMENT ''ID nhân viên tạo''';
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO column_name, column_def;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Kiểm tra xem cột đã tồn tại chưa
        SELECT COUNT(*) INTO column_exists
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'bookings'
        AND COLUMN_NAME = column_name;
        
        -- Nếu chưa tồn tại thì thêm
        IF column_exists = 0 THEN
            SET @sql = CONCAT('ALTER TABLE `bookings` ADD COLUMN `', column_name, '` ', column_def);
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        END IF;
    END LOOP;
    
    CLOSE cur;
    
    -- Thêm các index
    -- departure_id
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND INDEX_NAME = 'departure_id';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` ADD KEY `departure_id` (`departure_id`);
    END IF;
    
    -- departure_date
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND INDEX_NAME = 'departure_date';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` ADD KEY `departure_date` (`departure_date`);
    END IF;
    
    -- booking_code unique
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND INDEX_NAME = 'booking_code';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` ADD UNIQUE KEY `booking_code` (`booking_code`);
    END IF;
    
    SELECT '✅ Đã cập nhật bảng bookings thành công! Tất cả các cột còn thiếu đã được thêm.' as message;
END$$

DELIMITER ;

-- Chạy procedure
CALL AddMissingBookingColumns();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS AddMissingBookingColumns;

