-- ============================================
-- SEED DỮ LIỆU MẪU CHO HỆ THỐNG HƯỚNG DẪN VIÊN
-- ============================================
-- Chạy file này sau khi đã tạo các bảng
-- Sử dụng: Import vào phpMyAdmin hoặc chạy trong MySQL

USE travel_system;

-- ============================================
-- 1. TẠO DỮ LIỆU HƯỚNG DẪN VIÊN (guides)
-- ============================================
INSERT INTO `guides` (`fullname`, `phone`, `email`, `certificate`, `account_id`, `password`) VALUES
('Nguyễn Văn An', '0901234567', 'nguyenvanan@example.com', 'HDV-001', 'hdv001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Trần Thị Bình', '0912345678', 'tranthibinh@example.com', 'HDV-002', 'hdv002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Lê Văn Cường', '0923456789', 'levancuong@example.com', 'HDV-003', 'hdv003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Phạm Thị Dung', '0934567890', 'phamthidung@example.com', 'HDV-004', 'hdv004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Hoàng Văn Em', '0945678901', 'hoangvanem@example.com', 'HDV-005', 'hdv005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- ============================================
-- 2. TẠO TOUR MẪU (nếu chưa có)
-- ============================================
-- Kiểm tra và tạo tour nếu chưa có
INSERT INTO `tours` (`title`, `description`, `itinerary`, `price`, `slots`, `departure`, `status`) 
SELECT * FROM (
    SELECT 'Tour Hạ Long 2N1Đ' as title, 
           'Khám phá vịnh Hạ Long - Di sản thiên nhiên thế giới' as description,
           'Ngày 1: Hà Nội - Hạ Long - Tham quan hang Sửng Sốt\nNgày 2: Chèo kayak - Về Hà Nội' as itinerary,
           2500000 as price, 30 as slots, 'Hà Nội' as departure, 'active' as status
    UNION ALL
    SELECT 'Tour Ninh Bình 1 Ngày', 
           'Tham quan Tam Cốc - Bích Động - Chùa Bái Đính',
           'Sáng: Tam Cốc - Bích Động\nChiều: Chùa Bái Đính - Về Hà Nội',
           1500000, 40, 'Hà Nội', 'active'
    UNION ALL
    SELECT 'Tour Sapa 3N2Đ',
           'Khám phá Sapa - Fansipan - Bản Cát Cát',
           'Ngày 1: Hà Nội - Sapa\nNgày 2: Fansipan - Bản Cát Cát\nNgày 3: Sapa - Hà Nội',
           3500000, 25, 'Hà Nội', 'active'
    UNION ALL
    SELECT 'Tour Đà Nẵng - Hội An 3N2Đ',
           'Tham quan Đà Nẵng - Hội An cổ kính',
           'Ngày 1: Đà Nẵng - Bà Nà Hills\nNgày 2: Hội An - Phố cổ\nNgày 3: Về Hà Nội',
           4500000, 20, 'Đà Nẵng', 'active'
    UNION ALL
    SELECT 'Tour Phú Quốc 4N3Đ',
           'Nghỉ dưỡng tại đảo ngọc Phú Quốc',
           'Ngày 1: Đến Phú Quốc\nNgày 2-3: Tham quan đảo\nNgày 4: Về Hà Nội',
           6000000, 15, 'Phú Quốc', 'active'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM tours WHERE tours.title = tmp.title)
LIMIT 5;

-- ============================================
-- 3. TẠO DEPARTURE MẪU (lịch khởi hành)
-- ============================================
-- Lấy tour_id từ tours vừa tạo hoặc đã có
SET @tour_halong = (SELECT id FROM tours WHERE title LIKE '%Hạ Long%' LIMIT 1);
SET @tour_ninhbinh = (SELECT id FROM tours WHERE title LIKE '%Ninh Bình%' LIMIT 1);
SET @tour_sapa = (SELECT id FROM tours WHERE title LIKE '%Sapa%' LIMIT 1);
SET @tour_danang = (SELECT id FROM tours WHERE title LIKE '%Đà Nẵng%' LIMIT 1);
SET @tour_phuquoc = (SELECT id FROM tours WHERE title LIKE '%Phú Quốc%' LIMIT 1);

-- Tạo departures nếu chưa có
INSERT INTO `departures` (`tour_id`, `departure_time`, `meeting_point`, `seats_available`, `notes`)
SELECT * FROM (
    SELECT @tour_halong as tour_id, 
           DATE_ADD(NOW(), INTERVAL 7 DAY) as departure_time,
           'Sân bay Nội Bài - Cổng A' as meeting_point,
           30 as seats_available,
           'Xuất phát lúc 6:00 sáng' as notes
    UNION ALL
    SELECT @tour_halong, DATE_ADD(NOW(), INTERVAL 14 DAY), 'Sân bay Nội Bài - Cổng A', 30, 'Xuất phát lúc 6:00 sáng'
    UNION ALL
    SELECT @tour_ninhbinh, DATE_ADD(NOW(), INTERVAL 5 DAY), 'Khách sạn Sofitel - Hà Nội', 40, 'Xuất phát lúc 7:00 sáng'
    UNION ALL
    SELECT @tour_ninhbinh, DATE_ADD(NOW(), INTERVAL 12 DAY), 'Khách sạn Sofitel - Hà Nội', 40, 'Xuất phát lúc 7:00 sáng'
    UNION ALL
    SELECT @tour_sapa, DATE_ADD(NOW(), INTERVAL 10 DAY), 'Ga Lào Cai', 25, 'Tàu SP1 - 22:00'
    UNION ALL
    SELECT @tour_sapa, DATE_ADD(NOW(), INTERVAL 17 DAY), 'Ga Lào Cai', 25, 'Tàu SP3 - 22:00'
    UNION ALL
    SELECT @tour_danang, DATE_ADD(NOW(), INTERVAL 8 DAY), 'Sân bay Đà Nẵng', 20, 'Bay VN123 - 8:00'
    UNION ALL
    SELECT @tour_phuquoc, DATE_ADD(NOW(), INTERVAL 15 DAY), 'Sân bay Phú Quốc', 15, 'Bay VJ456 - 10:00'
) AS tmp
WHERE tour_id IS NOT NULL
LIMIT 8;

-- ============================================
-- 4. PHÂN CÔNG TOUR CHO HƯỚNG DẪN VIÊN (guide_assign)
-- ============================================
-- Lấy guide_id
SET @guide1 = (SELECT id FROM guides WHERE account_id = 'hdv001' LIMIT 1);
SET @guide2 = (SELECT id FROM guides WHERE account_id = 'hdv002' LIMIT 1);
SET @guide3 = (SELECT id FROM guides WHERE account_id = 'hdv003' LIMIT 1);
SET @guide4 = (SELECT id FROM guides WHERE account_id = 'hdv004' LIMIT 1);
SET @guide5 = (SELECT id FROM guides WHERE account_id = 'hdv005' LIMIT 1);

-- Lấy departure_id
SET @dep1 = (SELECT id FROM departures ORDER BY id LIMIT 1);
SET @dep2 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 1);
SET @dep3 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 2);
SET @dep4 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 3);
SET @dep5 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 4);
SET @dep6 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 5);
SET @dep7 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 6);
SET @dep8 = (SELECT id FROM departures ORDER BY id LIMIT 1 OFFSET 7);

