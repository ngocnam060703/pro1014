-- ============================================
-- BẢNG QUẢN LÝ CHI PHÍ VÀ BÁO CÁO DOANH THU
-- ============================================
-- File này tạo các bảng để quản lý chi phí và tính toán lợi nhuận
-- ============================================

USE travel_system;

-- ============================================
-- BẢNG CHI PHÍ THEO TOUR
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) DEFAULT NULL COMMENT 'ID tour (NULL nếu là chi phí chung)',
  `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành cụ thể',
  `expense_type` enum('transport','hotel','flight','restaurant','attraction','staff_salary','marketing','insurance','other') NOT NULL COMMENT 'Loại chi phí',
  `expense_name` varchar(255) NOT NULL COMMENT 'Tên chi phí',
  `amount` decimal(15,2) NOT NULL COMMENT 'Số tiền',
  `currency` varchar(10) DEFAULT 'VND' COMMENT 'Đơn vị tiền tệ',
  `expense_date` date DEFAULT NULL COMMENT 'Ngày phát sinh',
  `payment_date` date DEFAULT NULL COMMENT 'Ngày thanh toán',
  `payment_status` enum('pending','partial','paid') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
  `provider_name` varchar(255) DEFAULT NULL COMMENT 'Nhà cung cấp',
  `invoice_number` varchar(100) DEFAULT NULL COMMENT 'Số hóa đơn',
  `description` text DEFAULT NULL COMMENT 'Mô tả',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `departure_id` (`departure_id`),
  KEY `expense_type` (`expense_type`),
  KEY `expense_date` (`expense_date`),
  KEY `payment_status` (`payment_status`),
  CONSTRAINT `fk_tour_expenses_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tour_expenses_departure` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi phí theo tour';

-- ============================================
-- BẢNG TỔNG HỢP DOANH THU THEO TOUR (CACHE)
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_revenue_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL COMMENT 'ID tour',
  `period_type` enum('daily','monthly','quarterly','yearly') NOT NULL COMMENT 'Loại kỳ báo cáo',
  `period_value` varchar(20) NOT NULL COMMENT 'Giá trị kỳ (YYYY-MM-DD, YYYY-MM, YYYY-Q, YYYY)',
  `total_revenue` decimal(15,2) DEFAULT 0 COMMENT 'Tổng doanh thu',
  `total_expenses` decimal(15,2) DEFAULT 0 COMMENT 'Tổng chi phí',
  `total_profit` decimal(15,2) DEFAULT 0 COMMENT 'Tổng lợi nhuận',
  `booking_count` int(11) DEFAULT 0 COMMENT 'Số lượng booking',
  `guest_count` int(11) DEFAULT 0 COMMENT 'Số lượng khách',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tour_period` (`tour_id`, `period_type`, `period_value`),
  KEY `period_type` (`period_type`),
  KEY `period_value` (`period_value`),
  CONSTRAINT `fk_revenue_summary_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tổng hợp doanh thu theo tour (cache)';

