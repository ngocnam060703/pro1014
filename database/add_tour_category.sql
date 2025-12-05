-- ============================================
-- THÊM TRƯỜNG DANH MỤC TOUR (CATEGORY) VÀO BẢNG TOURS
-- ============================================
-- Mô tả: Thêm trường category để phân loại tour thành 3 loại:
--   - domestic: Tour trong nước
--   - international: Tour quốc tế  
--   - customized: Tour theo yêu cầu
-- ============================================

-- Kiểm tra và thêm cột category vào bảng tours
-- (Chỉ thêm nếu cột chưa tồn tại)

-- Cách 1: Sử dụng procedure để kiểm tra và thêm cột an toàn
DELIMITER $$

DROP PROCEDURE IF EXISTS AddTourCategoryColumn$$

CREATE PROCEDURE AddTourCategoryColumn()
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    
    -- Kiểm tra xem cột category đã tồn tại chưa
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tours'
    AND COLUMN_NAME = 'category';
    
    -- Nếu cột chưa tồn tại thì thêm vào
    IF column_exists = 0 THEN
        ALTER TABLE `tours` 
        ADD COLUMN `category` enum('domestic','international','customized') 
        DEFAULT 'domestic' 
        COMMENT 'Danh mục tour: trong nước, quốc tế, theo yêu cầu';
        
        SELECT '✅ Đã thêm trường category vào bảng tours thành công!' as message;
    ELSE
        SELECT 'ℹ Trường category đã tồn tại trong bảng tours.' as message;
    END IF;
END$$

DELIMITER ;

-- Chạy procedure để thêm cột
CALL AddTourCategoryColumn();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS AddTourCategoryColumn;

-- ============================================
-- CÁCH 2: Chạy trực tiếp (nếu chắc chắn cột chưa tồn tại)
-- ============================================
-- ALTER TABLE `tours` 
-- ADD COLUMN `category` enum('domestic','international','customized') 
-- DEFAULT 'domestic' 
-- COMMENT 'Danh mục tour: trong nước, quốc tế, theo yêu cầu';

-- ============================================
-- CẬP NHẬT DỮ LIỆU MẪU (Tùy chọn)
-- ============================================
-- Nếu bạn muốn cập nhật các tour hiện có thành một danh mục mặc định:
-- UPDATE `tours` SET `category` = 'domestic' WHERE `category` IS NULL;

-- ============================================
-- KIỂM TRA KẾT QUẢ
-- ============================================
-- Xem cấu trúc bảng tours sau khi thêm cột:
-- DESCRIBE `tours`;

-- Xem dữ liệu với danh mục:
-- SELECT id, title, category, departure, price FROM `tours` ORDER BY id DESC;

