-- ============================================
-- SỬA BẢNG GUIDE_CHECKIN (Hướng dẫn thủ công)
-- ============================================
-- Nếu MySQL không hỗ trợ IF EXISTS/IF NOT EXISTS, chạy từng câu lệnh sau:

-- 1. Kiểm tra cấu trúc bảng hiện tại:
-- SHOW COLUMNS FROM guide_checkin;

-- 2. Xóa foreign key của booking_id (nếu có):
-- Tìm tên foreign key:
SELECT CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'guide_checkin' 
AND COLUMN_NAME = 'booking_id' 
AND CONSTRAINT_NAME != 'PRIMARY';

-- Sau đó chạy (thay <FK_NAME> bằng tên foreign key tìm được):
-- ALTER TABLE guide_checkin DROP FOREIGN KEY <FK_NAME>;

-- 3. Xóa index booking_id (nếu có):
-- ALTER TABLE guide_checkin DROP INDEX booking_id;

-- 4. Xóa các cột không cần thiết (chạy từng câu, bỏ qua lỗi nếu cột không tồn tại):
ALTER TABLE `guide_checkin` DROP COLUMN `booking_id`;
ALTER TABLE `guide_checkin` DROP COLUMN `checkin_time`;
ALTER TABLE `guide_checkin` DROP COLUMN `checkin_location`;
ALTER TABLE `guide_checkin` DROP COLUMN `status`;
ALTER TABLE `guide_checkin` DROP COLUMN `notes`;

-- 5. Thêm cột checked_in_at (bỏ qua lỗi nếu đã tồn tại):
ALTER TABLE `guide_checkin` 
ADD COLUMN `checked_in_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian check-in' AFTER `departure_id`;

-- 6. Thêm unique constraint (bỏ qua lỗi nếu đã tồn tại):
ALTER TABLE `guide_checkin` 
ADD UNIQUE KEY `unique_guide_departure` (`guide_id`, `departure_id`);

-- 7. Kiểm tra lại cấu trúc:
-- SHOW COLUMNS FROM guide_checkin;

