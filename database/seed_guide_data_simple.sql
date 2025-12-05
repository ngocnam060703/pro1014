-- ============================================
-- SEED DỮ LIỆU MẪU ĐƠN GIẢN CHO HƯỚNG DẪN VIÊN
-- ============================================
-- File này tạo dữ liệu mẫu đơn giản hơn, không phụ thuộc vào tours/departures có sẵn

USE travel_system;

-- Xóa dữ liệu cũ (tùy chọn - cẩn thận!)
-- DELETE FROM guide_incidents;
-- DELETE FROM guide_journal;
-- DELETE FROM guide_assign;
-- DELETE FROM guides WHERE account_id LIKE 'hdv%';

-- ============================================
-- 1. TẠO HƯỚNG DẪN VIÊN
-- ============================================
INSERT INTO `guides` (`fullname`, `phone`, `email`, `certificate`, `account_id`, `password`) VALUES
('Nguyễn Văn An', '0901234567', 'nguyenvanan@example.com', 'HDV-001', 'hdv001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Trần Thị Bình', '0912345678', 'tranthibinh@example.com', 'HDV-002', 'hdv002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Lê Văn Cường', '0923456789', 'levancuong@example.com', 'HDV-003', 'hdv003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE fullname=VALUES(fullname);

-- Lưu ý: Password mặc định là "password" (đã hash bằng bcrypt)
-- Bạn có thể đăng nhập với:
-- - Tài khoản: hdv001, hdv002, hdv003
-- - Mật khẩu: password

-- ============================================
-- 2. TẠO TOUR MẪU (nếu bảng tours đã có)
-- ============================================
-- Chỉ chèn nếu tours table tồn tại và chưa có dữ liệu
INSERT INTO `tours` (`title`, `description`, `itinerary`, `price`, `slots`, `departure`, `status`) 
SELECT 'Tour Hạ Long 2N1Đ', 
       'Khám phá vịnh Hạ Long - Di sản thiên nhiên thế giới',
       'Ngày 1: Hà Nội - Hạ Long - Tham quan hang Sửng Sốt\nNgày 2: Chèo kayak - Về Hà Nội',
       2500000, 30, 'Hà Nội', 'active'
WHERE NOT EXISTS (SELECT 1 FROM tours WHERE title = 'Tour Hạ Long 2N1Đ')
LIMIT 1;

INSERT INTO `tours` (`title`, `description`, `itinerary`, `price`, `slots`, `departure`, `status`) 
SELECT 'Tour Ninh Bình 1 Ngày', 
       'Tham quan Tam Cốc - Bích Động - Chùa Bái Đính',
       'Sáng: Tam Cốc - Bích Động\nChiều: Chùa Bái Đính - Về Hà Nội',
       1500000, 40, 'Hà Nội', 'active'
WHERE NOT EXISTS (SELECT 1 FROM tours WHERE title = 'Tour Ninh Bình 1 Ngày')
LIMIT 1;

-- ============================================
-- 3. TẠO DEPARTURE MẪU (nếu bảng departures đã có)
-- ============================================
-- Lấy tour_id đầu tiên
SET @tour_id = (SELECT id FROM tours ORDER BY id LIMIT 1);

-- Tạo departures nếu có tour
INSERT INTO `departures` (`tour_id`, `departure_time`, `meeting_point`, `seats_available`, `notes`)
SELECT @tour_id, 
       DATE_ADD(NOW(), INTERVAL 7 DAY),
       'Sân bay Nội Bài - Cổng A',
       30,
       'Xuất phát lúc 6:00 sáng'
WHERE @tour_id IS NOT NULL 
  AND NOT EXISTS (SELECT 1 FROM departures WHERE tour_id = @tour_id AND meeting_point = 'Sân bay Nội Bài - Cổng A')
LIMIT 1;

INSERT INTO `departures` (`tour_id`, `departure_time`, `meeting_point`, `seats_available`, `notes`)
SELECT @tour_id,
       DATE_ADD(NOW(), INTERVAL 14 DAY),
       'Sân bay Nội Bài - Cổng A',
       30,
       'Xuất phát lúc 6:00 sáng'
WHERE @tour_id IS NOT NULL 
  AND NOT EXISTS (SELECT 1 FROM departures WHERE tour_id = @tour_id AND DATE(departure_time) = DATE(DATE_ADD(NOW(), INTERVAL 14 DAY)))
LIMIT 1;

-- ============================================
-- 4. PHÂN CÔNG TOUR CHO HDV
-- ============================================
-- Lấy guide_id và departure_id
SET @guide1 = (SELECT id FROM guides WHERE account_id = 'hdv001' LIMIT 1);
SET @guide2 = (SELECT id FROM guides WHERE account_id = 'hdv002' LIMIT 1);
SET @dep1 = (SELECT id FROM departures ORDER BY id LIMIT 1);
SET @dep2 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 1);
SET @tour1 = (SELECT tour_id FROM departures WHERE id = @dep1 LIMIT 1);
SET @tour2 = (SELECT tour_id FROM departures WHERE id = @dep2 LIMIT 1);

