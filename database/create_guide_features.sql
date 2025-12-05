-- ============================================
-- TẠO CÁC BẢNG MỚI CHO TÍNH NĂNG HDV
-- ============================================

USE travel_system;

-- 1. Bảng check-in/điểm danh khách
CREATE TABLE IF NOT EXISTS `guide_checkin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL,
  `departure_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `checkin_time` datetime DEFAULT NULL,
  `checkin_location` varchar(255) DEFAULT NULL,
  `status` enum('checked_in','absent','late') DEFAULT 'checked_in',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_id` (`guide_id`),
  KEY `departure_id` (`departure_id`),
  KEY `booking_id` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng yêu cầu đặc biệt của khách
CREATE TABLE IF NOT EXISTS `customer_special_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `request_type` varchar(100) DEFAULT NULL COMMENT 'diet, medical, accessibility, other',
  `description` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng phản hồi đánh giá của HDV về tour
CREATE TABLE IF NOT EXISTS `guide_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL,
  `departure_id` int(11) NOT NULL,
  `feedback_type` varchar(50) DEFAULT NULL COMMENT 'hotel, restaurant, transport, service, other',
  `provider_name` varchar(255) DEFAULT NULL,
  `rating` int(1) DEFAULT NULL COMMENT '1-5',
  `comment` text DEFAULT NULL,
  `suggestions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_id` (`guide_id`),
  KEY `departure_id` (`departure_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Cập nhật bảng guide_journal để thêm các trường mới
ALTER TABLE `guide_journal` 
ADD COLUMN IF NOT EXISTS `day_number` int(11) DEFAULT NULL COMMENT 'Số ngày trong tour',
ADD COLUMN IF NOT EXISTS `activities` text DEFAULT NULL COMMENT 'Các hoạt động trong ngày',
ADD COLUMN IF NOT EXISTS `photos` text DEFAULT NULL COMMENT 'Danh sách ảnh (JSON hoặc comma separated)',
ADD COLUMN IF NOT EXISTS `customer_feedback` text DEFAULT NULL COMMENT 'Phản hồi của khách hàng',
ADD COLUMN IF NOT EXISTS `weather` varchar(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `mood` varchar(50) DEFAULT NULL COMMENT 'good, normal, bad';

-- 5. Bảng lịch trình chi tiết từng ngày của tour
CREATE TABLE IF NOT EXISTS `tour_itinerary_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `activities` text DEFAULT NULL,
  `meals` varchar(255) DEFAULT NULL COMMENT 'Bữa ăn: breakfast, lunch, dinner',
  `accommodation` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT '✅ Đã tạo các bảng mới thành công!' as message;

