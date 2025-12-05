<?php
require_once "models/Database.php";

class GuideModel {
    private $conn;

    public function __construct() {
        $this->conn = pdo_get_connection();
    }

    public function all($filters = []) {
        $sql = "SELECT g.*, 
                (SELECT COUNT(*) FROM guide_tour_history WHERE guide_id = g.id) as total_tours,
                (SELECT AVG(rating) FROM guide_tour_history WHERE guide_id = g.id AND rating IS NOT NULL) as avg_rating
                FROM guides g";
        
        $where = [];
        $params = [];
        
        if (!empty($filters['category'])) {
            $sql .= " INNER JOIN guide_categories gc ON g.id = gc.guide_id";
            $where[] = "gc.category_type = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['status'])) {
            $where[] = "g.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " GROUP BY g.id ORDER BY g.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT * FROM guides WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByAccount($account) {
        $sql = "SELECT * FROM guides WHERE account_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$account]);
        return $stmt->fetch();
    }

    public function store($data) {
        // Lấy danh sách các cột có sẵn
        $columns = ['fullname', 'phone', 'email', 'certificate', 'account_id', 'password'];
        $placeholders = [];
        $values = [];
        
        // Thêm các trường mới nếu có
        $newFields = [
            'date_of_birth', 'photo', 'address', 'languages', 'experience_years',
            'experience_description', 'health_status', 'health_notes', 'specializations',
            'status', 'notes'
        ];
        
        foreach ($newFields as $field) {
            if (isset($data[$field])) {
                $columns[] = $field;
                $placeholders[] = '?';
                $values[] = $data[$field];
            }
        }
        
        // Thêm các trường cơ bản
        $values = array_merge([
            $data['fullname'] ?? '',
            $data['phone'] ?? '',
            $data['email'] ?? '',
            $data['certificate'] ?? null,
            $data['account_id'] ?? '',
            $data['password'] ?? ''
        ], $values);
        
        $sql = "INSERT INTO guides (" . implode(', ', $columns) . ")
                VALUES (" . implode(', ', array_fill(0, count($columns), '?')) . ")";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute($values);
        
        if ($result && isset($data['categories']) && !empty($data['categories'])) {
            $guideId = $this->conn->lastInsertId();
            $this->saveCategories($guideId, $data['categories']);
        }
        
        return $result;
    }

    public function updateData($id, $data) {
        $sql = "UPDATE guides SET 
                fullname = ?, 
                phone = ?, 
                email = ?, 
                certificate = ?";
        
        $values = [
            $data['fullname'] ?? '',
            $data['phone'] ?? '',
            $data['email'] ?? '',
            $data['certificate'] ?? null
        ];
        
        // Thêm các trường mới nếu có
        $newFields = [
            'date_of_birth', 'photo', 'address', 'languages', 'experience_years',
            'experience_description', 'health_status', 'health_notes', 'specializations',
            'status', 'notes'
        ];
        
        foreach ($newFields as $field) {
            if (isset($data[$field])) {
                $sql .= ", $field = ?";
                $values[] = $data[$field];
            }
        }
        
        $sql .= " WHERE id = ?";
        $values[] = $id;
        
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute($values);
        
        if ($result && isset($data['categories']) && !empty($data['categories'])) {
            $this->saveCategories($id, $data['categories']);
        }
        
        return $result;
    }

    public function delete($id) {
        $sql = "DELETE FROM guides WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Lấy danh sách phân loại của HDV
    public function getCategories($guideId) {
        $sql = "SELECT * FROM guide_categories WHERE guide_id = ? ORDER BY is_primary DESC, id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$guideId]);
        return $stmt->fetchAll();
    }

    // Lưu phân loại HDV
    public function saveCategories($guideId, $categories) {
        // Xóa phân loại cũ
        $sql = "DELETE FROM guide_categories WHERE guide_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$guideId]);
        
        // Thêm phân loại mới
        if (!empty($categories)) {
            $sql = "INSERT INTO guide_categories (guide_id, category_type, category_name, is_primary) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            foreach ($categories as $index => $category) {
                $categoryType = is_array($category) ? $category['type'] : $category;
                $categoryName = is_array($category) ? ($category['name'] ?? null) : null;
                $isPrimary = ($index === 0) ? 1 : 0;
                
                $stmt->execute([$guideId, $categoryType, $categoryName, $isPrimary]);
            }
        }
    }

    // Lấy lịch sử dẫn tour
    public function getTourHistory($guideId, $limit = null) {
        $sql = "SELECT gth.*, t.title as tour_title 
                FROM guide_tour_history gth
                LEFT JOIN tours t ON gth.tour_id = t.id
                WHERE gth.guide_id = ?
                ORDER BY gth.departure_date DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$guideId]);
        return $stmt->fetchAll();
    }

    // Thêm lịch sử dẫn tour
    public function addTourHistory($data) {
        $sql = "INSERT INTO guide_tour_history 
                (guide_id, tour_id, departure_id, tour_name, departure_date, end_date, 
                 num_guests, tour_type, rating, feedback, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['guide_id'],
            $data['tour_id'] ?? null,
            $data['departure_id'] ?? null,
            $data['tour_name'] ?? null,
            $data['departure_date'] ?? null,
            $data['end_date'] ?? null,
            $data['num_guests'] ?? 0,
            $data['tour_type'] ?? null,
            $data['rating'] ?? null,
            $data['feedback'] ?? null,
            $data['notes'] ?? null
        ]);
    }

    // Lấy chứng chỉ
    public function getCertificates($guideId) {
        $sql = "SELECT * FROM guide_certificates WHERE guide_id = ? ORDER BY issue_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$guideId]);
        return $stmt->fetchAll();
    }

    // Thêm chứng chỉ
    public function addCertificate($data) {
        $sql = "INSERT INTO guide_certificates 
                (guide_id, certificate_name, certificate_number, issuing_organization, 
                 issue_date, expiry_date, certificate_file, description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['guide_id'],
            $data['certificate_name'],
            $data['certificate_number'] ?? null,
            $data['issuing_organization'] ?? null,
            $data['issue_date'] ?? null,
            $data['expiry_date'] ?? null,
            $data['certificate_file'] ?? null,
            $data['description'] ?? null
        ]);
    }

    // Lấy HDV theo phân loại
    public function getByCategory($categoryType) {
        $sql = "SELECT DISTINCT g.* 
                FROM guides g
                INNER JOIN guide_categories gc ON g.id = gc.guide_id
                WHERE gc.category_type = ? AND g.status = 'active'
                ORDER BY g.fullname";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoryType]);
        return $stmt->fetchAll();
    }
}