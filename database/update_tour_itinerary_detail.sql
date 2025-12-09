-- ============================================
-- CẬP NHẬT BẢNG tour_itinerary_detail
-- Thêm các trường: schedule, destinations, attractions, travel_time, rest_time, meal_time
-- ============================================

DELIMITER $$

DROP PROCEDURE IF EXISTS UpdateTourItineraryDetail$$

CREATE PROCEDURE UpdateTourItineraryDetail()
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    
    -- Kiểm tra và thêm cột schedule (lịch trình)
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'schedule';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `schedule` text DEFAULT NULL COMMENT 'Lịch trình trong ngày';
    END IF;
    
    -- Kiểm tra và thêm cột destinations (đi đâu)
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'destinations';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `destinations` text DEFAULT NULL COMMENT 'Điểm đến/đi đâu';
    END IF;
    
    -- Kiểm tra và thêm cột attractions (điểm tham quan)
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'attractions';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `attractions` text DEFAULT NULL COMMENT 'Các điểm tham quan';
    END IF;
    
    -- Kiểm tra và thêm cột travel_time (thời gian di chuyển)
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'travel_time';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `travel_time` varchar(255) DEFAULT NULL COMMENT 'Thời gian di chuyển';
    END IF;
    
    -- Kiểm tra và thêm cột rest_time (thời gian nghỉ ngơi)
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'rest_time';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `rest_time` varchar(255) DEFAULT NULL COMMENT 'Thời gian nghỉ ngơi';
    END IF;
    
    -- Kiểm tra và thêm cột meal_time (thời gian ăn uống)
    SET column_exists = 0;
    SELECT COUNT(*) INTO column_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tour_itinerary_detail'
    AND COLUMN_NAME = 'meal_time';
    
    IF column_exists = 0 THEN
        ALTER TABLE `tour_itinerary_detail` 
        ADD COLUMN `meal_time` varchar(255) DEFAULT NULL COMMENT 'Thời gian ăn uống';
    END IF;
    
    SELECT '✅ Đã cập nhật bảng tour_itinerary_detail thành công!' as message;
END$$

DELIMITER ;

-- Chạy procedure để cập nhật
CALL UpdateTourItineraryDetail();

-- Xóa procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS UpdateTourItineraryDetail;




