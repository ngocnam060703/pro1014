<?php
/**
 * Script tạo các bảng cần thiết cho hệ thống HDV
 * Chạy file này một lần để tạo các bảng: guide_journal, guide_incidents
 */

require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

try {
    $conn = pdo_get_connection();
    
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
    echo "✓ Bảng guide_journal đã được tạo thành công!\n";
    
    // Tạo bảng guide_incidents nếu chưa có
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
    echo "✓ Bảng guide_incidents đã được tạo thành công!\n";
    
    // Tạo bảng guide_assign nếu chưa có
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
    echo "✓ Bảng guide_assign đã được kiểm tra/tạo thành công!\n";
    
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
    echo "✓ Bảng tour_itinerary_detail đã được tạo thành công!\n";
    
    // Tạo bảng guide_checkin
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
    echo "✓ Bảng guide_checkin đã được tạo thành công!\n";
    
    // Tạo bảng customer_special_requests
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
    echo "✓ Bảng customer_special_requests đã được tạo thành công!\n";
    
    // Tạo bảng guide_feedback
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
    echo "✓ Bảng guide_feedback đã được tạo thành công!\n";
    
    // Cập nhật bảng guide_journal để thêm các trường mới
    try {
        $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN `day_number` int(11) DEFAULT NULL");
    } catch (PDOException $e) {
        // Bỏ qua nếu cột đã tồn tại
    }
    try {
        $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN `activities` text DEFAULT NULL");
    } catch (PDOException $e) {}
    try {
        $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN `photos` text DEFAULT NULL");
    } catch (PDOException $e) {}
    try {
        $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN `customer_feedback` text DEFAULT NULL");
    } catch (PDOException $e) {}
    try {
        $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN `weather` varchar(100) DEFAULT NULL");
    } catch (PDOException $e) {}
    try {
        $conn->exec("ALTER TABLE `guide_journal` ADD COLUMN `mood` varchar(50) DEFAULT NULL");
    } catch (PDOException $e) {}
    
    echo "✓ Đã cập nhật bảng guide_journal với các trường mới!\n";
    
    echo "\n✅ Hoàn thành! Tất cả các bảng đã sẵn sàng.\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}

