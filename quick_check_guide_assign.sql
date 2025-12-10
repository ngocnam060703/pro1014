-- ============================================
-- QUICK CHECK - Phân công HDV Guide ID = 5
-- Chạy từng query một trong phpMyAdmin
-- ============================================

-- 1. Kiểm tra Guide có tồn tại không
SELECT id, fullname, status FROM guides WHERE id = 5;

-- 2. Đếm tất cả phân công (kể cả cancelled)
SELECT COUNT(*) as total FROM guide_assign WHERE guide_id = 5;

-- 3. Đếm phân công chưa hủy
SELECT COUNT(*) as active FROM guide_assign WHERE guide_id = 5 AND status != 'cancelled';

-- 4. Xem tất cả phân công (kể cả cancelled)
SELECT 
    id,
    guide_id,
    departure_id,
    tour_id,
    status,
    assigned_at,
    assigned_by,
    created_at
FROM guide_assign 
WHERE guide_id = 5 
ORDER BY id DESC;

-- 5. Xem phân công chưa hủy
SELECT 
    id,
    guide_id,
    departure_id,
    tour_id,
    status,
    assigned_at
FROM guide_assign 
WHERE guide_id = 5 
  AND status != 'cancelled'
ORDER BY id DESC;

-- 6. Kiểm tra departure có tồn tại không (nếu có phân công)
-- Thay ? bằng departure_id từ query 4 hoặc 5
SELECT id, tour_id, departure_time, status FROM departures WHERE id = ?;

-- 7. Kiểm tra tour có tồn tại không (nếu có phân công)
-- Thay ? bằng tour_id từ query 4 hoặc 5
SELECT id, title, status FROM tours WHERE id = ?;

-- 8. Query giống hệ thống (với JOIN)
SELECT 
    ga.id,
    ga.guide_id,
    ga.departure_id,
    ga.tour_id,
    ga.status,
    ga.assigned_at,
    t.title as tour_name,
    d.departure_time
FROM guide_assign ga
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
WHERE ga.guide_id = 5 
  AND ga.status != 'cancelled'
ORDER BY ga.assigned_at DESC, ga.id DESC;

-- 9. Xem 10 phân công mới nhất (tất cả HDV) để so sánh
SELECT 
    ga.id,
    ga.guide_id,
    g.fullname as guide_name,
    ga.departure_id,
    ga.status,
    ga.assigned_at
FROM guide_assign ga
LEFT JOIN guides g ON ga.guide_id = g.id
ORDER BY ga.id DESC
LIMIT 10;

