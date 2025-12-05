-- ============================================
-- SỬA LỖI ENUM CHO BẢNG guide_incidents (AN TOÀN)
-- ============================================
-- File này sẽ xử lý an toàn: cập nhật dữ liệu cũ trước khi sửa enum

USE travel_system;

-- Bước 1: Kiểm tra và cập nhật các giá trị severity không hợp lệ thành 'low'
UPDATE `guide_incidents` 
SET `severity` = 'low' 
WHERE `severity` NOT IN ('low', 'medium', 'high') 
   OR `severity` IS NULL;

-- Bước 2: Nếu bảng có dữ liệu với enum cũ, xóa dữ liệu cũ (tùy chọn)
-- Bỏ comment dòng dưới nếu muốn xóa dữ liệu cũ:
-- DELETE FROM `guide_incidents` WHERE severity NOT IN ('low', 'medium', 'high');

-- Bước 3: Sửa enum của cột severity
ALTER TABLE `guide_incidents` 
MODIFY COLUMN `severity` enum('low','medium','high') DEFAULT 'low';

-- Kiểm tra kết quả
SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'travel_system' 
  AND TABLE_NAME = 'guide_incidents' 
  AND COLUMN_NAME = 'severity';

SELECT '✅ Đã sửa enum thành công!' as message;

