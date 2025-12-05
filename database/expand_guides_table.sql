-- ============================================
-- MỞ RỘNG BẢNG GUIDES VỚI THÔNG TIN CHI TIẾT HDV
-- ============================================
-- File này sử dụng stored procedure để thêm các cột mới một cách an toàn
-- Chạy file này trong phpMyAdmin hoặc MySQL

USE travel_system;

DELIMITER $$

-- Stored procedure để thêm cột nếu chưa tồn tại
DROP PROCEDURE IF EXISTS AddColumnIfNotExists$$
CREATE PROCEDURE AddColumnIfNotExists(
    IN tableName VARCHAR(255),
    IN columnName VARCHAR(255),
    IN columnDefinition TEXT
)
BEGIN
    DECLARE columnExists INT DEFAULT 0;
    
    SELECT COUNT(*) INTO columnExists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = tableName
      AND COLUMN_NAME = columnName;
    
    IF columnExists = 0 THEN
        SET @sql = CONCAT('ALTER TABLE `', tableName, '` ADD COLUMN `', columnName, '` ', columnDefinition);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

-- Thêm các cột mới vào bảng guides
CALL AddColumnIfNotExists('guides', 'date_of_birth', 'date DEFAULT NULL COMMENT ''Ngày sinh''');
CALL AddColumnIfNotExists('guides', 'photo', 'varchar(255) DEFAULT NULL COMMENT ''Đường dẫn ảnh đại diện''');
CALL AddColumnIfNotExists('guides', 'address', 'text DEFAULT NULL COMMENT ''Địa chỉ''');
CALL AddColumnIfNotExists('guides', 'languages', 'varchar(255) DEFAULT NULL COMMENT ''Ngôn ngữ sử dụng (phân cách bằng dấu phẩy)''');
CALL AddColumnIfNotExists('guides', 'experience_years', 'int(11) DEFAULT 0 COMMENT ''Số năm kinh nghiệm''');
CALL AddColumnIfNotExists('guides', 'experience_description', 'text DEFAULT NULL COMMENT ''Mô tả kinh nghiệm''');
CALL AddColumnIfNotExists('guides', 'health_status', 'enum(''excellent'',''good'',''fair'',''poor'') DEFAULT ''good'' COMMENT ''Tình trạng sức khỏe''');
CALL AddColumnIfNotExists('guides', 'health_notes', 'text DEFAULT NULL COMMENT ''Ghi chú về sức khỏe''');
CALL AddColumnIfNotExists('guides', 'rating', 'decimal(3,2) DEFAULT 0.00 COMMENT ''Đánh giá năng lực (0-5)''');
CALL AddColumnIfNotExists('guides', 'rating_count', 'int(11) DEFAULT 0 COMMENT ''Số lượt đánh giá''');
CALL AddColumnIfNotExists('guides', 'specializations', 'text DEFAULT NULL COMMENT ''Chuyên môn đặc biệt''');
CALL AddColumnIfNotExists('guides', 'status', 'enum(''active'',''inactive'',''on_leave'') DEFAULT ''active'' COMMENT ''Trạng thái làm việc''');
CALL AddColumnIfNotExists('guides', 'notes', 'text DEFAULT NULL COMMENT ''Ghi chú khác''');
CALL AddColumnIfNotExists('guides', 'created_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT ''Ngày tạo''');
CALL AddColumnIfNotExists('guides', 'updated_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT ''Ngày cập nhật''');

-- Xóa stored procedure sau khi sử dụng
DROP PROCEDURE IF EXISTS AddColumnIfNotExists;

-- ============================================
-- TẠO BẢNG PHÂN LOẠI HDV
-- ============================================
CREATE TABLE IF NOT EXISTS `guide_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL COMMENT 'ID hướng dẫn viên',
  `category_type` enum('domestic','international','specialized_route','group_tour','customized') NOT NULL COMMENT 'Loại phân loại',
  `category_name` varchar(100) DEFAULT NULL COMMENT 'Tên phân loại',
  `description` text DEFAULT NULL COMMENT 'Mô tả',
  `is_primary` tinyint(1) DEFAULT 0 COMMENT 'Phân loại chính',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_id` (`guide_id`),
  KEY `category_type` (`category_type`),
  CONSTRAINT `fk_guide_categories_guide` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân loại hướng dẫn viên';

-- ============================================
-- TẠO BẢNG LỊCH SỬ DẪN TOUR
-- ============================================
CREATE TABLE IF NOT EXISTS `guide_tour_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL COMMENT 'ID hướng dẫn viên',
  `tour_id` int(11) DEFAULT NULL COMMENT 'ID tour',
  `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành',
  `tour_name` varchar(255) DEFAULT NULL COMMENT 'Tên tour',
  `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc',
  `num_guests` int(11) DEFAULT 0 COMMENT 'Số lượng khách',
  `tour_type` enum('domestic','international','customized') DEFAULT NULL COMMENT 'Loại tour',
  `rating` decimal(3,2) DEFAULT NULL COMMENT 'Đánh giá từ khách hàng',
  `feedback` text DEFAULT NULL COMMENT 'Phản hồi',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_id` (`guide_id`),
  KEY `tour_id` (`tour_id`),
  KEY `departure_id` (`departure_id`),
  KEY `departure_date` (`departure_date`),
  CONSTRAINT `fk_guide_tour_history_guide` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử dẫn tour của hướng dẫn viên';

-- ============================================
-- TẠO BẢNG CHỨNG CHỈ CHUYÊN MÔN
-- ============================================
CREATE TABLE IF NOT EXISTS `guide_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL COMMENT 'ID hướng dẫn viên',
  `certificate_name` varchar(255) NOT NULL COMMENT 'Tên chứng chỉ',
  `certificate_number` varchar(100) DEFAULT NULL COMMENT 'Số chứng chỉ',
  `issuing_organization` varchar(255) DEFAULT NULL COMMENT 'Tổ chức cấp',
  `issue_date` date DEFAULT NULL COMMENT 'Ngày cấp',
  `expiry_date` date DEFAULT NULL COMMENT 'Ngày hết hạn',
  `certificate_file` varchar(255) DEFAULT NULL COMMENT 'File chứng chỉ',
  `description` text DEFAULT NULL COMMENT 'Mô tả',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_id` (`guide_id`),
  CONSTRAINT `fk_guide_certificates_guide` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chứng chỉ chuyên môn của hướng dẫn viên';

