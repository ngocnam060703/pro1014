<?php
/**
 * Script cài đặt bảng database cho hệ thống HDV
 * Truy cập: http://localhost/pro1014/install_tables.php
 */

require_once __DIR__ . '/commons/env.php';
require_once __DIR__ . '/commons/function.php';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt Database - HDV System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Cài đặt Database - Hệ thống HDV</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
                            try {
                                $conn = pdo_get_connection();
                                $messages = [];
                                
                                // Tạo bảng guide_journal
                                $sql_guide_journal = "CREATE TABLE IF NOT EXISTS `guide_journal` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `guide_id` int(11) NOT NULL,
                                    `departure_id` int(11) NOT NULL,
                                    `note` text DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `guide_id` (`guide_id`),
                                    KEY `departure_id` (`departure_id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_guide_journal);
                                $messages[] = ['success', '✓ Bảng guide_journal đã được tạo thành công!'];
                                
                                // Tạo bảng guide_incidents
                                $sql_guide_incidents = "CREATE TABLE IF NOT EXISTS `guide_incidents` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `guide_id` int(11) NOT NULL,
                                    `departure_id` int(11) NOT NULL,
                                    `incident_type` varchar(100) DEFAULT NULL,
                                    `severity` enum('low','medium','high') DEFAULT 'low',
                                    `description` text DEFAULT NULL,
                                    `solution` text DEFAULT NULL,
                                    `photos` varchar(255) DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `guide_id` (`guide_id`),
                                    KEY `departure_id` (`departure_id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_guide_incidents);
                                $messages[] = ['success', '✓ Bảng guide_incidents đã được tạo thành công!'];
                                
                                // Tạo bảng guide_assign
                                $sql_guide_assign = "CREATE TABLE IF NOT EXISTS `guide_assign` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `guide_id` int(11) NOT NULL,
                                    `departure_id` int(11) NOT NULL,
                                    `tour_id` int(11) DEFAULT NULL,
                                    `departure_date` date DEFAULT NULL,
                                    `meeting_point` varchar(255) DEFAULT NULL,
                                    `max_people` int(11) DEFAULT NULL,
                                    `note` text DEFAULT NULL,
                                    `status` enum('scheduled','in_progress','completed','pending') DEFAULT 'scheduled',
                                    `assigned_at` timestamp NULL DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `guide_id` (`guide_id`),
                                    KEY `departure_id` (`departure_id`),
                                    KEY `tour_id` (`tour_id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_guide_assign);
                                $messages[] = ['success', '✓ Bảng guide_assign đã được kiểm tra/tạo thành công!'];
                                
                                // Cập nhật foreign key cho guide_assign để hỗ trợ CASCADE DELETE
                                try {
                                    // Xóa foreign key cũ nếu có
                                    $checkFK = $conn->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                                                             WHERE TABLE_SCHEMA = DATABASE() 
                                                             AND TABLE_NAME = 'guide_assign' 
                                                             AND CONSTRAINT_NAME LIKE '%departure%'");
                                    if ($checkFK->rowCount() > 0) {
                                        $fkName = $checkFK->fetchColumn();
                                        $conn->exec("ALTER TABLE `guide_assign` DROP FOREIGN KEY `{$fkName}`");
                                    }
                                    
                                    // Thêm lại với ON DELETE CASCADE
                                    $conn->exec("ALTER TABLE `guide_assign` 
                                                ADD CONSTRAINT `fk_guide_assign_departure` 
                                                FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`) ON DELETE CASCADE");
                                    $messages[] = ['success', '✓ Đã cập nhật foreign key cho guide_assign với ON DELETE CASCADE!'];
                                } catch (PDOException $e) {
                                    // Bỏ qua nếu đã tồn tại hoặc có lỗi
                                    $messages[] = ['info', 'ℹ Foreign key cho guide_assign: ' . htmlspecialchars($e->getMessage())];
                                }
                                
                                // Tạo bảng tour_itinerary_detail
                                $sql_tour_itinerary = "CREATE TABLE IF NOT EXISTS `tour_itinerary_detail` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `tour_id` int(11) NOT NULL,
                                    `day_number` int(11) NOT NULL,
                                    `title` varchar(255) DEFAULT NULL,
                                    `description` text DEFAULT NULL,
                                    `activities` text DEFAULT NULL,
                                    `meals` varchar(255) DEFAULT NULL,
                                    `accommodation` varchar(255) DEFAULT NULL,
                                    `notes` text DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `tour_id` (`tour_id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_tour_itinerary);
                                $messages[] = ['success', '✓ Bảng tour_itinerary_detail đã được tạo thành công!'];
                                
                                // Tạo các bảng khác từ create_guide_features.sql
                                $sql_checkin = "CREATE TABLE IF NOT EXISTS `guide_checkin` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `guide_id` int(11) NOT NULL,
                                    `departure_id` int(11) NOT NULL,
                                    `booking_id` int(11) NOT NULL,
                                    `checkin_time` datetime DEFAULT NULL,
                                    `checkin_location` varchar(255) DEFAULT NULL,
                                    `status` enum('checked_in','absent','late') DEFAULT 'checked_in',
                                    `notes` text DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `guide_id` (`guide_id`),
                                    KEY `departure_id` (`departure_id`),
                                    KEY `booking_id` (`booking_id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_checkin);
                                $messages[] = ['success', '✓ Bảng guide_checkin đã được tạo thành công!'];
                                
                                $sql_special_requests = "CREATE TABLE IF NOT EXISTS `customer_special_requests` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `booking_id` int(11) NOT NULL,
                                    `request_type` varchar(100) DEFAULT NULL,
                                    `description` text DEFAULT NULL,
                                    `status` enum('pending','confirmed','completed') DEFAULT 'pending',
                                    `notes` text DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `booking_id` (`booking_id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_special_requests);
                                $messages[] = ['success', '✓ Bảng customer_special_requests đã được tạo thành công!'];
                                
                                $sql_feedback = "CREATE TABLE IF NOT EXISTS `guide_feedback` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `guide_id` int(11) NOT NULL,
                                    `departure_id` int(11) NOT NULL,
                                    `feedback_type` varchar(50) DEFAULT NULL,
                                    `provider_name` varchar(255) DEFAULT NULL,
                                    `rating` int(1) DEFAULT NULL,
                                    `comment` text DEFAULT NULL,
                                    `suggestions` text DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `guide_id` (`guide_id`),
                                    KEY `departure_id` (`departure_id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_feedback);
                                $messages[] = ['success', '✓ Bảng guide_feedback đã được tạo thành công!'];
                                
                                // Tạo bảng tour_images - Lưu hình ảnh tour
                                $sql_tour_images = "CREATE TABLE IF NOT EXISTS `tour_images` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `tour_id` int(11) NOT NULL,
                                    `image_path` varchar(255) NOT NULL COMMENT 'Đường dẫn ảnh',
                                    `image_type` enum('thumbnail','gallery','banner') DEFAULT 'gallery' COMMENT 'Loại ảnh',
                                    `alt_text` varchar(255) DEFAULT NULL COMMENT 'Mô tả ảnh',
                                    `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự sắp xếp',
                                    `is_primary` tinyint(1) DEFAULT 0 COMMENT 'Ảnh chính',
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `tour_id` (`tour_id`),
                                    KEY `image_type` (`image_type`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_tour_images);
                                $messages[] = ['success', '✓ Bảng tour_images đã được tạo thành công!'];
                                
                                // Tạo bảng tour_pricing - Lưu giá tour theo đối tượng và thời điểm
                                $sql_tour_pricing = "CREATE TABLE IF NOT EXISTS `tour_pricing` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `tour_id` int(11) NOT NULL,
                                    `price_type` enum('adult','child','infant','senior','group') DEFAULT 'adult' COMMENT 'Loại giá',
                                    `price` decimal(15,2) NOT NULL COMMENT 'Giá',
                                    `currency` varchar(10) DEFAULT 'VND' COMMENT 'Đơn vị tiền tệ',
                                    `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu áp dụng',
                                    `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc áp dụng',
                                    `min_quantity` int(11) DEFAULT 1 COMMENT 'Số lượng tối thiểu',
                                    `max_quantity` int(11) DEFAULT NULL COMMENT 'Số lượng tối đa',
                                    `description` text DEFAULT NULL COMMENT 'Mô tả gói giá',
                                    `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái hoạt động',
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `tour_id` (`tour_id`),
                                    KEY `price_type` (`price_type`),
                                    KEY `start_date` (`start_date`),
                                    KEY `end_date` (`end_date`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_tour_pricing);
                                $messages[] = ['success', '✓ Bảng tour_pricing đã được tạo thành công!'];
                                
                                // Tạo bảng tour_policies - Lưu chính sách tour
                                $sql_tour_policies = "CREATE TABLE IF NOT EXISTS `tour_policies` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `tour_id` int(11) NOT NULL,
                                    `policy_type` enum('booking','cancellation','reschedule','refund','terms') NOT NULL COMMENT 'Loại chính sách',
                                    `title` varchar(255) DEFAULT NULL COMMENT 'Tiêu đề chính sách',
                                    `content` text NOT NULL COMMENT 'Nội dung chính sách',
                                    `days_before` int(11) DEFAULT NULL COMMENT 'Số ngày trước khi áp dụng',
                                    `penalty_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phần trăm phí phạt',
                                    `penalty_amount` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền phạt cố định',
                                    `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự hiển thị',
                                    `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái hoạt động',
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `tour_id` (`tour_id`),
                                    KEY `policy_type` (`policy_type`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_tour_policies);
                                $messages[] = ['success', '✓ Bảng tour_policies đã được tạo thành công!'];
                                
                                // Tạo bảng tour_providers - Lưu nhà cung cấp dịch vụ
                                $sql_tour_providers = "CREATE TABLE IF NOT EXISTS `tour_providers` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `tour_id` int(11) NOT NULL,
                                    `provider_type` enum('hotel','restaurant','transport','attraction','guide','other') NOT NULL COMMENT 'Loại nhà cung cấp',
                                    `provider_name` varchar(255) NOT NULL COMMENT 'Tên nhà cung cấp',
                                    `contact_person` varchar(255) DEFAULT NULL COMMENT 'Người liên hệ',
                                    `phone` varchar(50) DEFAULT NULL COMMENT 'Số điện thoại',
                                    `email` varchar(255) DEFAULT NULL COMMENT 'Email',
                                    `address` text DEFAULT NULL COMMENT 'Địa chỉ',
                                    `description` text DEFAULT NULL COMMENT 'Mô tả dịch vụ',
                                    `service_details` text DEFAULT NULL COMMENT 'Chi tiết dịch vụ (JSON)',
                                    `rating` decimal(3,2) DEFAULT NULL COMMENT 'Đánh giá (0-5)',
                                    `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái hoạt động',
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `tour_id` (`tour_id`),
                                    KEY `provider_type` (`provider_type`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_tour_providers);
                                $messages[] = ['success', '✓ Bảng tour_providers đã được tạo thành công!'];
                                
                                // Tạo bảng bookings - Quản lý booking
                                $sql_bookings = "CREATE TABLE IF NOT EXISTS `bookings` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `booking_code` varchar(50) DEFAULT NULL COMMENT 'Mã booking tự động',
                                    `tour_id` int(11) NOT NULL COMMENT 'ID tour',
                                    `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành',
                                    `booking_type` enum('individual','group') DEFAULT 'individual' COMMENT 'Loại booking',
                                    `customer_name` varchar(255) NOT NULL COMMENT 'Tên khách hàng',
                                    `customer_email` varchar(255) NOT NULL COMMENT 'Email',
                                    `customer_phone` varchar(50) NOT NULL COMMENT 'Số điện thoại',
                                    `customer_address` text DEFAULT NULL COMMENT 'Địa chỉ',
                                    `company_name` varchar(255) DEFAULT NULL COMMENT 'Tên công ty',
                                    `tax_code` varchar(50) DEFAULT NULL COMMENT 'Mã số thuế',
                                    `num_adults` int(11) DEFAULT 0 COMMENT 'Số người lớn',
                                    `num_children` int(11) DEFAULT 0 COMMENT 'Số trẻ em',
                                    `num_infants` int(11) DEFAULT 0 COMMENT 'Số trẻ sơ sinh',
                                    `num_people` int(11) NOT NULL COMMENT 'Tổng số người',
                                    `booking_date` date NOT NULL COMMENT 'Ngày đặt',
                                    `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành',
                                    `special_requests` text DEFAULT NULL COMMENT 'Yêu cầu đặc biệt',
                                    `notes` text DEFAULT NULL COMMENT 'Ghi chú',
                                    `base_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá cơ bản',
                                    `adult_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá người lớn',
                                    `child_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ em',
                                    `infant_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ sơ sinh',
                                    `discount_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền giảm giá',
                                    `discount_percentage` decimal(5,2) DEFAULT 0 COMMENT 'Phần trăm giảm giá',
                                    `total_price` decimal(15,2) NOT NULL COMMENT 'Tổng tiền',
                                    `deposit_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền đặt cọc',
                                    `remaining_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền còn lại',
                                    `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
                                    `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending' COMMENT 'Trạng thái booking',
                                    `confirmed_at` datetime DEFAULT NULL COMMENT 'Thời gian xác nhận',
                                    `cancelled_at` datetime DEFAULT NULL COMMENT 'Thời gian hủy',
                                    `cancellation_reason` text DEFAULT NULL COMMENT 'Lý do hủy',
                                    `created_by` int(11) DEFAULT NULL COMMENT 'ID nhân viên tạo',
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    UNIQUE KEY `booking_code` (`booking_code`),
                                    KEY `tour_id` (`tour_id`),
                                    KEY `departure_id` (`departure_id`),
                                    KEY `booking_type` (`booking_type`),
                                    KEY `status` (`status`),
                                    KEY `payment_status` (`payment_status`),
                                    KEY `booking_date` (`booking_date`),
                                    KEY `departure_date` (`departure_date`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_bookings);
                                $messages[] = ['success', '✓ Bảng bookings đã được tạo thành công!'];
                                
                                // Tạo bảng booking_guests - Lưu thông tin chi tiết khách (cho đoàn)
                                $sql_booking_guests = "CREATE TABLE IF NOT EXISTS `booking_guests` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `booking_id` int(11) NOT NULL,
                                    `guest_type` enum('adult','child','infant') DEFAULT 'adult' COMMENT 'Loại khách',
                                    `full_name` varchar(255) NOT NULL COMMENT 'Họ tên đầy đủ',
                                    `date_of_birth` date DEFAULT NULL COMMENT 'Ngày sinh',
                                    `gender` enum('male','female','other') DEFAULT NULL COMMENT 'Giới tính',
                                    `id_card` varchar(50) DEFAULT NULL COMMENT 'CMND/CCCD/Passport',
                                    `phone` varchar(50) DEFAULT NULL COMMENT 'Số điện thoại',
                                    `email` varchar(255) DEFAULT NULL COMMENT 'Email',
                                    `special_notes` text DEFAULT NULL COMMENT 'Ghi chú đặc biệt',
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `booking_id` (`booking_id`),
                                    KEY `guest_type` (`guest_type`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_booking_guests);
                                $messages[] = ['success', '✓ Bảng booking_guests đã được tạo thành công!'];
                                
                                // Tạo bảng booking_status_history - Lưu lịch sử thay đổi trạng thái
                                $sql_status_history = "CREATE TABLE IF NOT EXISTS `booking_status_history` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `booking_id` int(11) NOT NULL COMMENT 'ID booking',
                                    `old_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái cũ',
                                    `new_status` varchar(50) NOT NULL COMMENT 'Trạng thái mới',
                                    `old_payment_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái thanh toán cũ',
                                    `new_payment_status` varchar(50) DEFAULT NULL COMMENT 'Trạng thái thanh toán mới',
                                    `changed_by` int(11) DEFAULT NULL COMMENT 'ID người thay đổi',
                                    `change_reason` text DEFAULT NULL COMMENT 'Lý do thay đổi',
                                    `notes` text DEFAULT NULL COMMENT 'Ghi chú',
                                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    KEY `booking_id` (`booking_id`),
                                    KEY `new_status` (`new_status`),
                                    KEY `created_at` (`created_at`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                                
                                $conn->exec($sql_status_history);
                                $messages[] = ['success', '✓ Bảng booking_status_history đã được tạo thành công!'];
                                
                                // Mở rộng bảng guides với thông tin chi tiết HDV
                                try {
                                    $expandGuidesFile = __DIR__ . '/database/expand_guides_table.sql';
                                    if (file_exists($expandGuidesFile)) {
                                        $expandGuidesSQL = file_get_contents($expandGuidesFile);
                                        // Loại bỏ phần USE và DELIMITER để chạy trực tiếp
                                        $expandGuidesSQL = preg_replace('/USE\s+\w+;/i', '', $expandGuidesSQL);
                                        $expandGuidesSQL = preg_replace('/DELIMITER\s+\$\$.*?DELIMITER\s*;/is', '', $expandGuidesSQL);
                                        
                                        // Tách các câu lệnh CALL
                                        preg_match_all('/CALL\s+AddColumnIfNotExists\([^)]+\);/i', $expandGuidesSQL, $matches);
                                        
                                        // Tạo stored procedure trước
                                        $procSQL = "DROP PROCEDURE IF EXISTS AddColumnIfNotExists;
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
                                        END";
                                        
                                        // Chạy stored procedure (cần tách từng câu lệnh)
                                        $conn->exec("DROP PROCEDURE IF EXISTS AddColumnIfNotExists");
                                        
                                        // Tạo procedure
                                        $conn->exec($procSQL);
                                        
                                        // Thêm các cột mới
                                        $columns = [
                                            ['date_of_birth', 'date DEFAULT NULL COMMENT \'Ngày sinh\''],
                                            ['photo', 'varchar(255) DEFAULT NULL COMMENT \'Đường dẫn ảnh đại diện\''],
                                            ['address', 'text DEFAULT NULL COMMENT \'Địa chỉ\''],
                                            ['languages', 'varchar(255) DEFAULT NULL COMMENT \'Ngôn ngữ sử dụng (phân cách bằng dấu phẩy)\''],
                                            ['experience_years', 'int(11) DEFAULT 0 COMMENT \'Số năm kinh nghiệm\''],
                                            ['experience_description', 'text DEFAULT NULL COMMENT \'Mô tả kinh nghiệm\''],
                                            ['health_status', 'enum(\'excellent\',\'good\',\'fair\',\'poor\') DEFAULT \'good\' COMMENT \'Tình trạng sức khỏe\''],
                                            ['health_notes', 'text DEFAULT NULL COMMENT \'Ghi chú về sức khỏe\''],
                                            ['rating', 'decimal(3,2) DEFAULT 0.00 COMMENT \'Đánh giá năng lực (0-5)\''],
                                            ['rating_count', 'int(11) DEFAULT 0 COMMENT \'Số lượt đánh giá\''],
                                            ['specializations', 'text DEFAULT NULL COMMENT \'Chuyên môn đặc biệt\''],
                                            ['status', 'enum(\'active\',\'inactive\',\'on_leave\') DEFAULT \'active\' COMMENT \'Trạng thái làm việc\''],
                                            ['notes', 'text DEFAULT NULL COMMENT \'Ghi chú khác\''],
                                            ['created_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'Ngày tạo\''],
                                            ['updated_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'Ngày cập nhật\'']
                                        ];
                                        
                                        foreach ($columns as $col) {
                                            try {
                                                $stmt = $conn->prepare("CALL AddColumnIfNotExists('guides', ?, ?)");
                                                $stmt->execute([$col[0], $col[1]]);
                                            } catch (PDOException $e) {
                                                // Bỏ qua nếu cột đã tồn tại
                                            }
                                        }
                                        
                                        // Xóa procedure
                                        $conn->exec("DROP PROCEDURE IF EXISTS AddColumnIfNotExists");
                                        
                                        $messages[] = ['success', '✓ Đã mở rộng bảng guides với các trường mới!'];
                                    }
                                    
                                    // Tạo bảng guide_categories
                                    $sql_guide_categories = "CREATE TABLE IF NOT EXISTS `guide_categories` (
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
                                      KEY `category_type` (`category_type`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân loại hướng dẫn viên'";
                                    
                                    $conn->exec($sql_guide_categories);
                                    $messages[] = ['success', '✓ Bảng guide_categories đã được tạo thành công!'];
                                    
                                    // Tạo bảng guide_tour_history
                                    $sql_guide_tour_history = "CREATE TABLE IF NOT EXISTS `guide_tour_history` (
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
                                      KEY `departure_date` (`departure_date`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử dẫn tour của hướng dẫn viên'";
                                    
                                    $conn->exec($sql_guide_tour_history);
                                    $messages[] = ['success', '✓ Bảng guide_tour_history đã được tạo thành công!'];
                                    
                                    // Tạo bảng guide_certificates
                                    $sql_guide_certificates = "CREATE TABLE IF NOT EXISTS `guide_certificates` (
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
                                      KEY `guide_id` (`guide_id`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chứng chỉ chuyên môn của hướng dẫn viên'";
                                    
                                    $conn->exec($sql_guide_certificates);
                                    $messages[] = ['success', '✓ Bảng guide_certificates đã được tạo thành công!'];
                                    
                                } catch (PDOException $e) {
                                    $messages[] = ['warning', '⚠ Lỗi khi mở rộng bảng guides: ' . htmlspecialchars($e->getMessage())];
                                }
                                
                                // Tạo bảng quản lý lịch khởi hành và phân bổ
                                try {
                                    // Mở rộng bảng departures
                                    $departureColumns = [
                                        ['departure_date', 'date DEFAULT NULL COMMENT \'Ngày khởi hành\''],
                                        ['departure_time', 'time DEFAULT NULL COMMENT \'Giờ xuất phát\''],
                                        ['end_date', 'date DEFAULT NULL COMMENT \'Ngày kết thúc\''],
                                        ['end_time', 'time DEFAULT NULL COMMENT \'Giờ kết thúc\''],
                                        ['meeting_address', 'text DEFAULT NULL COMMENT \'Địa chỉ chi tiết điểm tập trung\''],
                                        ['meeting_instructions', 'text DEFAULT NULL COMMENT \'Hướng dẫn đến điểm tập trung\''],
                                        ['status', 'enum(\'scheduled\',\'confirmed\',\'in_progress\',\'completed\',\'cancelled\') DEFAULT \'scheduled\' COMMENT \'Trạng thái lịch khởi hành\''],
                                        ['total_seats', 'int(11) DEFAULT 0 COMMENT \'Tổng số chỗ\''],
                                        ['seats_booked', 'int(11) DEFAULT 0 COMMENT \'Số chỗ đã đặt\''],
                                        ['created_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'],
                                        ['updated_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
                                    ];
                                    
                                    foreach ($departureColumns as $col) {
                                        try {
                                            $checkColumn = $conn->query("SHOW COLUMNS FROM `departures` LIKE '{$col[0]}'");
                                            if ($checkColumn->rowCount() == 0) {
                                                $conn->exec("ALTER TABLE `departures` ADD COLUMN `{$col[0]}` {$col[1]}");
                                            }
                                        } catch (PDOException $e) {
                                            // Bỏ qua nếu cột đã tồn tại
                                        }
                                    }
                                    $messages[] = ['success', '✓ Đã mở rộng bảng departures!'];
                                    
                                    // Tạo bảng phân bổ nhân sự
                                    $sql_staff_assignments = "CREATE TABLE IF NOT EXISTS `departure_staff_assignments` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `departure_id` int(11) NOT NULL COMMENT 'ID lịch khởi hành',
                                      `staff_type` enum('guide','driver','logistics','coordinator','other') NOT NULL COMMENT 'Loại nhân sự',
                                      `staff_id` int(11) DEFAULT NULL COMMENT 'ID nhân sự',
                                      `staff_name` varchar(255) DEFAULT NULL COMMENT 'Tên nhân sự',
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
                                      KEY `staff_id` (`staff_id`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân bổ nhân sự cho lịch khởi hành'";
                                    
                                    $conn->exec($sql_staff_assignments);
                                    $messages[] = ['success', '✓ Bảng departure_staff_assignments đã được tạo thành công!'];
                                    
                                    // Tạo bảng phân bổ dịch vụ
                                    $sql_service_allocations = "CREATE TABLE IF NOT EXISTS `departure_service_allocations` (
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
                                      `unit` varchar(50) DEFAULT NULL COMMENT 'Đơn vị',
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
                                      KEY `status` (`status`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân bổ dịch vụ cho lịch khởi hành'";
                                    
                                    $conn->exec($sql_service_allocations);
                                    $messages[] = ['success', '✓ Bảng departure_service_allocations đã được tạo thành công!'];
                                    
                                    // Tạo bảng chi tiết vận chuyển
                                    $sql_transport_details = "CREATE TABLE IF NOT EXISTS `departure_transport_details` (
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
                                      KEY `service_allocation_id` (`service_allocation_id`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết dịch vụ vận chuyển'";
                                    
                                    $conn->exec($sql_transport_details);
                                    $messages[] = ['success', '✓ Bảng departure_transport_details đã được tạo thành công!'];
                                    
                                    // Tạo bảng chi tiết khách sạn
                                    $sql_hotel_details = "CREATE TABLE IF NOT EXISTS `departure_hotel_details` (
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
                                      KEY `service_allocation_id` (`service_allocation_id`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết dịch vụ khách sạn'";
                                    
                                    $conn->exec($sql_hotel_details);
                                    $messages[] = ['success', '✓ Bảng departure_hotel_details đã được tạo thành công!'];
                                    
                                    // Tạo bảng chi tiết vé máy bay
                                    $sql_flight_details = "CREATE TABLE IF NOT EXISTS `departure_flight_details` (
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
                                      KEY `service_allocation_id` (`service_allocation_id`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết dịch vụ vé máy bay'";
                                    
                                    $conn->exec($sql_flight_details);
                                    $messages[] = ['success', '✓ Bảng departure_flight_details đã được tạo thành công!'];
                                    
                                } catch (PDOException $e) {
                                    $messages[] = ['warning', '⚠ Lỗi khi tạo bảng quản lý lịch khởi hành: ' . htmlspecialchars($e->getMessage())];
                                }
                                
                                // Cập nhật bảng bookings cũ để thêm các cột còn thiếu
                                try {
                                    $checkTable = $conn->query("SHOW TABLES LIKE 'bookings'");
                                    if ($checkTable->rowCount() > 0) {
                                        // Kiểm tra và thêm departure_id
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'departure_id'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` ADD COLUMN `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành'");
                                            try {
                                                $conn->exec("ALTER TABLE `bookings` ADD KEY `departure_id` (`departure_id`)");
                                            } catch (PDOException $e) {
                                                // Bỏ qua nếu key đã tồn tại
                                            }
                                            $messages[] = ['success', '✓ Đã thêm cột departure_id vào bảng bookings!'];
                                        }
                                        
                                        // Kiểm tra và thêm departure_date
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'departure_date'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` ADD COLUMN `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành'");
                                            try {
                                                $conn->exec("ALTER TABLE `bookings` ADD KEY `departure_date` (`departure_date`)");
                                            } catch (PDOException $e) {
                                                // Bỏ qua nếu key đã tồn tại
                                            }
                                            $messages[] = ['success', '✓ Đã thêm cột departure_date vào bảng bookings!'];
                                        }
                                        
                                        // Kiểm tra và thêm booking_code
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'booking_code'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` ADD COLUMN `booking_code` varchar(50) DEFAULT NULL COMMENT 'Mã booking tự động'");
                                            try {
                                                $conn->exec("ALTER TABLE `bookings` ADD UNIQUE KEY `booking_code` (`booking_code`)");
                                            } catch (PDOException $e) {
                                                // Bỏ qua nếu key đã tồn tại
                                            }
                                            $messages[] = ['success', '✓ Đã thêm cột booking_code vào bảng bookings!'];
                                        }
                                        
                                        // Kiểm tra và thêm booking_type
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'booking_type'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` ADD COLUMN `booking_type` enum('individual','group') DEFAULT 'individual' COMMENT 'Loại booking'");
                                            $messages[] = ['success', '✓ Đã thêm cột booking_type vào bảng bookings!'];
                                        }
                                        
                                        // Kiểm tra và thêm customer_address
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'customer_address'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` ADD COLUMN `customer_address` text DEFAULT NULL COMMENT 'Địa chỉ'");
                                            $messages[] = ['success', '✓ Đã thêm cột customer_address vào bảng bookings!'];
                                        }
                                        
                                        // Kiểm tra và thêm special_requests
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'special_requests'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` ADD COLUMN `special_requests` text DEFAULT NULL COMMENT 'Yêu cầu đặc biệt'");
                                            $messages[] = ['success', '✓ Đã thêm cột special_requests vào bảng bookings!'];
                                        }
                                        
                                        // Kiểm tra và thêm notes
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'notes'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` ADD COLUMN `notes` text DEFAULT NULL COMMENT 'Ghi chú'");
                                            $messages[] = ['success', '✓ Đã thêm cột notes vào bảng bookings!'];
                                        }
                                        
                                        // Kiểm tra và thêm các cột khác
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'company_name'");
                                        if ($checkColumn->rowCount() == 0) {
                                            $conn->exec("ALTER TABLE `bookings` 
                                                ADD COLUMN `company_name` varchar(255) DEFAULT NULL COMMENT 'Tên công ty',
                                                ADD COLUMN `tax_code` varchar(50) DEFAULT NULL COMMENT 'Mã số thuế',
                                                ADD COLUMN `num_adults` int(11) DEFAULT 0 COMMENT 'Số người lớn',
                                                ADD COLUMN `num_children` int(11) DEFAULT 0 COMMENT 'Số trẻ em',
                                                ADD COLUMN `num_infants` int(11) DEFAULT 0 COMMENT 'Số trẻ sơ sinh',
                                                ADD COLUMN `base_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá cơ bản',
                                                ADD COLUMN `adult_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá người lớn',
                                                ADD COLUMN `child_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ em',
                                                ADD COLUMN `infant_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ sơ sinh',
                                                ADD COLUMN `discount_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền giảm giá',
                                                ADD COLUMN `discount_percentage` decimal(5,2) DEFAULT 0 COMMENT 'Phần trăm giảm giá',
                                                ADD COLUMN `deposit_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền đặt cọc',
                                                ADD COLUMN `remaining_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền còn lại',
                                                ADD COLUMN `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
                                                ADD COLUMN `confirmed_at` datetime DEFAULT NULL COMMENT 'Thời gian xác nhận',
                                                ADD COLUMN `cancelled_at` datetime DEFAULT NULL COMMENT 'Thời gian hủy',
                                                ADD COLUMN `cancellation_reason` text DEFAULT NULL COMMENT 'Lý do hủy',
                                                ADD COLUMN `created_by` int(11) DEFAULT NULL COMMENT 'ID nhân viên tạo'");
                                            $messages[] = ['success', '✓ Đã thêm các cột mới vào bảng bookings!'];
                                        }
                                    }
                                } catch (PDOException $e) {
                                    $messages[] = ['warning', '⚠ Không thể cập nhật bảng bookings: ' . htmlspecialchars($e->getMessage())];
                                }
                                
                                // Cập nhật bảng tour_itinerary_detail để thêm thời gian chi tiết
                                try {
                                    $checkColumn = $conn->query("SHOW COLUMNS FROM `tour_itinerary_detail` LIKE 'start_time'");
                                    if ($checkColumn->rowCount() == 0) {
                                        $conn->exec("ALTER TABLE `tour_itinerary_detail` ADD COLUMN `start_time` time DEFAULT NULL COMMENT 'Giờ bắt đầu'");
                                        $conn->exec("ALTER TABLE `tour_itinerary_detail` ADD COLUMN `end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc'");
                                        $conn->exec("ALTER TABLE `tour_itinerary_detail` ADD COLUMN `location` varchar(255) DEFAULT NULL COMMENT 'Địa điểm'");
                                        $conn->exec("ALTER TABLE `tour_itinerary_detail` ADD COLUMN `transportation` varchar(255) DEFAULT NULL COMMENT 'Phương tiện di chuyển'");
                                        $messages[] = ['success', '✓ Đã cập nhật bảng tour_itinerary_detail với các trường mới!'];
                                    }
                                } catch (PDOException $e) {
                                    // Bỏ qua nếu đã tồn tại
                                }
                                
                                // Cập nhật bảng tours để thêm trường category
                                try {
                                    // Kiểm tra xem bảng tours có tồn tại không
                                    $checkTable = $conn->query("SHOW TABLES LIKE 'tours'");
                                    if ($checkTable->rowCount() > 0) {
                                        // Kiểm tra xem cột category đã tồn tại chưa
                                        $checkColumn = $conn->query("SHOW COLUMNS FROM `tours` LIKE 'category'");
                                        if ($checkColumn->rowCount() == 0) {
                                            // Thêm cột category nếu chưa có
                                            $conn->exec("ALTER TABLE `tours` ADD COLUMN `category` enum('domestic','international','customized') DEFAULT 'domestic' COMMENT 'Danh mục tour: trong nước, quốc tế, theo yêu cầu'");
                                            $messages[] = ['success', '✓ Đã thêm trường category vào bảng tours!'];
                                        } else {
                                            $messages[] = ['info', 'ℹ Trường category đã tồn tại trong bảng tours.'];
                                        }
                                    } else {
                                        $messages[] = ['info', 'ℹ Bảng tours chưa tồn tại. Vui lòng tạo bảng tours trước khi chạy script này.'];
                                    }
                                } catch (PDOException $e) {
                                    $messages[] = ['warning', '⚠ Không thể thêm trường category: ' . htmlspecialchars($e->getMessage())];
                                }
                                
                                // Cập nhật bảng guide_journal để thêm các trường mới
                                try {
                                    $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN IF NOT EXISTS `day_number` int(11) DEFAULT NULL");
                                    $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN IF NOT EXISTS `activities` text DEFAULT NULL");
                                    $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN IF NOT EXISTS `photos` text DEFAULT NULL");
                                    $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN IF NOT EXISTS `customer_feedback` text DEFAULT NULL");
                                    $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN IF NOT EXISTS `weather` varchar(100) DEFAULT NULL");
                                    $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN IF NOT EXISTS `mood` varchar(50) DEFAULT NULL");
                                    $messages[] = ['success', '✓ Đã cập nhật bảng guide_journal với các trường mới!'];
                                } catch (PDOException $e) {
                                    // Bỏ qua nếu cột đã tồn tại
                                }
                                
                                // Tạo bảng quản lý chi phí và báo cáo doanh thu
                                try {
                                    // Tạo bảng tour_expenses
                                    $sql_tour_expenses = "CREATE TABLE IF NOT EXISTS `tour_expenses` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `tour_id` int(11) DEFAULT NULL COMMENT 'ID tour',
                                      `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành cụ thể',
                                      `expense_type` enum('transport','hotel','flight','restaurant','attraction','staff_salary','marketing','insurance','other') NOT NULL COMMENT 'Loại chi phí',
                                      `expense_name` varchar(255) NOT NULL COMMENT 'Tên chi phí',
                                      `amount` decimal(15,2) NOT NULL COMMENT 'Số tiền',
                                      `currency` varchar(10) DEFAULT 'VND' COMMENT 'Đơn vị tiền tệ',
                                      `expense_date` date DEFAULT NULL COMMENT 'Ngày phát sinh',
                                      `payment_date` date DEFAULT NULL COMMENT 'Ngày thanh toán',
                                      `payment_status` enum('pending','partial','paid') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
                                      `provider_name` varchar(255) DEFAULT NULL COMMENT 'Nhà cung cấp',
                                      `invoice_number` varchar(100) DEFAULT NULL COMMENT 'Số hóa đơn',
                                      `description` text DEFAULT NULL COMMENT 'Mô tả',
                                      `notes` text DEFAULT NULL COMMENT 'Ghi chú',
                                      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      PRIMARY KEY (`id`),
                                      KEY `tour_id` (`tour_id`),
                                      KEY `departure_id` (`departure_id`),
                                      KEY `expense_type` (`expense_type`),
                                      KEY `expense_date` (`expense_date`),
                                      KEY `payment_status` (`payment_status`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi phí theo tour'";
                                    
                                    $conn->exec($sql_tour_expenses);
                                    $messages[] = ['success', '✓ Bảng tour_expenses đã được tạo thành công!'];
                                    
                                    // Tạo bảng tour_revenue_summary (cache)
                                    $sql_revenue_summary = "CREATE TABLE IF NOT EXISTS `tour_revenue_summary` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `tour_id` int(11) NOT NULL COMMENT 'ID tour',
                                      `period_type` enum('daily','monthly','quarterly','yearly') NOT NULL COMMENT 'Loại kỳ báo cáo',
                                      `period_value` varchar(20) NOT NULL COMMENT 'Giá trị kỳ',
                                      `total_revenue` decimal(15,2) DEFAULT 0 COMMENT 'Tổng doanh thu',
                                      `total_expenses` decimal(15,2) DEFAULT 0 COMMENT 'Tổng chi phí',
                                      `total_profit` decimal(15,2) DEFAULT 0 COMMENT 'Tổng lợi nhuận',
                                      `booking_count` int(11) DEFAULT 0 COMMENT 'Số lượng booking',
                                      `guest_count` int(11) DEFAULT 0 COMMENT 'Số lượng khách',
                                      `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      PRIMARY KEY (`id`),
                                      UNIQUE KEY `unique_tour_period` (`tour_id`, `period_type`, `period_value`),
                                      KEY `period_type` (`period_type`),
                                      KEY `period_value` (`period_value`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tổng hợp doanh thu theo tour (cache)'";
                                    
                                    $conn->exec($sql_revenue_summary);
                                    $messages[] = ['success', '✓ Bảng tour_revenue_summary đã được tạo thành công!'];
                                    
                                } catch (PDOException $e) {
                                    $messages[] = ['warning', '⚠ Lỗi khi tạo bảng báo cáo doanh thu: ' . htmlspecialchars($e->getMessage())];
                                }
                                
                                foreach ($messages as $msg) {
                                    echo '<div class="alert alert-' . $msg[0] . '">' . htmlspecialchars($msg[1]) . '</div>';
                                }
                                
                                echo '<div class="alert alert-success mt-3"><strong>✅ Hoàn thành!</strong> Tất cả các bảng đã sẵn sàng. Bạn có thể <a href="index.php?act=hdv_login">đăng nhập HDV</a> ngay bây giờ.</div>';
                                
                            } catch (PDOException $e) {
                                echo '<div class="alert alert-danger">❌ Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
                            }
                        } else {
                            ?>
                            <p class="lead">Script này sẽ tạo các bảng cần thiết cho hệ thống HDV:</p>
                            <ul>
                                <li><code>guide_journal</code> - Lưu nhật ký của hướng dẫn viên</li>
                                <li><code>guide_incidents</code> - Lưu báo cáo sự cố</li>
                                <li><code>guide_assign</code> - Lưu phân công tour cho HDV</li>
                                <li><code>tour_itinerary_detail</code> - Lịch trình chi tiết từng ngày</li>
                                <li><code>guide_checkin</code> - Check-in/điểm danh khách</li>
                                <li><code>customer_special_requests</code> - Yêu cầu đặc biệt của khách</li>
                                <li><code>guide_feedback</code> - Phản hồi đánh giá của HDV</li>
                                <li><code>guide_categories</code> - Phân loại HDV (nội địa/quốc tế, chuyên tuyến, chuyên khách đoàn)</li>
                                <li><code>guide_tour_history</code> - Lịch sử dẫn tour của HDV</li>
                                <li><code>guide_certificates</code> - Chứng chỉ chuyên môn của HDV</li>
                            </ul>
                            <p class="lead mt-3">Script sẽ mở rộng bảng <code>guides</code> với:</p>
                            <ul>
                                <li>Thông tin cá nhân: ngày sinh, ảnh, địa chỉ</li>
                                <li>Thông tin chuyên môn: ngôn ngữ, kinh nghiệm, chuyên môn đặc biệt</li>
                                <li>Tình trạng sức khỏe và ghi chú</li>
                                <li>Đánh giá năng lực (rating)</li>
                                <li>Trạng thái làm việc (active, inactive, on_leave)</li>
                            </ul>
                            <p class="lead mt-3">Ngoài ra, script sẽ:</p>
                            <ul>
                                <li>Cập nhật bảng <code>tours</code> để thêm trường danh mục (category)</li>
                                <li>Tạo bảng <code>tour_images</code> - Quản lý hình ảnh tour</li>
                                <li>Tạo bảng <code>tour_pricing</code> - Quản lý giá tour theo đối tượng và thời điểm</li>
                                <li>Tạo bảng <code>tour_policies</code> - Quản lý chính sách đặt/hủy/đổi lịch/hoàn tiền</li>
                                <li>Tạo bảng <code>tour_providers</code> - Quản lý nhà cung cấp dịch vụ (khách sạn, nhà hàng, vận chuyển...)</li>
                                <li>Tạo bảng <code>bookings</code> - Quản lý booking cho khách lẻ và đoàn</li>
                                <li>Tạo bảng <code>booking_guests</code> - Lưu thông tin chi tiết khách (cho đoàn)</li>
                                <li>Tạo bảng <code>booking_status_history</code> - Lưu lịch sử thay đổi trạng thái booking</li>
                                <li>Cập nhật bảng <code>tour_itinerary_detail</code> - Thêm thời gian và địa điểm chi tiết</li>
                                <li>Tạo bảng <code>tour_expenses</code> - Quản lý chi phí theo tour</li>
                                <li>Tạo bảng <code>tour_revenue_summary</code> - Tổng hợp doanh thu (cache)</li>
                            </ul>
                            <form method="POST">
                                <button type="submit" name="install" class="btn btn-primary btn-lg">
                                    <i class="bi bi-database"></i> Cài đặt ngay
                                </button>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

