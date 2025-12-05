-- ============================================
-- CẬP NHẬT BẢNG BOOKINGS - THÊM CÁC CỘT CÒN THIẾU
-- ============================================
-- Script này sẽ thêm các cột mới vào bảng bookings nếu chưa tồn tại
-- ============================================

DELIMITER $$

DROP PROCEDURE IF EXISTS UpdateBookingsTable$$

CREATE PROCEDURE UpdateBookingsTable()
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    
    -- Kiểm tra và thêm cột departure_id
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND COLUMN_NAME = 'departure_id';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` 
        ADD COLUMN `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành';
        ALTER TABLE `bookings` ADD KEY `departure_id` (`departure_id`);
    END IF;
    
    -- Kiểm tra và thêm cột departure_date
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND COLUMN_NAME = 'departure_date';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` 
        ADD COLUMN `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành';
        ALTER TABLE `bookings` ADD KEY `departure_date` (`departure_date`);
    END IF;
    
    -- Kiểm tra và thêm cột booking_code
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND COLUMN_NAME = 'booking_code';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` 
        ADD COLUMN `booking_code` varchar(50) DEFAULT NULL COMMENT 'Mã booking tự động';
        ALTER TABLE `bookings` ADD UNIQUE KEY `booking_code` (`booking_code`);
    END IF;
    
    -- Kiểm tra và thêm cột booking_type
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND COLUMN_NAME = 'booking_type';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` 
        ADD COLUMN `booking_type` enum('individual','group') DEFAULT 'individual' COMMENT 'Loại booking';
    END IF;
    
    -- Kiểm tra và thêm các cột khác
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'bookings'
    AND COLUMN_NAME = 'company_name';
    
    IF column_exists = 0 THEN
        ALTER TABLE `bookings` 
        ADD COLUMN `company_name` varchar(255) DEFAULT NULL COMMENT 'Tên công ty/tổ chức',
        ADD COLUMN `tax_code` varchar(50) DEFAULT NULL COMMENT 'Mã số thuế',
        ADD COLUMN `num_adults` int(11) DEFAULT 0 COMMENT 'Số lượng người lớn',
        ADD COLUMN `num_children` int(11) DEFAULT 0 COMMENT 'Số lượng trẻ em',
        ADD COLUMN `num_infants` int(11) DEFAULT 0 COMMENT 'Số lượng trẻ sơ sinh',
        ADD COLUMN `base_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá cơ bản',
        ADD COLUMN `adult_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá người lớn',
        ADD COLUMN `child_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ em',
        ADD COLUMN `infant_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ sơ sinh',
        ADD COLUMN `discount_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền giảm giá',
        ADD COLUMN `discount_percentage` decimal(5,2) DEFAULT 0 COMMENT 'Phần trăm giảm giá',
        ADD COLUMN `deposit_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền đặt cọc',
        ADD COLUMN `remaining_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền còn lại',
        ADD COLUMN `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
        ADD COLUMN `confirmed_at` datetime DEFAULT NULL COMMENT 'Thời gian xác nhận',
        ADD COLUMN `cancelled_at` datetime DEFAULT NULL COMMENT 'Thời gian hủy',
        ADD COLUMN `cancellation_reason` text DEFAULT NULL COMMENT 'Lý do hủy',
        ADD COLUMN `created_by` int(11) DEFAULT NULL COMMENT 'ID nhân viên tạo';
    END IF;
    
    SELECT '✅ Đã cập nhật bảng bookings thành công!' as message;
END$$

DELIMITER ;

-- Chạy procedure để cập nhật
CALL UpdateBookingsTable();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS UpdateBookingsTable;

