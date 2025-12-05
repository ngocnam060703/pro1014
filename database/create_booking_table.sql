-- ============================================
-- TẠO/CẬP NHẬT BẢNG BOOKINGS CHO HỆ THỐNG BOOKING
-- ============================================
-- Hỗ trợ booking cho khách lẻ (1-2 người) và đoàn (nhiều người)
-- ============================================

-- Tạo bảng bookings nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_code` varchar(50) DEFAULT NULL COMMENT 'Mã booking tự động',
  `tour_id` int(11) NOT NULL COMMENT 'ID tour',
  `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành',
  `booking_type` enum('individual','group') DEFAULT 'individual' COMMENT 'Loại booking: khách lẻ hoặc đoàn',
  `customer_name` varchar(255) NOT NULL COMMENT 'Tên khách hàng/người đại diện',
  `customer_email` varchar(255) NOT NULL COMMENT 'Email khách hàng',
  `customer_phone` varchar(50) NOT NULL COMMENT 'Số điện thoại',
  `customer_address` text DEFAULT NULL COMMENT 'Địa chỉ',
  `company_name` varchar(255) DEFAULT NULL COMMENT 'Tên công ty/tổ chức (nếu là đoàn)',
  `tax_code` varchar(50) DEFAULT NULL COMMENT 'Mã số thuế (nếu là đoàn)',
  `num_adults` int(11) DEFAULT 0 COMMENT 'Số lượng người lớn',
  `num_children` int(11) DEFAULT 0 COMMENT 'Số lượng trẻ em',
  `num_infants` int(11) DEFAULT 0 COMMENT 'Số lượng trẻ sơ sinh',
  `num_people` int(11) NOT NULL COMMENT 'Tổng số người',
  `booking_date` date NOT NULL COMMENT 'Ngày đặt tour',
  `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành',
  `special_requests` text DEFAULT NULL COMMENT 'Yêu cầu đặc biệt',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  `base_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá cơ bản',
  `adult_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá người lớn',
  `child_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ em',
  `infant_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ sơ sinh',
  `discount_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền giảm giá',
  `discount_percentage` decimal(5,2) DEFAULT 0 COMMENT 'Phần trăm giảm giá',
  `total_price` decimal(15,2) NOT NULL COMMENT 'Tổng tiền',
  `deposit_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền đặt cọc',
  `remaining_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền còn lại',
  `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending' COMMENT 'Trạng thái booking',
  `confirmed_at` datetime DEFAULT NULL COMMENT 'Thời gian xác nhận',
  `cancelled_at` datetime DEFAULT NULL COMMENT 'Thời gian hủy',
  `cancellation_reason` text DEFAULT NULL COMMENT 'Lý do hủy',
  `created_by` int(11) DEFAULT NULL COMMENT 'ID nhân viên tạo booking',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_code` (`booking_code`),
  KEY `tour_id` (`tour_id`),
  KEY `departure_id` (`departure_id`),
  KEY `booking_type` (`booking_type`),
  KEY `status` (`status`),
  KEY `payment_status` (`payment_status`),
  KEY `booking_date` (`booking_date`),
  KEY `departure_date` (`departure_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng booking_guests để lưu thông tin chi tiết từng khách (cho đoàn)
CREATE TABLE IF NOT EXISTS `booking_guests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `guest_type` enum('adult','child','infant') DEFAULT 'adult' COMMENT 'Loại khách',
  `full_name` varchar(255) NOT NULL COMMENT 'Họ tên đầy đủ',
  `date_of_birth` date DEFAULT NULL COMMENT 'Ngày sinh',
  `gender` enum('male','female','other') DEFAULT NULL COMMENT 'Giới tính',
  `id_card` varchar(50) DEFAULT NULL COMMENT 'CMND/CCCD/Passport',
  `phone` varchar(50) DEFAULT NULL COMMENT 'Số điện thoại',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email',
  `special_notes` text DEFAULT NULL COMMENT 'Ghi chú đặc biệt',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `guest_type` (`guest_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo function tự động tạo mã booking
DELIMITER $$

DROP FUNCTION IF EXISTS GenerateBookingCode$$

CREATE FUNCTION GenerateBookingCode() RETURNS VARCHAR(50)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE new_code VARCHAR(50);
    DECLARE code_exists INT DEFAULT 1;
    
    WHILE code_exists > 0 DO
        SET new_code = CONCAT('BK', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
        SELECT COUNT(*) INTO code_exists FROM bookings WHERE booking_code = new_code;
    END WHILE;
    
    RETURN new_code;
END$$

DELIMITER ;

SELECT '✅ Đã tạo bảng bookings và booking_guests thành công!' as message;

