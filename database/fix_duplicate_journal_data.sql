-- ============================================
-- XỬ LÝ DỮ LIỆU TRÙNG LẶP TRONG GUIDE_JOURNAL
-- ============================================
-- Script này sẽ xóa các bản ghi trùng lặp, giữ lại bản ghi mới nhất
-- Chạy script này TRƯỚC KHI chạy update_guide_journal_system.sql
-- ============================================

-- Bước 1: Kiểm tra dữ liệu trùng lặp
SELECT 
    guide_id, 
    departure_id, 
    journal_date,
    COUNT(*) as duplicate_count
FROM guide_journal
WHERE journal_date IS NOT NULL
GROUP BY guide_id, departure_id, journal_date
HAVING COUNT(*) > 1;

-- Bước 2: Xóa dữ liệu trùng lặp (giữ lại bản ghi mới nhất - ID lớn nhất)
-- ⚠️ LƯU Ý: Script này sẽ XÓA các bản ghi cũ, chỉ giữ lại bản ghi mới nhất

DELETE gj1 FROM guide_journal gj1
INNER JOIN guide_journal gj2 
WHERE gj1.id < gj2.id 
AND gj1.guide_id = gj2.guide_id 
AND gj1.departure_id = gj2.departure_id
AND (
    (gj1.journal_date = gj2.journal_date) OR 
    (gj1.journal_date IS NULL AND gj2.journal_date IS NULL)
);

-- Bước 3: Xử lý các bản ghi có journal_date = NULL
-- Nếu có nhiều bản ghi với journal_date = NULL cho cùng (guide_id, departure_id)
-- Chỉ giữ lại bản ghi mới nhất

DELETE gj1 FROM guide_journal gj1
INNER JOIN guide_journal gj2 
WHERE gj1.id < gj2.id 
AND gj1.guide_id = gj2.guide_id 
AND gj1.departure_id = gj2.departure_id
AND gj1.journal_date IS NULL
AND gj2.journal_date IS NULL;

-- Bước 4: Cập nhật journal_date = NULL thành ngày hiện tại (nếu cần)
-- Chỉ áp dụng nếu bạn muốn tự động điền ngày cho các bản ghi cũ
-- UPDATE guide_journal 
-- SET journal_date = DATE(created_at)
-- WHERE journal_date IS NULL;

SELECT '✅ Đã xử lý dữ liệu trùng lặp thành công!' as message;
SELECT 'Bây giờ bạn có thể chạy update_guide_journal_system.sql' as next_step;

