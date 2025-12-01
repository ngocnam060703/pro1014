<?php
require_once __DIR__ . "/../commons/function.php";

class GuideAssignModel {

    // Lấy tất cả phân công kèm HDV, tour, ngày khởi hành
    public function all() {
        $sql = "SELECT ga.*, 
                       g.fullname AS guide_name, 
                       t.title AS tour_title, 
                       d.departure_date 
                FROM guide_assign ga
                LEFT JOIN guides g ON ga.guide_id = g.id
                LEFT JOIN departures d ON ga.departure_id = d.id
                LEFT JOIN tours t ON d.tour_id = t.id
                ORDER BY ga.id DESC";
        return pdo_query($sql);
    }

    // Lấy 1 phân công theo id
    public function find($id) {
        $sql = "SELECT * FROM guide_assign WHERE id = ?";
        return pdo_query_one($sql, $id);
    }

    public function store($data) {
    $sql = "INSERT INTO guide_assign 
            (guide_id, departure_id, tour_id, departure_date, note, assigned_at)
            VALUES (?, ?, ?, ?, ?, ?)";

    return pdo_execute(
        $sql,
        $data["guide_id"],
        $data["departure_id"],
        $data["tour_id"],
        $data["departure_date"],  // thêm vào
        $data["note"],
        $data["assigned_at"]
    );
}


    // Cập nhật phân công
    public function updateData($id, $data) {
    $sql = "UPDATE guide_assign SET 
                guide_id = ?, 
                departure_id = ?, 
                tour_id = ?, 
                departure_date = ?, 
                note = ?
            WHERE id = ?";

    return pdo_execute(
        $sql,
        $data["guide_id"],
        $data["departure_id"],
        $data["tour_id"],
        $data["departure_date"],
        $data["note"],
        $id
    );
}


    // Xóa phân công
    public function delete($id) {
        $sql = "DELETE FROM guide_assign WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}