-- Chèn phân công
INSERT INTO `guide_assign` (`guide_id`, `departure_id`, `tour_id`, `departure_date`, `meeting_point`, `max_people`, `note`, `status`, `assigned_at`)
SELECT @guide1, @dep1, @tour1, DATE((SELECT departure_time FROM departures WHERE id = @dep1)), 
       (SELECT meeting_point FROM departures WHERE id = @dep1),
       (SELECT seats_available FROM departures WHERE id = @dep1),
       'HDV chính - Có kinh nghiệm 5 năm', 'scheduled', NOW()
WHERE @guide1 IS NOT NULL AND @dep1 IS NOT NULL
LIMIT 1;

INSERT INTO `guide_assign` (`guide_id`, `departure_id`, `tour_id`, `departure_date`, `meeting_point`, `max_people`, `note`, `status`, `assigned_at`)
SELECT @guide2, @dep2, @tour2, DATE((SELECT departure_time FROM departures WHERE id = @dep2)),
       (SELECT meeting_point FROM departures WHERE id = @dep2),
       (SELECT seats_available FROM departures WHERE id = @dep2),
       'HDV phụ - Mới vào nghề', 'scheduled', NOW()
WHERE @guide2 IS NOT NULL AND @dep2 IS NOT NULL
LIMIT 1;

-- ============================================
-- 5. TẠO NHẬT KÝ MẪU
-- ============================================
INSERT INTO `guide_journal` (`guide_id`, `departure_id`, `note`, `created_at`)
SELECT guide_id, departure_id, 
       'Tour diễn ra tốt đẹp. Khách hàng rất hài lòng với dịch vụ. Thời tiết đẹp, không có sự cố gì.',
       DATE_SUB(NOW(), INTERVAL 2 DAY)
FROM guide_assign
ORDER BY id LIMIT 1;

-- ============================================
-- 6. TẠO BÁO CÁO SỰ CỐ MẪU
-- ============================================
INSERT INTO `guide_incidents` (`guide_id`, `departure_id`, `incident_type`, `severity`, `description`, `solution`, `created_at`)
SELECT guide_id, departure_id, incident_type, severity, description, solution, created_date
FROM (
    SELECT guide_id, departure_id,
           'Khách hàng' as incident_type, 'low' as severity,
           'Một khách hàng bị say xe nhẹ trong quá trình di chuyển' as description,
           'Đã cung cấp túi nôn và thuốc chống say xe. Khách đã ổn sau 30 phút.' as solution,
           DATE_SUB(NOW(), INTERVAL 3 DAY) as created_date
    FROM guide_assign ORDER BY id LIMIT 1
    UNION ALL
    SELECT guide_id, departure_id,
           'Phương tiện', 'low',
           'Xe bị kẹt xe do tai nạn trên đường',
           'Đã thông báo cho khách hàng và điều chỉnh lịch trình. Khách hàng hiểu và đồng ý.',
           DATE_SUB(NOW(), INTERVAL 2 DAY)
    FROM guide_assign ORDER BY id LIMIT 1 OFFSET 1
    UNION ALL
    SELECT guide_id, departure_id,
           'Dịch vụ', 'low',
           'Nhà hàng phục vụ chậm, khách hàng phàn nàn',
           'Đã trao đổi với quản lý nhà hàng. Đã xin lỗi khách hàng và bù đắp bằng món tráng miệng miễn phí.',
           DATE_SUB(NOW(), INTERVAL 1 DAY)
    FROM guide_assign ORDER BY id LIMIT 1
) AS tmp
WHERE guide_id IS NOT NULL AND departure_id IS NOT NULL;

-- ============================================
-- KẾT QUẢ
-- ============================================
SELECT '✅ Hoàn thành tạo dữ liệu mẫu!' as message;
SELECT 
    (SELECT COUNT(*) FROM guides) as total_guides,
    (SELECT COUNT(*) FROM guide_assign) as total_assigns,
    (SELECT COUNT(*) FROM guide_journal) as total_journals,
    (SELECT COUNT(*) FROM guide_incidents) as total_incidents;

