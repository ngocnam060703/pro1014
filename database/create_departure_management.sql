-- ============================================
-- QUẢN LÝ LỊCH KHỞI HÀNH & PHÂN BỔ NHÂN SỰ, DỊCH VỤ
-- ============================================
-- File này mở rộng bảng departures và tạo các bảng phân bổ
-- ============================================

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

-- Mở rộng bảng departures với thông tin chi tiết
CALL AddColumnIfNotExists('departures', 'departure_date', 'date DEFAULT NULL COMMENT ''Ngày khởi hành''');
CALL AddColumnIfNotExists('departures', 'departure_time', 'time DEFAULT NULL COMMENT ''Giờ xuất phát''');
CALL AddColumnIfNotExists('departures', 'end_date', 'date DEFAULT NULL COMMENT ''Ngày kết thúc''');
CALL AddColumnIfNotExists('departures', 'end_time', 'time DEFAULT NULL COMMENT ''Giờ kết thúc''');
CALL AddColumnIfNotExists('departures', 'meeting_point', 'varchar(255) DEFAULT NULL COMMENT ''Điểm tập trung''');
CALL AddColumnIfNotExists('departures', 'meeting_address', 'text DEFAULT NULL COMMENT ''Địa chỉ chi tiết điểm tập trung''');
CALL AddColumnIfNotExists('departures', 'meeting_instructions', 'text DEFAULT NULL COMMENT ''Hướng dẫn đến điểm tập trung''');
CALL AddColumnIfNotExists('departures', 'status', 'enum(''scheduled'',''confirmed'',''in_progress'',''completed'',''cancelled'') DEFAULT ''scheduled'' COMMENT ''Trạng thái lịch khởi hành''');
CALL AddColumnIfNotExists('departures', 'total_seats', 'int(11) DEFAULT 0 COMMENT ''Tổng số chỗ''');
CALL AddColumnIfNotExists('departures', 'seats_available', 'int(11) DEFAULT 0 COMMENT ''Số chỗ còn trống''');
CALL AddColumnIfNotExists('departures', 'seats_booked', 'int(11) DEFAULT 0 COMMENT ''Số chỗ đã đặt''');
CALL AddColumnIfNotExists('departures', 'notes', 'text DEFAULT NULL COMMENT ''Ghi chú''');
CALL AddColumnIfNotExists('departures', 'created_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
CALL AddColumnIfNotExists('departures', 'updated_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

-- Xóa stored procedure
DROP PROCEDURE IF EXISTS AddColumnIfNotExists;

-- ============================================
-- BẢNG PHÂN BỔ NHÂN SỰ
-- ============================================
CREATE TABLE IF NOT EXISTS `departure_staff_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departure_id` int(11) NOT NULL COMMENT 'ID lịch khởi hành',
  `staff_type` enum('guide','driver','logistics','coordinator','other') NOT NULL COMMENT 'Loại nhân sự',
  `staff_id` int(11) DEFAULT NULL COMMENT 'ID nhân sự (HDV, tài xế, ...)',
  `staff_name` varchar(255) DEFAULT NULL COMMENT 'Tên nhân sự (nếu không có trong hệ thống)',
  `staff_phone` varchar(50) DEFAULT NULL COMMENT 'SĐT nhân sự',
  `role` varchar(100) DEFAULT NULL COMMENT 'Vai trò cụ thể',
  `responsibilities` text DEFAULT NULL COMMENT 'Trách nhiệm',
  `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc',
  `status` enum('assigned','confirmed','completed','cancelled') DEFAULT 'assigned' COMMENT 'Trạng thái phân công',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `departure_id` (`departure_id`),
  KEY `staff_type` (`staff_type`),
  KEY `staff_id` (`staff_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_departure_staff_departure` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân bổ nhân sự cho lịch khởi hành';

-- ============================================
-- BẢNG PHÂN BỔ DỊCH VỤ
-- ============================================
CREATE TABLE IF NOT EXISTS `departure_service_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departure_id` int(11) NOT NULL COMMENT 'ID lịch khởi hành',
  `service_type` enum('transport','hotel','flight','restaurant','attraction','insurance','other') NOT NULL COMMENT 'Loại dịch vụ',
  `service_name` varchar(255) NOT NULL COMMENT 'Tên dịch vụ',
  `provider_name` varchar(255) DEFAULT NULL COMMENT 'Tên nhà cung cấp',
  `provider_contact` varchar(255) DEFAULT NULL COMMENT 'Liên hệ nhà cung cấp',
  `booking_reference` varchar(100) DEFAULT NULL COMMENT 'Mã đặt chỗ',
  `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc',
  `start_time` time DEFAULT NULL COMMENT 'Giờ bắt đầu',
  `end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc',
  `location` varchar(255) DEFAULT NULL COMMENT 'Địa điểm',
  `quantity` int(11) DEFAULT 1 COMMENT 'Số lượng',
  `unit` varchar(50) DEFAULT NULL COMMENT 'Đơn vị (phòng, xe, vé, ...)',
  `unit_price` decimal(15,2) DEFAULT 0 COMMENT 'Đơn giá',
  `total_price` decimal(15,2) DEFAULT 0 COMMENT 'Tổng giá',
  `currency` varchar(10) DEFAULT 'VND' COMMENT 'Đơn vị tiền tệ',
  `status` enum('pending','confirmed','in_use','completed','cancelled') DEFAULT 'pending' COMMENT 'Trạng thái',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `departure_id` (`departure_id`),
  KEY `service_type` (`service_type`),
  KEY `status` (`status`),
  KEY `start_date` (`start_date`),
  CONSTRAINT `fk_departure_service_departure` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân bổ dịch vụ cho lịch khởi hành';

-- ============================================
-- BẢNG CHI TIẾT DỊCH VỤ VẬN CHUYỂN
-- ============================================
CREATE TABLE IF NOT EXISTS `departure_transport_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_allocation_id` int(11) NOT NULL COMMENT 'ID phân bổ dịch vụ',
  `vehicle_type` enum('car','van','bus','coach','plane','train','boat','other') DEFAULT NULL COMMENT 'Loại phương tiện',
  `vehicle_number` varchar(50) DEFAULT NULL COMMENT 'Biển số xe',
  `driver_name` varchar(255) DEFAULT NULL COMMENT 'Tên tài xế',
  `driver_phone` varchar(50) DEFAULT NULL COMMENT 'SĐT tài xế',
  `license_number` varchar(50) DEFAULT NULL COMMENT 'Số bằng lái',
  `capacity` int(11) DEFAULT NULL COMMENT 'Sức chứa',
  `route` text DEFAULT NULL COMMENT 'Tuyến đường',
  `pickup_location` varchar(255) DEFAULT NULL COMMENT 'Điểm đón',
  `dropoff_location` varchar(255) DEFAULT NULL COMMENT 'Điểm trả',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  PRIMARY KEY (`id`),
  KEY `service_allocation_id` (`service_allocation_id`),
  CONSTRAINT `fk_transport_service` FOREIGN KEY (`service_allocation_id`) REFERENCES `departure_service_allocations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết dịch vụ vận chuyển';

-- ============================================
-- BẢNG CHI TIẾT DỊCH VỤ KHÁCH SẠN
-- ============================================
CREATE TABLE IF NOT EXISTS `departure_hotel_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_allocation_id` int(11) NOT NULL COMMENT 'ID phân bổ dịch vụ',
  `hotel_name` varchar(255) DEFAULT NULL COMMENT 'Tên khách sạn',
  `room_type` varchar(100) DEFAULT NULL COMMENT 'Loại phòng',
  `room_number` varchar(50) DEFAULT NULL COMMENT 'Số phòng',
  `check_in_date` date DEFAULT NULL COMMENT 'Ngày nhận phòng',
  `check_out_date` date DEFAULT NULL COMMENT 'Ngày trả phòng',
  `check_in_time` time DEFAULT NULL COMMENT 'Giờ nhận phòng',
  `check_out_time` time DEFAULT NULL COMMENT 'Giờ trả phòng',
  `number_of_rooms` int(11) DEFAULT 1 COMMENT 'Số phòng',
  `number_of_nights` int(11) DEFAULT 1 COMMENT 'Số đêm',
  `amenities` text DEFAULT NULL COMMENT 'Tiện ích',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  PRIMARY KEY (`id`),
  KEY `service_allocation_id` (`service_allocation_id`),
  CONSTRAINT `fk_hotel_service` FOREIGN KEY (`service_allocation_id`) REFERENCES `departure_service_allocations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết dịch vụ khách sạn';

-- ============================================
-- BẢNG CHI TIẾT DỊCH VỤ VÉ MÁY BAY
-- ============================================
CREATE TABLE IF NOT EXISTS `departure_flight_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_allocation_id` int(11) NOT NULL COMMENT 'ID phân bổ dịch vụ',
  `flight_number` varchar(50) DEFAULT NULL COMMENT 'Số hiệu chuyến bay',
  `airline` varchar(100) DEFAULT NULL COMMENT 'Hãng hàng không',
  `departure_airport` varchar(255) DEFAULT NULL COMMENT 'Sân bay đi',
  `arrival_airport` varchar(255) DEFAULT NULL COMMENT 'Sân bay đến',
  `departure_datetime` datetime DEFAULT NULL COMMENT 'Thời gian khởi hành',
  `arrival_datetime` datetime DEFAULT NULL COMMENT 'Thời gian đến',
  `class` enum('economy','business','first') DEFAULT 'economy' COMMENT 'Hạng ghế',
  `number_of_tickets` int(11) DEFAULT 1 COMMENT 'Số vé',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  PRIMARY KEY (`id`),
  KEY `service_allocation_id` (`service_allocation_id`),
  CONSTRAINT `fk_flight_service` FOREIGN KEY (`service_allocation_id`) REFERENCES `departure_service_allocations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết dịch vụ vé máy bay';

