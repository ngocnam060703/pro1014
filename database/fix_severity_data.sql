-- ============================================
-- SỬA DỮ LIỆU SEVERITY TỪ TIẾNG VIỆT SANG TIẾNG ANH
-- ============================================
-- File này sẽ cập nhật các giá trị severity từ tiếng Việt sang tiếng Anh

USE travel_system;

-- Cập nhật các giá trị severity không hợp lệ
UPDATE `guide_incidents` 
SET `severity` = 'low' 
WHERE `severity` IN ('thấp', 'Thấp', 'thap', 'THẤP');

UPDATE `guide_incidents` 
SET `severity` = 'medium' 
WHERE `severity` IN ('trung bình', 'Trung bình', 'Trung Bình', 'TRUNG BÌNH', 'trungbinh');

UPDATE `guide_incidents` 
SET `severity` = 'high' 
WHERE `severity` IN ('cao', 'Cao', 'CAO');

-- Kiểm tra kết quả
SELECT severity, COUNT(*) as count 
FROM guide_incidents 
GROUP BY severity;

SELECT '✅ Đã cập nhật dữ liệu severity thành công!' as message;