-- Lấy tour_id từ departures
SET @tour1 = (SELECT tour_id FROM departures WHERE id = @dep1);
SET @tour2 = (SELECT tour_id FROM departures WHERE id = @dep2);
SET @tour3 = (SELECT tour_id FROM departures WHERE id = @dep3);
SET @tour4 = (SELECT tour_id FROM departures WHERE id = @dep4);
SET @tour5 = (SELECT tour_id FROM departures WHERE id = @dep5);
SET @tour6 = (SELECT tour_id FROM departures WHERE id = @dep6);
SET @tour7 = (SELECT tour_id FROM departures WHERE id = @dep7);
SET @tour8 = (SELECT tour_id FROM departures WHERE id = @dep8);

-- Lấy thông tin departure
SET @meeting1 = (SELECT meeting_point FROM departures WHERE id = @dep1);
SET @meeting2 = (SELECT meeting_point FROM departures WHERE id = @dep2);
SET @meeting3 = (SELECT meeting_point FROM departures WHERE id = @dep3);
SET @meeting4 = (SELECT meeting_point FROM departures WHERE id = @dep4);
SET @meeting5 = (SELECT meeting_point FROM departures WHERE id = @dep5);
SET @meeting6 = (SELECT meeting_point FROM departures WHERE id = @dep6);
SET @meeting7 = (SELECT meeting_point FROM departures WHERE id = @dep7);
SET @meeting8 = (SELECT meeting_point FROM departures WHERE id = @dep8);

SET @seats1 = (SELECT seats_available FROM departures WHERE id = @dep1);
SET @seats2 = (SELECT seats_available FROM departures WHERE id = @dep2);
SET @seats3 = (SELECT seats_available FROM departures WHERE id = @dep3);
SET @seats4 = (SELECT seats_available FROM departures WHERE id = @dep4);
SET @seats5 = (SELECT seats_available FROM departures WHERE id = @dep5);
SET @seats6 = (SELECT seats_available FROM departures WHERE id = @dep6);
SET @seats7 = (SELECT seats_available FROM departures WHERE id = @dep7);
SET @seats8 = (SELECT seats_available FROM departures WHERE id = @dep8);

