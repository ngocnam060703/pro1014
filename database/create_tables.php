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
    
    echo "\n✅ Hoàn thành! Tất cả các bảng đã sẵn sàng.\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}

