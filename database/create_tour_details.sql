-- ============================================
-- TẠO CÁC BẢNG QUẢN LÝ THÔNG TIN CHI TIẾT TOUR
-- ============================================
-- Bao gồm: Hình ảnh, Giá, Chính sách, Nhà cung cấp
-- ============================================

-- 1. Bảng lưu hình ảnh tour
CREATE TABLE IF NOT EXISTS `tour_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL COMMENT 'Đường dẫn ảnh',
  `image_type` enum('thumbnail','gallery','banner') DEFAULT 'gallery' COMMENT 'Loại ảnh: thumbnail, gallery, banner',
  `alt_text` varchar(255) DEFAULT NULL COMMENT 'Mô tả ảnh',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự sắp xếp',
  `is_primary` tinyint(1) DEFAULT 0 COMMENT 'Ảnh chính (1) hoặc không (0)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `image_type` (`image_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng lưu giá tour theo đối tượng và thời điểm
CREATE TABLE IF NOT EXISTS `tour_pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `price_type` enum('adult','child','infant','senior','group') DEFAULT 'adult' COMMENT 'Loại giá: người lớn, trẻ em, trẻ sơ sinh, người cao tuổi, nhóm',
  `price` decimal(15,2) NOT NULL COMMENT 'Giá',
  `currency` varchar(10) DEFAULT 'VND' COMMENT 'Đơn vị tiền tệ',
  `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu áp dụng',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc áp dụng',
  `min_quantity` int(11) DEFAULT 1 COMMENT 'Số lượng tối thiểu',
  `max_quantity` int(11) DEFAULT NULL COMMENT 'Số lượng tối đa',
  `description` text DEFAULT NULL COMMENT 'Mô tả gói giá',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `price_type` (`price_type`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng lưu chính sách tour
CREATE TABLE IF NOT EXISTS `tour_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `policy_type` enum('booking','cancellation','reschedule','refund','terms') NOT NULL COMMENT 'Loại chính sách: đặt tour, hủy tour, đổi lịch, hoàn tiền, điều khoản',
  `title` varchar(255) DEFAULT NULL COMMENT 'Tiêu đề chính sách',
  `content` text NOT NULL COMMENT 'Nội dung chính sách',
  `days_before` int(11) DEFAULT NULL COMMENT 'Số ngày trước khi áp dụng (ví dụ: hủy trước 7 ngày)',
  `penalty_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phần trăm phí phạt (ví dụ: 10.50)',
  `penalty_amount` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền phạt cố định',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `policy_type` (`policy_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bảng lưu nhà cung cấp dịch vụ cho tour
CREATE TABLE IF NOT EXISTS `tour_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `provider_type` enum('hotel','restaurant','transport','attraction','guide','other') NOT NULL COMMENT 'Loại nhà cung cấp: khách sạn, nhà hàng, vận chuyển, điểm tham quan, hướng dẫn viên, khác',
  `provider_name` varchar(255) NOT NULL COMMENT 'Tên nhà cung cấp',
  `contact_person` varchar(255) DEFAULT NULL COMMENT 'Người liên hệ',
  `phone` varchar(50) DEFAULT NULL COMMENT 'Số điện thoại',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email',
  `address` text DEFAULT NULL COMMENT 'Địa chỉ',
  `description` text DEFAULT NULL COMMENT 'Mô tả dịch vụ',
  `service_details` text DEFAULT NULL COMMENT 'Chi tiết dịch vụ (JSON)',
  `rating` decimal(3,2) DEFAULT NULL COMMENT 'Đánh giá (0-5)',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `provider_type` (`provider_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Cập nhật bảng tour_itinerary_detail để thêm thời gian chi tiết
-- Sử dụng procedure để kiểm tra và thêm cột an toàn

DELIMITER $$

DROP PROCEDURE IF EXISTS AddTourItineraryColumns$$

CREATE PROCEDURE AddTourItineraryColumns()
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    
    -- Kiểm tra và thêm cột start_time
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'start_time';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `start_time` time DEFAULT NULL COMMENT 'Giờ bắt đầu';
    END IF;
    
    -- Kiểm tra và thêm cột end_time
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'end_time';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc';
    END IF;
    
    -- Kiểm tra và thêm cột location
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'location';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `location` varchar(255) DEFAULT NULL COMMENT 'Địa điểm';
    END IF;
    
    -- Kiểm tra và thêm cột transportation
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'transportation';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `transportation` varchar(255) DEFAULT NULL COMMENT 'Phương tiện di chuyển';
    END IF;
    
    SELECT '✅ Đã cập nhật bảng tour_itinerary_detail thành công!' as message;
END$$

DELIMITER ;

-- Chạy procedure để thêm các cột
CALL AddTourItineraryColumns();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS AddTourItineraryColumns;

SELECT '✅ Đã tạo các bảng quản lý thông tin chi tiết tour thành công!' as message;