-- Lấy departure_date từ departure_time
SET @dep_date1 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep1);
SET @dep_date2 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep2);
SET @dep_date3 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep3);
SET @dep_date4 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep4);
SET @dep_date5 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep5);
SET @dep_date6 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep6);
SET @dep_date7 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep7);
SET @dep_date8 = (SELECT DATE(departure_time) FROM departures WHERE id = @dep8);

-- Chèn phân công
INSERT INTO `guide_assign` (`guide_id`, `departure_id`, `tour_id`, `departure_date`, `meeting_point`, `max_people`, `note`, `status`, `assigned_at`)
SELECT * FROM (
    SELECT @guide1 as guide_id, @dep1 as departure_id, @tour1 as tour_id, 
           @dep_date1 as departure_date, @meeting1 as meeting_point, @seats1 as max_people,
           'HDV chính - Có kinh nghiệm 5 năm' as note, 'scheduled' as status, NOW() as assigned_at
    UNION ALL
    SELECT @guide2, @dep2, @tour2, @dep_date2, @meeting2, @seats2, 'HDV phụ - Mới vào nghề', 'scheduled', NOW()
    UNION ALL
    SELECT @guide1, @dep3, @tour3, @dep_date3, @meeting3, @seats3, 'HDV chính', 'scheduled', NOW()
    UNION ALL
    SELECT @guide3, @dep4, @tour4, @dep_date4, @meeting4, @seats4, 'HDV chuyên tour miền Bắc', 'scheduled', NOW()
    UNION ALL
    SELECT @guide4, @dep5, @tour5, @dep_date5, @meeting5, @seats5, 'HDV chuyên tour miền Trung', 'scheduled', NOW()
    UNION ALL
    SELECT @guide5, @dep6, @tour6, @dep_date6, @meeting6, @seats6, 'HDV chuyên tour miền Nam', 'scheduled', NOW()
    UNION ALL
    SELECT @guide2, @dep7, @tour7, @dep_date7, @meeting7, @seats7, 'HDV phụ', 'scheduled', NOW()
    UNION ALL
    SELECT @guide3, @dep8, @tour8, @dep_date8, @meeting8, @seats8, 'HDV chính', 'scheduled', NOW()
) AS tmp
WHERE guide_id IS NOT NULL AND departure_id IS NOT NULL
LIMIT 8;

-- ============================================
-- 5. TẠO NHẬT KÝ MẪU (guide_journal)
-- ============================================
-- Lấy một số departure_id và guide_id đã phân công
INSERT INTO `guide_journal` (`guide_id`, `departure_id`, `note`, `created_at`)
SELECT guide_id, departure_id, note_text, created_date
FROM (
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1) as guide_id,
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1) as departure_id,
        'Tour diễn ra tốt đẹp. Khách hàng rất hài lòng với dịch vụ. Thời tiết đẹp, không có sự cố gì. Khách tham quan tích cực và tuân thủ quy định.' as note_text,
        DATE_SUB(NOW(), INTERVAL 2 DAY) as created_date
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 1),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 1),
        'Tour có một số khách đến muộn nhưng đã xử lý kịp thời. Hướng dẫn viên hỗ trợ tốt. Khách hàng phản hồi tích cực về chất lượng tour.',
        DATE_SUB(NOW(), INTERVAL 1 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 2),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 2),
        'Tour hoàn thành thành công. Khách hàng rất thích thú với các điểm tham quan. Cần cải thiện thêm về thời gian nghỉ giữa các điểm.',
        NOW()
) AS tmp
WHERE guide_id IS NOT NULL AND departure_id IS NOT NULL
LIMIT 3;

-- ============================================
-- 6. TẠO BÁO CÁO SỰ CỐ MẪU (guide_incidents)
-- ============================================
-- Bước 1: Cập nhật dữ liệu cũ nếu có giá trị không hợp lệ
UPDATE `guide_incidents` 
SET `severity` = 'low' 
WHERE `severity` NOT IN ('low', 'medium', 'high') OR `severity` IS NULL;

-- Bước 2: Sửa enum (chỉ chạy nếu bảng đã tồn tại)
-- Nếu bảng chưa tồn tại, bỏ qua lỗi này
SET @exist := (SELECT COUNT(*) FROM information_schema.tables 
               WHERE table_schema = 'travel_system' 
               AND table_name = 'guide_incidents');
