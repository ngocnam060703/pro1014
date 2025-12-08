<?php
require_once __DIR__ . "/../commons/function.php";

class GuideJournalModel {

    public function all() {
        $sql = "SELECT gj.*, g.fullname AS guide_name, 
                       CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_journal gj
                LEFT JOIN guides g ON gj.guide_id = g.id
                LEFT JOIN departures d ON gj.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                ORDER BY gj.id DESC";

        return pdo_query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM guide_journal WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function store($data) {
        $sql = "INSERT INTO guide_journal 
                (journal_date, guide_id, departure_id, activities, completed_attractions, 
                 travel_time, customer_status, important_notes, status, note)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)";

        return pdo_execute($sql,
            $data['journal_date'],
            $data['guide_id'],
            $data['departure_id'],
            $data['activities'] ?? null,
            $data['completed_attractions'] ?? null,
            $data['travel_time'] ?? null,
            $data['customer_status'] ?? null,
            $data['important_notes'] ?? null,
            $data['note'] ?? null
        );
    }
    
    public function getLastInsertId() {
        return pdo_last_insert_id();
    }
    
    // Kiểm tra đã có nhật ký cho ngày này chưa
    public function existsForDate($guideId, $departureId, $journalDate) {
        $sql = "SELECT id FROM guide_journal 
                WHERE guide_id = ? AND departure_id = ? AND journal_date = ?";
        return pdo_query_one($sql, $guideId, $departureId, $journalDate);
    }
    
    // Lấy nhật ký với thông tin đầy đủ
    public function findWithDetails($id) {
        $sql = "SELECT gj.*, 
                       g.fullname AS guide_name,
                       t.title AS tour_name,
                       d.departure_time,
                       d.end_date,
                       d.end_time,
                       u.full_name AS approved_by_name
                FROM guide_journal gj
                LEFT JOIN guides g ON gj.guide_id = g.id
                LEFT JOIN departures d ON gj.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                LEFT JOIN users u ON gj.approved_by = u.id
                WHERE gj.id = ?";
        return pdo_query_one($sql, $id);
    }
    
    // Lấy danh sách nhật ký theo departure với filter
    public function getByDeparture($departureId, $filters = []) {
        $sql = "SELECT gj.*, g.fullname AS guide_name
                FROM guide_journal gj
                LEFT JOIN guides g ON gj.guide_id = g.id
                WHERE gj.departure_id = ?";
        
        $params = [$departureId];
        
        if (!empty($filters['status'])) {
            $sql .= " AND gj.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['date'])) {
            $sql .= " AND gj.journal_date = ?";
            $params[] = $filters['date'];
        }
        
        $sql .= " ORDER BY gj.journal_date DESC, gj.created_at DESC";
        
        return pdo_query($sql, ...$params);
    }
    
    // Duyệt nhật ký
    public function approve($id, $approvedBy) {
        $sql = "UPDATE guide_journal 
                SET status = 'approved', approved_by = ?, approved_at = NOW()
                WHERE id = ?";
        return pdo_execute($sql, $approvedBy, $id);
    }
    
    // Kiểm tra có thể sửa/xóa không (chỉ khi chưa duyệt)
    public function canEdit($id) {
        $journal = $this->find($id);
        return $journal && $journal['status'] == 'pending';
    }

    public function updateData($id, $data) {
    $sql = "UPDATE guide_journal
            SET 
                day_number = ?, 
                guide_id = ?, 
                departure_id = ?, 
                note = ?, 
                activities = ?, 
                updated_at = NOW()
            WHERE id = ?";

    return pdo_execute($sql,
        $data['day_number'] ?? null,
        $data['guide_id'],
        $data['departure_id'],
        $data['note'],
        $data['activities'] ?? null,
        $id
    );
}



    public function delete($id) {
        $sql = "DELETE FROM guide_journal WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    // Lấy nhật ký theo guide_id
    public function getByGuide($guide_id) {
        $sql = "SELECT 
                    gj.*, 
                    t.title AS tour_name,
                    d.departure_time,
                    CONCAT(t.title, ' - ', d.departure_time) AS departure_name
                FROM guide_journal gj
                LEFT JOIN departures d ON gj.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE gj.guide_id = ?
                ORDER BY gj.id DESC";
        return pdo_query($sql, $guide_id);
    }
}
