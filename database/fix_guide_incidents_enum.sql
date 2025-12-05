-- ============================================
-- SỬA LỖI ENUM CHO BẢNG guide_incidents
-- ============================================
-- Chạy file này nếu gặp lỗi "Data truncated for column 'severity'"

USE travel_system;

-- Kiểm tra và sửa enum của cột severity
ALTER TABLE `guide_incidents` 
MODIFY COLUMN `severity` enum('low','medium','high') DEFAULT 'low';

-- Kiểm tra kết quả
SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'travel_system' 
  AND TABLE_NAME = 'guide_incidents' 
  AND COLUMN_NAME = 'severity';

SELECT '✅ Đã sửa enum thành công!' as message;

