-- ============================================
-- SỬA LỖI BẢNG BOOKINGS - THÊM CÁC CỘT CÒN THIẾU
-- ============================================
-- Chạy file này để thêm các cột còn thiếu vào bảng bookings
-- ============================================

-- Thêm cột departure_id nếu chưa có
SET @dbname = DATABASE();
SET @tablename = 'bookings';
SET @columnname = 'departure_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' int(11) DEFAULT NULL COMMENT ''ID lịch khởi hành'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm index cho departure_id
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = 'departure_id')
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD KEY departure_id (departure_id);')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm cột departure_date nếu chưa có
SET @columnname = 'departure_date';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' date DEFAULT NULL COMMENT ''Ngày khởi hành'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm index cho departure_date
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = 'departure_date')
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD KEY departure_date (departure_date);')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm cột booking_code nếu chưa có
SET @columnname = 'booking_code';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' varchar(50) DEFAULT NULL COMMENT ''Mã booking tự động'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm unique key cho booking_code
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = 'booking_code')
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD UNIQUE KEY booking_code (booking_code);')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm cột booking_type nếu chưa có
SET @columnname = 'booking_type';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' enum(''individual'',''group'') DEFAULT ''individual'' COMMENT ''Loại booking'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm cột customer_address nếu chưa có
SET @columnname = 'customer_address';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' text DEFAULT NULL COMMENT ''Địa chỉ'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm cột special_requests nếu chưa có
SET @columnname = 'special_requests';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' text DEFAULT NULL COMMENT ''Yêu cầu đặc biệt'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm cột notes nếu chưa có
SET @columnname = 'notes';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' text DEFAULT NULL COMMENT ''Ghi chú'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Thêm các cột còn lại cùng lúc (nếu chưa có company_name)
SET @columnname = 'company_name';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' 
    ADD COLUMN company_name varchar(255) DEFAULT NULL COMMENT ''Tên công ty/tổ chức'',
    ADD COLUMN tax_code varchar(50) DEFAULT NULL COMMENT ''Mã số thuế'',
    ADD COLUMN num_adults int(11) DEFAULT 0 COMMENT ''Số lượng người lớn'',
    ADD COLUMN num_children int(11) DEFAULT 0 COMMENT ''Số lượng trẻ em'',
    ADD COLUMN num_infants int(11) DEFAULT 0 COMMENT ''Số lượng trẻ sơ sinh'',
    ADD COLUMN base_price decimal(15,2) DEFAULT 0 COMMENT ''Giá cơ bản'',
    ADD COLUMN adult_price decimal(15,2) DEFAULT 0 COMMENT ''Giá người lớn'',
    ADD COLUMN child_price decimal(15,2) DEFAULT 0 COMMENT ''Giá trẻ em'',
    ADD COLUMN infant_price decimal(15,2) DEFAULT 0 COMMENT ''Giá trẻ sơ sinh'',
    ADD COLUMN discount_amount decimal(15,2) DEFAULT 0 COMMENT ''Số tiền giảm giá'',
    ADD COLUMN discount_percentage decimal(5,2) DEFAULT 0 COMMENT ''Phần trăm giảm giá'',
    ADD COLUMN deposit_amount decimal(15,2) DEFAULT 0 COMMENT ''Số tiền đặt cọc'',
    ADD COLUMN remaining_amount decimal(15,2) DEFAULT 0 COMMENT ''Số tiền còn lại'',
    ADD COLUMN payment_status enum(''pending'',''partial'',''paid'',''refunded'') DEFAULT ''pending'' COMMENT ''Trạng thái thanh toán'',
    ADD COLUMN confirmed_at datetime DEFAULT NULL COMMENT ''Thời gian xác nhận'',
    ADD COLUMN cancelled_at datetime DEFAULT NULL COMMENT ''Thời gian hủy'',
    ADD COLUMN cancellation_reason text DEFAULT NULL COMMENT ''Lý do hủy'',
    ADD COLUMN created_by int(11) DEFAULT NULL COMMENT ''ID nhân viên tạo'';')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SELECT '✅ Đã cập nhật bảng bookings thành công!' as message;

