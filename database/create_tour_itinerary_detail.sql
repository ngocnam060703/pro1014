-- ============================================
-- TẠO BẢNG tour_itinerary_detail
-- ============================================

USE travel_system;

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

SELECT '✅ Đã tạo bảng tour_itinerary_detail thành công!' as message;

