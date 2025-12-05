-- ============================================
-- THÊM TẤT CẢ CÁC CỘT CÒN THIẾU VÀO BẢNG BOOKINGS
-- ============================================
-- LƯU Ý: Nếu cột đã tồn tại sẽ báo lỗi "Duplicate column name"
-- Bỏ qua các cột đã tồn tại và chỉ chạy các cột còn thiếu
-- ============================================
-- 
-- CÁCH SỬ DỤNG:
-- 1. Chạy file "add_missing_booking_columns_safe.sql" (khuyến nghị - tự động kiểm tra)
-- HOẶC
-- 2. Chạy từng câu lệnh dưới đây, bỏ qua các cột đã báo lỗi
-- ============================================

-- Bỏ qua các cột đã tồn tại, chỉ chạy các cột còn thiếu:

-- ALTER TABLE `bookings` ADD COLUMN `departure_id` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành';
ALTER TABLE `bookings` ADD COLUMN `departure_date` date DEFAULT NULL COMMENT 'Ngày khởi hành';
ALTER TABLE `bookings` ADD COLUMN `booking_code` varchar(50) DEFAULT NULL COMMENT 'Mã booking tự động';
ALTER TABLE `bookings` ADD COLUMN `booking_type` enum('individual','group') DEFAULT 'individual' COMMENT 'Loại booking';
ALTER TABLE `bookings` ADD COLUMN `customer_address` text DEFAULT NULL COMMENT 'Địa chỉ';
ALTER TABLE `bookings` ADD COLUMN `special_requests` text DEFAULT NULL COMMENT 'Yêu cầu đặc biệt';
ALTER TABLE `bookings` ADD COLUMN `notes` text DEFAULT NULL COMMENT 'Ghi chú';
ALTER TABLE `bookings` ADD COLUMN `company_name` varchar(255) DEFAULT NULL COMMENT 'Tên công ty/tổ chức';
ALTER TABLE `bookings` ADD COLUMN `tax_code` varchar(50) DEFAULT NULL COMMENT 'Mã số thuế';
ALTER TABLE `bookings` ADD COLUMN `num_adults` int(11) DEFAULT 0 COMMENT 'Số lượng người lớn';
ALTER TABLE `bookings` ADD COLUMN `num_children` int(11) DEFAULT 0 COMMENT 'Số lượng trẻ em';
ALTER TABLE `bookings` ADD COLUMN `num_infants` int(11) DEFAULT 0 COMMENT 'Số lượng trẻ sơ sinh';
ALTER TABLE `bookings` ADD COLUMN `base_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá cơ bản';
ALTER TABLE `bookings` ADD COLUMN `adult_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá người lớn';
ALTER TABLE `bookings` ADD COLUMN `child_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ em';
ALTER TABLE `bookings` ADD COLUMN `infant_price` decimal(15,2) DEFAULT 0 COMMENT 'Giá trẻ sơ sinh';
ALTER TABLE `bookings` ADD COLUMN `discount_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền giảm giá';
ALTER TABLE `bookings` ADD COLUMN `discount_percentage` decimal(5,2) DEFAULT 0 COMMENT 'Phần trăm giảm giá';
ALTER TABLE `bookings` ADD COLUMN `deposit_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền đặt cọc';
ALTER TABLE `bookings` ADD COLUMN `remaining_amount` decimal(15,2) DEFAULT 0 COMMENT 'Số tiền còn lại';
ALTER TABLE `bookings` ADD COLUMN `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán';
ALTER TABLE `bookings` ADD COLUMN `confirmed_at` datetime DEFAULT NULL COMMENT 'Thời gian xác nhận';
ALTER TABLE `bookings` ADD COLUMN `cancelled_at` datetime DEFAULT NULL COMMENT 'Thời gian hủy';
ALTER TABLE `bookings` ADD COLUMN `cancellation_reason` text DEFAULT NULL COMMENT 'Lý do hủy';
ALTER TABLE `bookings` ADD COLUMN `created_by` int(11) DEFAULT NULL COMMENT 'ID nhân viên tạo';

-- Thêm các index (bỏ qua lỗi nếu đã tồn tại)
-- ALTER TABLE `bookings` ADD KEY `departure_id` (`departure_id`);
ALTER TABLE `bookings` ADD KEY `departure_date` (`departure_date`);
ALTER TABLE `bookings` ADD UNIQUE KEY `booking_code` (`booking_code`);

SELECT '✅ Hoàn thành! Đã thêm tất cả các cột còn thiếu vào bảng bookings.' as message;

