<?php
/**
 * Script tự động sửa bảng guide_checkin
 * Chạy: php database/fix_guide_checkin.php
 */

require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

try {
    $conn = pdo_get_connection();
    
    echo "🔧 Đang sửa bảng guide_checkin...\n\n";
    
    // 1. Kiểm tra và xóa foreign key của booking_id
    $fkQuery = "SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'guide_checkin' 
                AND COLUMN_NAME = 'booking_id' 
                AND CONSTRAINT_NAME != 'PRIMARY'
                LIMIT 1";
    
    $fkResult = $conn->query($fkQuery);
    if ($fkResult && $fkRow = $fkResult->fetch(PDO::FETCH_ASSOC)) {
        $fkName = $fkRow['CONSTRAINT_NAME'];
        echo "   - Xóa foreign key: $fkName\n";
        $conn->exec("ALTER TABLE guide_checkin DROP FOREIGN KEY `$fkName`");
    }
    
    // 2. Xóa index booking_id (nếu có)
    $idxQuery = "SELECT INDEX_NAME 
                 FROM INFORMATION_SCHEMA.STATISTICS 
                 WHERE TABLE_SCHEMA = DATABASE() 
                 AND TABLE_NAME = 'guide_checkin' 
                 AND INDEX_NAME = 'booking_id'
                 LIMIT 1";
    
    $idxResult = $conn->query($idxQuery);
    if ($idxResult && $idxRow = $idxResult->fetch(PDO::FETCH_ASSOC)) {
        echo "   - Xóa index: booking_id\n";
        $conn->exec("ALTER TABLE guide_checkin DROP INDEX booking_id");
    }
    
    // 3. Xóa các cột không cần thiết
    $columnsToDrop = ['booking_id', 'checkin_time', 'checkin_location', 'status', 'notes'];
    
    foreach ($columnsToDrop as $column) {
        $checkQuery = "SELECT COUNT(*) as cnt 
                      FROM INFORMATION_SCHEMA.COLUMNS 
                      WHERE TABLE_SCHEMA = DATABASE() 
                      AND TABLE_NAME = 'guide_checkin' 
                      AND COLUMN_NAME = '$column'";
        
        $checkResult = $conn->query($checkQuery);
        if ($checkResult && $checkRow = $checkResult->fetch(PDO::FETCH_ASSOC)) {
            if ($checkRow['cnt'] > 0) {
                echo "   - Xóa cột: $column\n";
                try {
                    $conn->exec("ALTER TABLE guide_checkin DROP COLUMN `$column`");
                } catch (PDOException $e) {
                    echo "     ⚠️  Lỗi khi xóa cột $column: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    // 4. Thêm cột checked_in_at nếu chưa có
    $checkQuery = "SELECT COUNT(*) as cnt 
                   FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'guide_checkin' 
                   AND COLUMN_NAME = 'checked_in_at'";
    
    $checkResult = $conn->query($checkQuery);
    if ($checkResult && $checkRow = $checkResult->fetch(PDO::FETCH_ASSOC)) {
        if ($checkRow['cnt'] == 0) {
            echo "   - Thêm cột: checked_in_at\n";
            $conn->exec("ALTER TABLE guide_checkin 
                         ADD COLUMN `checked_in_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP 
                         COMMENT 'Thời gian check-in' AFTER `departure_id`");
        } else {
            echo "   - Cột checked_in_at đã tồn tại\n";
        }
    }
    
    // 5. Thêm unique constraint nếu chưa có
    $constraintQuery = "SELECT COUNT(*) as cnt 
                        FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                        WHERE TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = 'guide_checkin' 
                        AND CONSTRAINT_NAME = 'unique_guide_departure'";
    
    $constraintResult = $conn->query($constraintQuery);
    if ($constraintResult && $constraintRow = $constraintResult->fetch(PDO::FETCH_ASSOC)) {
        if ($constraintRow['cnt'] == 0) {
            echo "   - Thêm unique constraint: unique_guide_departure\n";
            $conn->exec("ALTER TABLE guide_checkin 
                         ADD UNIQUE KEY `unique_guide_departure` (`guide_id`, `departure_id`)");
        } else {
            echo "   - Unique constraint đã tồn tại\n";
        }
    }
    
    echo "\n✅ Đã sửa bảng guide_checkin thành công!\n";
    echo "\n📋 Cấu trúc bảng hiện tại:\n";
    
    $columns = $conn->query("SHOW COLUMNS FROM guide_checkin");
    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$col['Field']} ({$col['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}


