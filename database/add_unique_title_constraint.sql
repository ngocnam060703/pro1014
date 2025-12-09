-- ============================================
-- THÊM UNIQUE CONSTRAINT CHO TÊN TOUR (TITLE)
-- ============================================
-- Mô tả: Thêm ràng buộc UNIQUE cho cột title trong bảng tours
-- để đảm bảo tên địa điểm không được trùng lặp
-- ============================================

USE travel_system;

DELIMITER $$

-- Kiểm tra và thêm unique constraint cho title
DROP PROCEDURE IF EXISTS AddUniqueTitleConstraint$$
CREATE PROCEDURE AddUniqueTitleConstraint()
BEGIN
    DECLARE constraintExists INT DEFAULT 0;
    
    -- Kiểm tra xem unique constraint đã tồn tại chưa
    SELECT COUNT(*) INTO constraintExists
    FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tours'
      AND CONSTRAINT_TYPE = 'UNIQUE'
      AND CONSTRAINT_NAME LIKE '%title%';
    
    -- Nếu chưa có, thêm unique constraint
    IF constraintExists = 0 THEN
        -- Kiểm tra xem có dữ liệu trùng lặp không
        SET @duplicateCount = (
            SELECT COUNT(*) 
            FROM (
                SELECT title, COUNT(*) as cnt
                FROM tours
                WHERE title IS NOT NULL AND title != ''
                GROUP BY title
                HAVING cnt > 1
            ) AS duplicates
        );
        
        IF @duplicateCount > 0 THEN
            SELECT CONCAT('⚠️ Cảnh báo: Có ', @duplicateCount, ' tên tour bị trùng lặp. Vui lòng xóa hoặc sửa các tour trùng trước khi thêm constraint.') as message;
            SELECT title, COUNT(*) as count
            FROM tours
            WHERE title IS NOT NULL AND title != ''
            GROUP BY title
            HAVING count > 1;
        ELSE
            -- Thêm unique constraint
            ALTER TABLE `tours` 
            ADD UNIQUE KEY `unique_tour_title` (`title`);
            
            SELECT '✅ Đã thêm unique constraint cho cột title thành công!' as message;
        END IF;
    ELSE
        SELECT 'ℹ Unique constraint cho title đã tồn tại.' as message;
    END IF;
END$$

DELIMITER ;

-- Chạy procedure để thêm constraint
CALL AddUniqueTitleConstraint();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS AddUniqueTitleConstraint;




