-- ============================================
-- SCRIPT KIỂM TRA PHÂN CÔNG HDV
-- Thay đổi guide_id = 5 thành ID HDV của bạn
-- ============================================

-- 1. Kiểm tra Guide ID = 5 có tồn tại không
SELECT * FROM guides WHERE id = 5;

-- 2. Kiểm tra tất cả phân công của Guide ID = 5 (kể cả cancelled)
SELECT 
    ga.id,
    ga.guide_id,
    ga.departure_id,
    ga.tour_id,
    ga.status,
    ga.assigned_at,
    ga.assigned_by
FROM guide_assign ga
WHERE ga.guide_id = 5
ORDER BY ga.id DESC;

-- 3. Đếm số phân công của Guide ID = 5
SELECT 
    COUNT(*) as total_assignments,
    COUNT(CASE WHEN status != 'cancelled' THEN 1 END) as active_assignments,
    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_assignments
FROM guide_assign
WHERE guide_id = 5;

-- 4. Kiểm tra phân công với JOIN (query giống hệ thống)
SELECT 
    ga.id,
    ga.guide_id,
    ga.departure_id,
    ga.tour_id,
    ga.status,
    ga.assigned_at,
    t.title as tour_name,
    d.departure_time,
    d.end_date
FROM guide_assign ga
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
WHERE ga.guide_id = 5 
  AND ga.status != 'cancelled'
ORDER BY ga.assigned_at DESC, ga.id DESC;

-- 5. Kiểm tra phân công mới nhất (tất cả HDV) - 10 bản ghi
SELECT 
    ga.id,
    ga.guide_id,
    g.fullname as guide_name,
    ga.departure_id,
    ga.status,
    ga.assigned_at,
    t.title as tour_name
FROM guide_assign ga
LEFT JOIN guides g ON ga.guide_id = g.id
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
ORDER BY ga.id DESC
LIMIT 10;

-- 6. Kiểm tra phân công có vấn đề (departure hoặc tour không tồn tại)
SELECT 
    ga.id,
    ga.guide_id,
    ga.departure_id,
    ga.tour_id,
    ga.status,
    CASE WHEN d.id IS NULL THEN 'Departure không tồn tại' ELSE 'Departure OK' END as departure_check,
    CASE WHEN t.id IS NULL THEN 'Tour không tồn tại' ELSE 'Tour OK' END as tour_check
FROM guide_assign ga
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
WHERE ga.guide_id = 5 
  AND (d.id IS NULL OR t.id IS NULL);

-- 7. Kiểm tra tất cả guide có phân công
SELECT 
    g.id,
    g.fullname,
    COUNT(ga.id) as total_assignments,
    COUNT(CASE WHEN ga.status != 'cancelled' THEN 1 END) as active_assignments
FROM guides g
LEFT JOIN guide_assign ga ON g.id = ga.guide_id
GROUP BY g.id, g.fullname
ORDER BY total_assignments DESC;
