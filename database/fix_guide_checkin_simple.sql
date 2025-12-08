-- ============================================
-- SỬA BẢNG GUIDE_CHECKIN (Phiên bản đơn giản)
-- ============================================
-- Script này sẽ chuyển đổi bảng guide_checkin từ phiên bản cũ sang phiên bản mới
-- Chạy từng câu lệnh một, bỏ qua lỗi nếu cột/constraint không tồn tại
-- ============================================

-- Bước 1: Xóa foreign key của booking_id (nếu có)
-- Tìm tên foreign key trước:
-- SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
-- WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guide_checkin' 
-- AND COLUMN_NAME = 'booking_id' AND CONSTRAINT_NAME != 'PRIMARY';
-- Sau đó chạy: ALTER TABLE guide_checkin DROP FOREIGN KEY <tên_fk>;

-- Bước 2: Xóa index booking_id (nếu có)
-- ALTER TABLE guide_checkin DROP INDEX booking_id;

-- Bước 3: Xóa các cột không cần thiết
-- ⚠️ CHẠY TỪNG CÂU MỘT, BỎ QUA LỖI NẾU CỘT KHÔNG TỒN TẠI

-- Xóa cột booking_id (bỏ qua lỗi nếu không tồn tại)
ALTER TABLE `guide_checkin` DROP COLUMN `booking_id`;

-- Xóa cột checkin_time (bỏ qua lỗi nếu không tồn tại)
ALTER TABLE `guide_checkin` DROP COLUMN `checkin_time`;

-- Xóa cột checkin_location (bỏ qua lỗi nếu không tồn tại)
ALTER TABLE `guide_checkin` DROP COLUMN `checkin_location`;

-- Xóa cột status (bỏ qua lỗi nếu không tồn tại)
ALTER TABLE `guide_checkin` DROP COLUMN `status`;

-- Xóa cột notes (bỏ qua lỗi nếu không tồn tại)
ALTER TABLE `guide_checkin` DROP COLUMN `notes`;

-- Bước 4: Thêm cột checked_in_at
-- ⚠️ BỎ QUA LỖI NẾU CỘT ĐÃ TỒN TẠI
ALTER TABLE `guide_checkin` 
ADD COLUMN `checked_in_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian check-in' AFTER `departure_id`;

-- Bước 5: Thêm unique constraint
-- ⚠️ BỎ QUA LỖI NẾU CONSTRAINT ĐÃ TỒN TẠI
ALTER TABLE `guide_checkin` 
ADD UNIQUE KEY `unique_guide_departure` (`guide_id`, `departure_id`);

SELECT '✅ Đã sửa bảng guide_checkin thành công!' as message;