SET @sqlstmt := IF(@exist > 0,
    'ALTER TABLE `guide_incidents` MODIFY COLUMN `severity` enum(''low'',''medium'',''high'') DEFAULT ''low''',
    'SELECT ''Table does not exist'' as message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Bước 3: Chèn dữ liệu mẫu sự cố
INSERT INTO `guide_incidents` (`guide_id`, `departure_id`, `incident_type`, `severity`, `description`, `solution`, `photos`, `created_at`)
SELECT guide_id, departure_id, incident_type, severity, description, solution, photos, created_date
FROM (
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1) as guide_id,
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1) as departure_id,
        'Khách hàng' as incident_type,
        'low' as severity,
        'Một khách hàng bị say xe nhẹ trong quá trình di chuyển' as description,
        'Đã cung cấp túi nôn và thuốc chống say xe. Khách đã ổn sau 30 phút.' as solution,
        NULL as photos,
        DATE_SUB(NOW(), INTERVAL 5 DAY) as created_date
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 1),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 1),
        'Phương tiện',
        'high' as severity,
        'Xe du lịch bị hỏng điều hòa giữa đường' as description,
        'Đã gọi xe thay thế. Tour tiếp tục với độ trễ 1 giờ. Đã thông báo và xin lỗi khách hàng.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 4 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 2),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 2),
        'Thời tiết',
        'low' as severity,
        'Trời mưa nhẹ ảnh hưởng đến một số hoạt động ngoài trời' as description,
        'Đã điều chỉnh lịch trình, chuyển sang các hoạt động trong nhà. Khách hàng vẫn hài lòng.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 3 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 3),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 3),
        'Khách hàng',
        'low' as severity,
        'Một khách hàng quên đồ cá nhân tại khách sạn' as description,
        'Đã liên hệ với khách sạn để tìm và gửi đồ về địa chỉ khách hàng. Khách hàng rất cảm ơn.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 2 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 4),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 4),
        'Dịch vụ',
        'low' as severity,
        'Nhà hàng phục vụ chậm, khách hàng phàn nàn' as description,
        'Đã trao đổi với quản lý nhà hàng để cải thiện tốc độ phục vụ. Đã xin lỗi khách hàng và bù đắp bằng món tráng miệng miễn phí.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 1 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1),
        'Phương tiện',
        'low' as severity,
        'Xe bị kẹt xe do tai nạn trên đường' as description,
        'Đã thông báo cho khách hàng và điều chỉnh lịch trình. Khách hàng hiểu và đồng ý với thay đổi.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 6 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 1),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 1),
        'Khách hàng',
        'low' as severity,
        'Một khách hàng bị dị ứng với thức ăn' as description,
        'Đã đưa khách đến bệnh viện gần nhất. Khách đã được điều trị và ổn định. Đã thông báo cho công ty và gia đình khách hàng.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 7 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 2),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 2),
        'Dịch vụ',
        'low' as severity,
        'Khách sạn không có phòng như đã đặt' as description,
        'Đã liên hệ với công ty để tìm khách sạn thay thế. Đã tìm được khách sạn tương đương và di chuyển khách hàng. Khách hàng hài lòng với giải pháp.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 8 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 3),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 3),
        'Thời tiết',
        'low' as severity,
        'Sương mù dày đặc làm giảm tầm nhìn khi di chuyển' as description,
        'Đã điều chỉnh tốc độ và thời gian di chuyển để đảm bảo an toàn. Khách hàng được thông báo và đồng ý.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 9 DAY)
    UNION ALL
    SELECT 
        (SELECT guide_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 4),
        (SELECT departure_id FROM guide_assign ORDER BY id LIMIT 1 OFFSET 4),
        'Khách hàng',
        'low' as severity,
        'Một nhóm khách đi lạc trong khu vực tham quan' as description,
        'Đã liên hệ với ban quản lý khu vực và tìm thấy nhóm khách sau 30 phút. Đã nhắc nhở khách về việc đi theo đoàn.' as solution,
        NULL,
        DATE_SUB(NOW(), INTERVAL 10 DAY)
) AS tmp
WHERE guide_id IS NOT NULL AND departure_id IS NOT NULL;

-- ============================================
-- HOÀN THÀNH
-- ============================================
SELECT '✅ Đã tạo dữ liệu mẫu thành công!' as message;
SELECT COUNT(*) as total_guides FROM guides;
SELECT COUNT(*) as total_assigns FROM guide_assign;
SELECT COUNT(*) as total_journals FROM guide_journal;
SELECT COUNT(*) as total_incidents FROM guide_incidents;

