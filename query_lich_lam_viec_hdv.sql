-- ============================================
-- QUERY LẤY LỊCH LÀM VIỆC CỦA HDV
-- Thay đổi guide_id = 5 thành ID HDV của bạn
-- ============================================

-- Query chính để lấy lịch làm việc HDV (giống hệ thống)
SELECT 
    ga.*,
    t.id as tour_id,
    t.title as tour_name,
    COALESCE(
        NULLIF(
            CASE 
                WHEN t.tour_code IS NULL OR t.tour_code = '' OR TRIM(t.tour_code) = '' OR t.tour_code = t.title 
                THEN NULL
                ELSE t.tour_code 
            END,
            ''
        ),
        CONCAT('TOUR-', LPAD(t.id, 4, '0'))
    ) as tour_code,
    t.description as tour_description,
    d.departure_time,
    d.end_date,
    d.end_time,
    d.meeting_point,
    d.status as departure_status,
    d.total_seats,
    d.seats_booked,
    (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') as booked_guests,
    DATEDIFF(COALESCE(d.end_date, DATE(d.departure_time)), DATE(d.departure_time)) + 1 as duration_days,
    ga.assigned_at,
    ga.assigned_by
FROM guide_assign ga
LEFT JOIN departures d ON ga.departure_id = d.id
LEFT JOIN tours t ON d.tour_id = t.id
WHERE ga.guide_id = 5 
  AND ga.status != 'cancelled'
ORDER BY 
    ga.assigned_at DESC,
    ga.id DESC;

