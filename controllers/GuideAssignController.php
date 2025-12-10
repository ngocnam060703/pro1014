<?php
require_once "models/GuideAssignModel.php";
require_once "models/GuideAssignLogModel.php";
require_once "models/GuideModel.php";
require_once "models/TourModel.php";

class GuideAssignController {

    private $assign;
    private $assignLog;
    private $guide;
    private $tours;

    public function __construct() {
        $this->assign = new GuideAssignModel();
        $this->assignLog = new GuideAssignLogModel();
        $this->guide = new GuideModel();
        $this->tours = new TourModel();
    }

    // Hiển thị danh sách phân công với bộ lọc
    public function index() {
        $filters = [];
        
        // Lấy các tham số filter
        if (!empty($_GET['guide_id'])) {
            $filters['guide_id'] = $_GET['guide_id'];
        }
        if (!empty($_GET['tour_id'])) {
            $filters['tour_id'] = $_GET['tour_id'];
        }
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['date'])) {
            $filters['date'] = $_GET['date'];
        }
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        // Lấy dữ liệu với filter hoặc không
        if (!empty($filters)) {
            $data = $this->assign->getAllWithFilters($filters);
        } else {
            $data = $this->assign->all();
        }
        
        // Lấy danh sách guides và tours cho filter dropdowns
        $guides = $this->guide->all();
        $tours = $this->tours->getAllTours();
        
        include "views/admin/guide_assign_list.php";
    }

    // Form thêm phân công
    public function create() {
        $guides = $this->guide->all();
        $tours = $this->tours->getAllTours();
        include "views/admin/guide_assign_create.php";
    }

    // AJAX: Lấy thông tin tour và departures chưa kết thúc
    public function getTourInfo() {
        header('Content-Type: application/json');
        
        $tourId = $_GET['tour_id'] ?? 0;
        if (!$tourId) {
            echo json_encode(['success' => false, 'message' => 'Tour ID không hợp lệ']);
            exit;
        }
        
        // Lấy thông tin tour
        $tour = $this->tours->getTourById($tourId);
        if (!$tour) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy tour']);
            exit;
        }
        
        // Tính số ngày từ itinerary
        $daysCount = 1; // Mặc định 1 ngày
        try {
            require_once "models/TourItineraryModel.php";
            $itineraryModel = new TourItineraryModel();
            $itineraryDays = $itineraryModel->getByTourId($tourId);
            if (!empty($itineraryDays)) {
                $daysCount = count($itineraryDays);
            }
        } catch (Exception $e) {
            // Nếu không có itinerary, mặc định 1 ngày
            $daysCount = 1;
        }
        
        // Lấy các departures chưa kết thúc
        $sql = "SELECT d.*, 
                       (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') as booked_guests,
                       t.title as tour_name
                FROM departures d
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE d.tour_id = ? 
                AND (d.status != 'completed' AND (d.end_date IS NULL OR d.end_date >= CURDATE()))
                ORDER BY d.departure_time ASC";
        $departures = pdo_query($sql, $tourId);
        
        echo json_encode([
            'success' => true,
            'tour' => [
                'id' => $tour['id'],
                'title' => $tour['title'],
                'description' => $tour['description'] ?? '',
                'days' => $daysCount
            ],
            'departures' => $departures
        ]);
        exit;
    }

    // AJAX: Lấy thông tin departure chi tiết
    public function getDepartureInfo() {
        header('Content-Type: application/json');
        
        $departureId = $_GET['departure_id'] ?? 0;
        if (!$departureId) {
            echo json_encode(['success' => false, 'message' => 'Departure ID không hợp lệ']);
            exit;
        }
        
        $sql = "SELECT d.*, 
                       t.title as tour_name,
                       (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') as booked_guests
                FROM departures d
                LEFT JOIN tours t ON d.tour_id = t.id
                WHERE d.id = ?";
        $departure = pdo_query_one($sql, $departureId);
        
        if (!$departure) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy lịch khởi hành']);
            exit;
        }
        
        // Kiểm tra nếu đã kết thúc
        $isCompleted = false;
        if ($departure['status'] == 'completed') {
            $isCompleted = true;
        } elseif (!empty($departure['end_date'])) {
            $endDate = strtotime($departure['end_date']);
            $today = strtotime(date('Y-m-d'));
            if ($endDate < $today) {
                $isCompleted = true;
            }
        }
        
        echo json_encode([
            'success' => true,
            'departure' => $departure,
            'is_completed' => $isCompleted
        ]);
        exit;
    }

    // AJAX: Lấy thông tin HDV và lịch làm việc
    public function getGuideInfo() {
        header('Content-Type: application/json');
        
        $guideId = $_GET['guide_id'] ?? 0;
        if (!$guideId) {
            echo json_encode(['success' => false, 'message' => 'Guide ID không hợp lệ']);
            exit;
        }
        
        // Lấy thông tin HDV
        $guide = $this->guide->find($guideId);
        if (!$guide) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy HDV']);
            exit;
        }
        
        // Kiểm tra trạng thái
        $isInactive = false;
        $statusMessage = '';
        if (isset($guide['status'])) {
            if ($guide['status'] == 'inactive' || $guide['status'] == 'not_active') {
                $isInactive = true;
                $statusMessage = 'HDV đang ở trạng thái không hoạt động';
            } elseif ($guide['status'] == 'on_leave' || $guide['status'] == 'leave') {
                $isInactive = true;
                $statusMessage = 'HDV đang nghỉ phép';
            }
        }
        
        // Lấy lịch làm việc (các tour đang dẫn)
        $sql = "SELECT ga.*, 
                       t.title as tour_name,
                       d.departure_time,
                       d.end_date,
                       d.end_time,
                       d.status as departure_status,
                       ga.status as assignment_status
                FROM guide_assign ga
                INNER JOIN departures d ON ga.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE ga.guide_id = ? 
                AND ga.status != 'cancelled'
                AND (d.status != 'completed' OR d.end_date >= CURDATE())
                ORDER BY d.departure_time ASC";
        $schedule = pdo_query($sql, $guideId);
        
        echo json_encode([
            'success' => true,
            'guide' => $guide,
            'is_inactive' => $isInactive,
            'status_message' => $statusMessage,
            'schedule' => $schedule
        ]);
        exit;
    }

    // Thêm phân công mới
    public function store() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // BUSINESS RULE: Phải chọn lịch khởi hành khi phân công HDV
        if (empty($_POST["departure_id"])) {
            $_SESSION['error'] = "Vui lòng chọn Lịch khởi hành trước khi phân công HDV!";
            header("Location: index.php?act=guide-assign-create");
            exit();
        }
        
        $guide_id = $_POST["guide_id"];
        $departure_id = $_POST["departure_id"];
        $note = $_POST["note"] ?? '';
        $reason = $_POST["reason"] ?? '';
        $status = $_POST["status"] ?? 'scheduled';
        // Lấy user ID từ session (admin)
        $assigned_by = $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? null;

        // Lấy thông tin departure
        $departure = pdo_query_one("SELECT * FROM departures WHERE id = ?", $departure_id);
        if (!$departure) {
            $_SESSION['error'] = "Không tìm thấy lịch khởi hành!";
            header("Location: index.php?act=guide-assign-create");
            exit();
        }

        // BUSINESS RULE: Không cho phân công vào lịch đã kết thúc
        if ($this->assign->isTourCompleted($departure_id)) {
            $_SESSION['error'] = "Không thể phân công vào lịch đã kết thúc!";
            header("Location: index.php?act=guide-assign-create");
            exit();
        }

        // Lấy thông tin HDV để kiểm tra trạng thái
        $guide = $this->guide->find($guide_id);
        if (!$guide) {
            $_SESSION['error'] = "Không tìm thấy hướng dẫn viên!";
            header("Location: index.php?act=guide-assign-create");
            exit();
        }

        // BUSINESS RULE: HDV không thể được phân công nếu trạng thái là "Không hoạt động" hoặc "Nghỉ phép"
        if (isset($guide['status'])) {
            if ($guide['status'] == 'inactive' || $guide['status'] == 'not_active') {
                $_SESSION['error'] = "Không thể phân công vì HDV đang ở trạng thái không hoạt động!";
                header("Location: index.php?act=guide-assign-create");
                exit();
            }
            if ($guide['status'] == 'on_leave' || $guide['status'] == 'leave') {
                $_SESSION['error'] = "Không thể phân công vì HDV đang nghỉ phép!";
                header("Location: index.php?act=guide-assign-create");
                exit();
            }
        }

        // VALIDATION: Kiểm tra trùng lịch
        $conflict = $this->assign->hasScheduleConflict($guide_id, $departure_id);
        if ($conflict['has_conflict']) {
            $_SESSION['error'] = $conflict['message'];
            header("Location: index.php?act=guide-assign-create");
            exit();
        }

        $data = [
            "guide_id"       => $guide_id,
            "departure_id"   => $departure_id,
            "tour_id"        => $departure["tour_id"],
            "departure_date" => date("Y-m-d", strtotime($departure["departure_time"])),
            "meeting_point"  => $departure["meeting_point"] ?? '',
            "max_people"     => $departure["seats_total"] ?? $departure["total_seats"] ?? 0,
            "note"           => $note,
            "reason"         => $reason,
            "status"         => $status,
            "assigned_at"    => date("Y-m-d H:i:s"),
            "assigned_by"    => $assigned_by
        ];

        $assignmentId = $this->assign->store($data);
        
        // Ghi log tạo mới
        if ($assignmentId) {
            $this->assignLog->addLog([
                'assignment_id' => $assignmentId,
                'new_guide_id' => $guide_id,
                'new_status' => $status,
                'new_note' => $note,
                'change_type' => 'created',
                'changed_by' => $assigned_by,
                'change_reason' => $reason
            ]);
        }
        
        $_SESSION['message'] = "Phân công hướng dẫn viên thành công!";
        header("Location: index.php?act=guide-assign");
        exit();
    }

    // Form chỉnh sửa
    public function edit() {
        $id = $_GET["id"];
        $assign = $this->assign->find($id);
        $guides = $this->guide->all();
        $tours = $this->tours->getAllTours();
        $departures = pdo_query("SELECT * FROM departures");
        include "views/admin/guide_assign_edit.php";
    }

    // Cập nhật phân công
    public function update() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_POST["id"];
        $new_guide_id = $_POST["guide_id"];
        $departure_id = $_POST["departure_id"];
        $note = $_POST["note"] ?? '';
        $reason = $_POST["reason"] ?? '';
        $status = $_POST["status"] ?? 'scheduled';
        $changed_by = $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? null;

        // Lấy thông tin assignment hiện tại
        $oldAssignment = $this->assign->find($id);
        if (!$oldAssignment) {
            $_SESSION['error'] = "Không tìm thấy phân công!";
            header("Location: index.php?act=guide-assign");
            exit();
        }

        // BUSINESS RULE: Không được đổi HDV nếu tour đã kết thúc
        if ($this->assign->isTourCompleted($departure_id)) {
            $_SESSION['error'] = "Không thể đổi HDV vì tour đã kết thúc!";
            header("Location: index.php?act=guide-assign-edit&id=" . $id);
            exit();
        }

        // BUSINESS RULE: Không được đổi HDV nếu phân công đang ở trạng thái đã kết thúc
        if ($this->assign->isAssignmentCompleted($id)) {
            $_SESSION['error'] = "Không thể đổi HDV vì phân công đã kết thúc!";
            header("Location: index.php?act=guide-assign-edit&id=" . $id);
            exit();
        }

        $departure = pdo_query_one("SELECT * FROM departures WHERE id = ?", $departure_id);
        if (!$departure) {
            $_SESSION['error'] = "Không tìm thấy lịch khởi hành!";
            header("Location: index.php?act=guide-assign-edit&id=" . $id);
            exit();
        }

        // VALIDATION: Kiểm tra trùng lịch (loại trừ assignment hiện tại)
        $conflict = $this->assign->hasScheduleConflict($new_guide_id, $departure_id, $id);
        if ($conflict['has_conflict']) {
            $_SESSION['error'] = $conflict['message'];
            header("Location: index.php?act=guide-assign-edit&id=" . $id);
            exit();
        }

        $data = [
            "guide_id"       => $new_guide_id,
            "departure_id"   => $departure_id,
            "tour_id"        => $departure["tour_id"],
            "departure_date" => date("Y-m-d", strtotime($departure["departure_time"])),
            "meeting_point"  => $departure["meeting_point"],
            "max_people"     => $departure["seats_total"] ?? $departure["total_seats"] ?? 0,
            "note"           => $note,
            "reason"         => $reason,
            "status"         => $status
        ];

        // Xác định loại thay đổi để ghi log
        $changeType = null;
        $logData = [
            'assignment_id' => $id,
            'changed_by' => $changed_by,
            'change_reason' => $reason
        ];

        // Kiểm tra thay đổi HDV
        if ($oldAssignment['guide_id'] != $new_guide_id) {
            $changeType = 'guide_changed';
            $logData['old_guide_id'] = $oldAssignment['guide_id'];
            $logData['new_guide_id'] = $new_guide_id;
        }
        // Kiểm tra thay đổi trạng thái
        elseif ($oldAssignment['status'] != $status) {
            $changeType = 'status_changed';
            $logData['old_status'] = $oldAssignment['status'];
            $logData['new_status'] = $status;
        }
        // Kiểm tra thay đổi ghi chú
        elseif ($oldAssignment['note'] != $note) {
            $changeType = 'note_changed';
            $logData['old_note'] = $oldAssignment['note'];
            $logData['new_note'] = $note;
        }

        $this->assign->updateData($id, $data);
        
        // Ghi log nếu có thay đổi
        if ($changeType) {
            $logData['change_type'] = $changeType;
            $this->assignLog->addLog($logData);
        }
        
        $_SESSION['message'] = "Cập nhật phân công hướng dẫn viên thành công!";
        header("Location: index.php?act=guide-assign");
        exit();
    }

    // Xóa phân công
    public function delete() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_GET["id"];
        $changed_by = $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? null;
        
        // Lấy thông tin assignment
        $assignment = $this->assign->find($id);
        if (!$assignment) {
            $_SESSION['error'] = "Không tìm thấy phân công!";
            header("Location: index.php?act=guide-assign");
            exit();
        }

        // BUSINESS RULE: Chỉ xóa nếu tour chưa khởi hành
        if (!$this->assign->isTourNotStarted($assignment['departure_id'])) {
            $_SESSION['error'] = "Không thể xóa phân công vì tour đã khởi hành!";
            header("Location: index.php?act=guide-assign");
            exit();
        }

        // BUSINESS RULE: Không xóa nếu phân công đang chạy
        if ($this->assign->isAssignmentRunning($id)) {
            $_SESSION['error'] = "Không thể xóa phân công đang chạy!";
            header("Location: index.php?act=guide-assign");
            exit();
        }

        // BUSINESS RULE: Không xóa nếu phân công đã kết thúc (chỉ được "đóng")
        if ($this->assign->isAssignmentCompleted($id)) {
            $_SESSION['error'] = "Không thể xóa phân công đã kết thúc! Chỉ có thể đóng phân công.";
            header("Location: index.php?act=guide-assign");
            exit();
        }

        // Ghi log trước khi xóa
        $this->assignLog->addLog([
            'assignment_id' => $id,
            'old_guide_id' => $assignment['guide_id'],
            'old_status' => $assignment['status'],
            'old_note' => $assignment['note'],
            'change_type' => 'deleted',
            'changed_by' => $changed_by,
            'change_reason' => 'Xóa phân công'
        ]);

        $this->assign->delete($id);
        $_SESSION['message'] = "Xóa phân công thành công!";
        header("Location: index.php?act=guide-assign");
        exit();
    }

    // Xem chi tiết phân công
    public function detail() {
        $id = $_GET["id"] ?? 0;
        
        if (!$id) {
            header("Location: index.php?act=guide-assign");
            exit();
        }
        
        $assignment = $this->assign->find($id);
        
        if (!$assignment) {
            $_SESSION['error'] = "Không tìm thấy phân công!";
            header("Location: index.php?act=guide-assign");
            exit();
        }
        
        // Lấy nhật ký thay đổi
        $logs = $this->assignLog->getByAssignmentId($id);
        
        // Lấy các lần thay đổi HDV
        $guideChanges = $this->assignLog->getGuideChanges($id);
        
        include "views/admin/guide_assign_detail.php";
    }

    // Kiểm tra trùng lịch (AJAX)
    public function checkConflict() {
        header('Content-Type: application/json');
        
        $guideId = $_GET['guide_id'] ?? 0;
        $departureId = $_GET['departure_id'] ?? 0;
        $excludeId = $_GET['exclude_id'] ?? null;
        
        if (!$guideId || !$departureId) {
            echo json_encode(['has_conflict' => false]);
            exit();
        }
        
        $conflict = $this->assign->hasScheduleConflict($guideId, $departureId, $excludeId);
        echo json_encode($conflict);
        exit();
    }
}
