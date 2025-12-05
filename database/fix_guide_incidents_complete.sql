-- ============================================
-- SỬA HOÀN TOÀN BẢNG guide_incidents
-- ============================================
-- File này sẽ xử lý an toàn: xóa dữ liệu cũ và tạo lại bảng với enum đúng

USE travel_system;

-- Bước 1: Xóa dữ liệu cũ (nếu muốn giữ lại, bỏ comment dòng này)
-- DELETE FROM `guide_incidents`;

-- Bước 2: Xóa bảng cũ và tạo lại với enum đúng
DROP TABLE IF EXISTS `guide_incidents`;

CREATE TABLE `guide_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL,
  `departure_id` int(11) NOT NULL,
  `incident_type` varchar(100) DEFAULT NULL,
  `severity` enum('low','medium','high') DEFAULT 'low',
  `description` text DEFAULT NULL,
  `solution` text DEFAULT NULL,
  `photos` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_id` (`guide_id`),
  KEY `departure_id` (`departure_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT '✅ Đã tạo lại bảng guide_incidents thành công!' as message;

