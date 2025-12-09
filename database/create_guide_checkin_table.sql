-- ============================================
-- TẠO BẢNG GUIDE_CHECKIN
-- ============================================

CREATE TABLE IF NOT EXISTS `guide_checkin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL COMMENT 'ID hướng dẫn viên',
  `departure_id` int(11) NOT NULL COMMENT 'ID lịch khởi hành',
  `checked_in_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian check-in',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_guide_departure` (`guide_id`, `departure_id`),
  KEY `guide_id` (`guide_id`),
  KEY `departure_id` (`departure_id`),
  CONSTRAINT `fk_checkin_guide` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_checkin_departure` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu thông tin check-in của HDV';

SELECT '✅ Đã tạo bảng guide_checkin thành công!' as message;




