-- ============================================
-- TẠO BẢNG LỊCH SỬ THAY ĐỔI TRẠNG THÁI BOOKING
-- ============================================

-- Tạo bảng lưu lịch sử thay đổi trạng thái booking
CREATE TABLE IF NOT EXISTS `booking_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL COMMENT 'ID booking',
  `old_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái cũ',
  `new_status` varchar(50) NOT NULL COMMENT 'Trạng thái mới',
  `old_payment_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái thanh toán cũ',
  `new_payment_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái thanh toán mới',
  `changed_by` int(11) DEFAULT NULL COMMENT 'ID người thay đổi',
  `change_reason` text DEFAULT NULL COMMENT 'Lý do thay đổi',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `new_status` (`new_status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cập nhật enum status trong bảng bookings nếu cần
-- (Chạy riêng nếu enum chưa có các giá trị mới)
-- ALTER TABLE `bookings` MODIFY COLUMN `status` enum('pending','deposit_paid','completed','cancelled') DEFAULT 'pending' COMMENT 'Trạng thái booking';

SELECT '✅ Đã tạo bảng booking_status_history thành công!' as message;

