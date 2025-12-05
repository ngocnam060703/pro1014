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

