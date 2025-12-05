-- ============================================
-- SỬA FOREIGN KEY CHO BẢNG DEPARTURES
-- ============================================
-- File này cập nhật các foreign key để hỗ trợ CASCADE DELETE
-- ============================================

USE travel_system;

-- Xóa foreign key cũ của guide_assign nếu có
SET @dbname = DATABASE();
SET @tablename = 'guide_assign';
SET @constraintname = 'fk_guide_assign_departure';

SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (constraint_name = @constraintname)
  ) > 0,
  CONCAT('ALTER TABLE ', @tablename, ' DROP FOREIGN KEY ', @constraintname, ';'),
  'SELECT 1'
));
PREPARE alterIfExists FROM @preparedStatement;
EXECUTE alterIfExists;
DEALLOCATE PREPARE alterIfExists;

-- Thêm lại foreign key với ON DELETE CASCADE
ALTER TABLE `guide_assign` 
ADD CONSTRAINT `fk_guide_assign_departure` 
FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE CASCADE;

-- Kiểm tra và cập nhật các foreign key khác nếu cần
-- (Các bảng mới đã có ON DELETE CASCADE rồi)

