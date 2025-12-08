<?php
require_once "models/GuideAssignModel.php";
require_once "models/TourModel.php";
require_once "models/ScheduleModel.php";
require_once "models/BookingModel.php";
require_once "models/GuideJournalModel.php";
require_once "models/GuideModel.php";

class GuideScheduleController {
    
    private $assignModel;
    private $tourModel;
    private $scheduleModel;
    private $bookingModel;
    private $journalModel;
    private $guideModel;
    
    public function __construct() {
        $this->assignModel = new GuideAssignModel();
        $this->tourModel = new TourModel();
        $this->scheduleModel = new ScheduleModel();
        $this->bookingModel = new BookingModel();
        $this->journalModel = new GuideJournalModel();
        $this->guideModel = new GuideModel();
    }
    
    // Danh sách lịch làm việc với filter
    public function scheduleList() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $guideId = $_SESSION['guide']['id'] ?? 0;
        if (!$guideId) {
            header("Location: index.php?act=hdv_login");
            exit;
        }
        
        // Lấy filters
        $filters = [];
        if (!empty($_GET['month'])) {
            $filters['month'] = $_GET['month'];
        }
        if (!empty($_GET['date'])) {
            $filters['date'] = $_GET['date'];
        }
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        // Lấy danh sách lịch trình từ GuideAssignModel (đồng bộ với admin)
        $schedules = $this->assignModel->getByGuideWithFilters($guideId, $filters);
        
        include "views/hdv/schedule_list.php";
    }
    
    // Chi tiết lịch khởi hành
    public function scheduleDetail() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $guideId = $_SESSION['guide']['id'] ?? 0;
        $departureId = $_GET['departure_id'] ?? 0;
        
        if (!$guideId || !$departureId) {
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Lấy thông tin assignment
        $assignment = $this->getAssignmentDetail($guideId, $departureId);
        
        if (!$assignment) {
            $_SESSION['error'] = "Không tìm thấy lịch trình!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Lấy thông tin tour đầy đủ
        $tour = $this->tourModel->getTourById($assignment['tour_id']);
        
        // Lấy lịch trình chi tiết từng ngày
        require_once "models/TourItineraryModel.php";
        $itineraryModel = new TourItineraryModel();
        $itineraryDays = $itineraryModel->getByTourId($assignment['tour_id']);
        
        // Lấy danh sách khách (chỉ tên + năm sinh)
        $customers = $this->getCustomersForGuide($departureId);
        
        // Lấy phương tiện di chuyển
        $transport = $this->getTransportInfo($departureId);
        
        // Lấy danh sách phòng khách sạn
        $hotels = $this->getHotelInfo($departureId);
        
        // Lấy thông tin điều hành viên
        $coordinators = $this->getCoordinators($departureId);
        
        // Kiểm tra đã check-in chưa
        $checkInStatus = $this->getCheckInStatus($guideId, $departureId);
        
        include "views/hdv/schedule_detail.php";
    }
    
    // Check-in / Nhận tour
    public function checkIn() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $guideId = $_SESSION['guide']['id'] ?? 0;
        $departureId = $_POST['departure_id'] ?? $_GET['departure_id'] ?? 0;
        
        if (!$guideId || !$departureId) {
            $_SESSION['error'] = "Thông tin không hợp lệ!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Kiểm tra đã check-in chưa
        $existing = pdo_query_one(
            "SELECT * FROM guide_checkin WHERE guide_id = ? AND departure_id = ?",
            $guideId, $departureId
        );
        
        if ($existing) {
            $_SESSION['error'] = "Bạn đã check-in tour này rồi!";
            header("Location: index.php?act=hdv_schedule_detail&departure_id=" . $departureId);
            exit;
        }
        
        // Tạo bản ghi check-in
        // Sử dụng DEFAULT cho checked_in_at (timestamp với DEFAULT CURRENT_TIMESTAMP)
        $sql = "INSERT INTO guide_checkin (guide_id, departure_id) VALUES (?, ?)";
        pdo_execute($sql, $guideId, $departureId);
        
        // Lấy assignment ID
        $assignment = $this->getAssignmentDetail($guideId, $departureId);
        if ($assignment) {
            // Cập nhật trạng thái assignment
            $this->assignModel->updateData(
                $assignment['id'],
                ['status' => 'in_progress']
            );
        }
        
        $_SESSION['message'] = "Đã check-in thành công!";
        header("Location: index.php?act=hdv_schedule_detail&departure_id=" . $departureId);
        exit;
    }
    
    // Cập nhật tình trạng tour
    public function updateStatus() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $guideId = $_SESSION['guide']['id'] ?? 0;
        $departureId = $_POST['departure_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        $note = $_POST['note'] ?? '';
        
        if (!$guideId || !$departureId || !$status) {
            $_SESSION['error'] = "Thông tin không đầy đủ!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Lấy assignment
        $assignment = $this->getAssignmentDetail($guideId, $departureId);
        if (!$assignment) {
            $_SESSION['error'] = "Không tìm thấy phân công!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Cập nhật trạng thái
        $this->assignModel->updateData($assignment['id'], [
            'status' => $status,
            'note' => $note
        ]);
        
        $_SESSION['message'] = "Đã cập nhật trạng thái tour!";
        header("Location: index.php?act=hdv_schedule_detail&departure_id=" . $departureId);
        exit;
    }
    
    // Helper methods - Sử dụng cùng nguồn dữ liệu với admin để đảm bảo đồng bộ
    private function getAssignmentDetail($guideId, $departureId) {
        // Sử dụng method từ GuideAssignModel để đảm bảo đồng bộ với admin
        $sql = "SELECT ga.*, 
                       t.id as tour_id,
                       t.title as tour_name,
                       t.tour_code,
                       t.description as tour_description,
                       d.departure_time,
                       d.end_date,
                       d.end_time,
                       d.meeting_point,
                       d.status as departure_status,
                       d.total_seats,
                       d.seats_booked,
                       (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.id AND b.status != 'cancelled') as booked_guests,
                       DATEDIFF(COALESCE(d.end_date, DATE(d.departure_time)), DATE(d.departure_time)) + 1 as duration_days
                FROM guide_assign ga
                INNER JOIN departures d ON ga.departure_id = d.id
                INNER JOIN tours t ON d.tour_id = t.id
                WHERE ga.guide_id = ? AND ga.departure_id = ?";
        return pdo_query_one($sql, $guideId, $departureId);
    }
    
    private function getCustomersForGuide($departureId) {
        $sql = "SELECT 
                    b.customer_name as full_name,
                    b.num_adults + b.num_children + b.num_infants as total_guests,
                    b.num_adults,
                    b.num_children,
                    b.num_infants
                FROM bookings b
                WHERE b.departure_id = ? AND b.status != 'cancelled'
                ORDER BY b.created_at ASC";
        return pdo_query($sql, $departureId);
    }
    
    private function getTransportInfo($departureId) {
        $sql = "SELECT 
                    sa.service_name,
                    sa.service_type,
                    td.vehicle_type,
                    td.vehicle_number,
                    td.driver_name,
                    td.driver_phone
                FROM departure_service_allocations sa
                LEFT JOIN departure_transport_details td ON sa.id = td.service_allocation_id
                WHERE sa.departure_id = ? AND sa.service_type = 'transport'";
        return pdo_query($sql, $departureId);
    }
    
    private function getHotelInfo($departureId) {
        $sql = "SELECT 
                    sa.service_name,
                    hd.hotel_name,
                    hd.room_type,
                    hd.room_number,
                    hd.number_of_rooms,
                    hd.check_in_date,
                    hd.check_out_date,
                    hd.check_in_time,
                    hd.check_out_time,
                    hd.number_of_nights,
                    hd.amenities,
                    hd.notes
                FROM departure_service_allocations sa
                LEFT JOIN departure_hotel_details hd ON sa.id = hd.service_allocation_id
                WHERE sa.departure_id = ? AND sa.service_type = 'hotel'";
        return pdo_query($sql, $departureId);
    }
    
    private function getCoordinators($departureId) {
        $sql = "SELECT 
                    sa.staff_name,
                    sa.staff_phone,
                    COALESCE(u.email, '') as staff_email,
                    sa.notes,
                    sa.role
                FROM departure_staff_assignments sa
                LEFT JOIN users u ON sa.staff_id = u.id
                WHERE sa.departure_id = ? AND sa.staff_type = 'coordinator'";
        return pdo_query($sql, $departureId);
    }
    
    private function getCheckInStatus($guideId, $departureId) {
        return pdo_query_one(
            "SELECT * FROM guide_checkin WHERE guide_id = ? AND departure_id = ?",
            $guideId, $departureId
        );
    }
    
    // ============================================
    // NHẬT KÝ (JOURNAL)
    // ============================================
    
    public function journalCreate() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $guideId = $_SESSION['guide']['id'] ?? 0;
        $departureId = $_GET['departure_id'] ?? 0;
        
        if (!$guideId || !$departureId) {
            $_SESSION['error'] = "Thông tin không hợp lệ!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Kiểm tra quyền: chỉ HDV được phân công mới được tạo nhật ký
        $assignment = $this->getAssignmentDetail($guideId, $departureId);
        if (!$assignment) {
            $_SESSION['error'] = "Bạn không được phân công cho tour này!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Lấy thông tin departure để validate ngày
        $departure = $this->scheduleModel->getById($departureId);
        if (!$departure) {
            $_SESSION['error'] = "Không tìm thấy lịch khởi hành!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Kiểm tra tour đã kết thúc chưa
        if ($departure['status'] == 'completed') {
            $_SESSION['error'] = "Tour đã kết thúc, không thể thêm nhật ký mới!";
            header("Location: index.php?act=hdv_schedule_detail&departure_id=" . $departureId);
            exit;
        }
        
        // Kiểm tra tour tạm hoãn
        if ($assignment['status'] == 'paused') {
            $_SESSION['error'] = "Tour đang tạm hoãn, không thể thêm nhật ký!";
            header("Location: index.php?act=hdv_schedule_detail&departure_id=" . $departureId);
            exit;
        }
        
        // Lấy thông tin tour
        $tour = $this->tourModel->getTourById($departure['tour_id']);
        
        include "views/hdv/journal_create.php";
    }
    
    public function journalStore() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        $guideId = $_SESSION['guide']['id'] ?? 0;
        $departureId = $_POST['departure_id'] ?? 0;
        $journalDate = $_POST['journal_date'] ?? date('Y-m-d');
        
        if (!$guideId || !$departureId) {
            $_SESSION['error'] = "Thông tin không hợp lệ!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Kiểm tra quyền
        $assignment = $this->getAssignmentDetail($guideId, $departureId);
        if (!$assignment) {
            $_SESSION['error'] = "Bạn không được phân công cho tour này!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Lấy thông tin departure để validate
        $departure = $this->scheduleModel->getById($departureId);
        if (!$departure) {
            $_SESSION['error'] = "Không tìm thấy lịch khởi hành!";
            header("Location: index.php?act=hdv_schedule_list");
            exit;
        }
        
        // Validation: Ngày không được tương lai
        $today = date('Y-m-d');
        if ($journalDate > $today) {
            $_SESSION['error'] = "Ngày ghi nhật ký không hợp lệ. Không được chọn ngày tương lai.";
            header("Location: index.php?act=hdv_journal_create&departure_id=" . $departureId);
            exit;
        }
        
        // Validation: Ngày phải >= ngày khởi hành và <= ngày kết thúc
        $departureDate = date('Y-m-d', strtotime($departure['departure_time']));
        $endDate = !empty($departure['end_date']) ? $departure['end_date'] : $departureDate;
        
        if ($journalDate < $departureDate || $journalDate > $endDate) {
            $_SESSION['error'] = "Ngày ghi nhật ký không hợp lệ. Phải trong khoảng từ ngày khởi hành đến ngày kết thúc.";
            header("Location: index.php?act=hdv_journal_create&departure_id=" . $departureId);
            exit;
        }
        
        // Validation: Một ngày chỉ tạo 1 nhật ký
        if ($this->journalModel->existsForDate($guideId, $departureId, $journalDate)) {
            $_SESSION['error'] = "Đã có nhật ký cho ngày này. Một ngày chỉ được tạo 1 nhật ký.";
            header("Location: index.php?act=hdv_journal_create&departure_id=" . $departureId);
            exit;
        }
        
        // Validation: Nội dung nhật ký không được để trống
        $activities = trim($_POST['activities'] ?? '');
        $completedAttractions = trim($_POST['completed_attractions'] ?? '');
        $travelTime = trim($_POST['travel_time'] ?? '');
        $customerStatus = trim($_POST['customer_status'] ?? '');
        $importantNotes = trim($_POST['important_notes'] ?? '');
        
        if (empty($activities) && empty($completedAttractions) && empty($travelTime) && 
            empty($customerStatus) && empty($importantNotes)) {
            $_SESSION['error'] = "Vui lòng nhập nội dung nhật ký.";
            header("Location: index.php?act=hdv_journal_create&departure_id=" . $departureId);
            exit;
        }
        
        // Lưu nhật ký
        $journalData = [
            'journal_date' => $journalDate,
            'guide_id' => $guideId,
            'departure_id' => $departureId,
            'activities' => $activities,
            'completed_attractions' => $completedAttractions,
            'travel_time' => $travelTime,
            'customer_status' => $customerStatus,
            'important_notes' => $importantNotes,
            'note' => trim($_POST['note'] ?? '')
        ];
        
        $this->journalModel->store($journalData);
        $journalId = $this->journalModel->getLastInsertId();
        
        // Xử lý sự cố nếu có
        $incidentId = null;
        if (!empty($_POST['has_incident']) && $_POST['has_incident'] == '1') {
            require_once "models/GuideJournalIncidentModel.php";
            require_once "models/GuideIncidentModel.php";
            $incidentModel = new GuideJournalIncidentModel();
            $guideIncidentModel = new GuideIncidentModel();
            
            $incidentDescription = trim($_POST['incident_description'] ?? '');
            $incidentSeverity = $_POST['incident_severity'] ?? 'low';
            $incidentSolution = trim($_POST['incident_solution'] ?? '');
            $affectedCustomers = trim($_POST['affected_customers'] ?? '');
            
            if (!empty($incidentDescription)) {
                // Lưu vào guide_journal_incidents (liên kết với nhật ký)
                $incidentData = [
                    'journal_id' => $journalId,
                    'incident_time' => !empty($_POST['incident_time']) ? $_POST['incident_time'] : null,
                    'description' => $incidentDescription,
                    'affected_customers' => $affectedCustomers,
                    'solution' => $incidentSolution,
                    'severity' => $incidentSeverity
                ];
                
                $incidentModel->store($incidentData);
                $incidentId = $incidentModel->getLastInsertId();
            }
        }
        
        // Xử lý upload ảnh
        if (!empty($_FILES['photos']['name'][0])) {
            require_once "models/GuideJournalPhotoModel.php";
            $photoModel = new GuideJournalPhotoModel();
            
            $uploadDir = "uploads/journals/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $maxFiles = 10;
            $maxSize = 5 * 1024 * 1024; // 5MB
            $uploadedCount = 0;
            
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
                if ($uploadedCount >= $maxFiles) break;
                
                if (!empty($tmpName) && $_FILES['photos']['error'][$key] == UPLOAD_ERR_OK) {
                    $fileSize = $_FILES['photos']['size'][$key];
                    if ($fileSize > $maxSize) {
                        continue; // Bỏ qua file quá lớn
                    }
                    
                    $fileName = $_FILES['photos']['name'][$key];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'];
                    
                    if (!in_array($fileExt, $allowedExts)) {
                        continue; // Bỏ qua file không hợp lệ
                    }
                    
                    $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
                    $filePath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($tmpName, $filePath)) {
                        $photoData = [
                            'journal_id' => $journalId,
                            'incident_id' => $incidentId,
                            'file_path' => $filePath,
                            'file_name' => $fileName,
                            'file_size' => $fileSize,
                            'file_type' => strpos($_FILES['photos']['type'][$key], 'video') !== false ? 'video' : 'image'
                        ];
                        $photoModel->store($photoData);
                        $uploadedCount++;
                    }
                }
            }
        }
        
        // Sau khi upload ảnh xong, tạo bản ghi trong guide_incidents nếu có sự cố
        if ($incidentId) {
            require_once "models/GuideIncidentModel.php";
            $guideIncidentModel = new GuideIncidentModel();
            
            // Lấy ảnh từ journal photos nếu có
            $incidentPhotos = null;
            if (!empty($incidentId)) {
                require_once "models/GuideJournalPhotoModel.php";
                $photoModel = new GuideJournalPhotoModel();
                $photos = $photoModel->getByIncidentId($incidentId);
                if (!empty($photos)) {
                    $photoPaths = array_column($photos, 'file_path');
                    $incidentPhotos = implode(',', $photoPaths);
                }
            }
            
            // Tạo bản ghi trong guide_incidents để admin có thể xem
            $incidentDescription = trim($_POST['incident_description'] ?? '');
            $incidentSeverity = $_POST['incident_severity'] ?? 'low';
            $incidentSolution = trim($_POST['incident_solution'] ?? '');
            $affectedCustomers = trim($_POST['affected_customers'] ?? '');
            
            $guideIncidentData = [
                'departure_id' => $departureId,
                'guide_id' => $guideId,
                'incident_type' => 'journal_incident', // Đánh dấu là sự cố từ nhật ký
                'severity' => $incidentSeverity,
                'description' => $incidentDescription . 
                    (!empty($affectedCustomers) ? "\n\nKhách bị ảnh hưởng: " . $affectedCustomers : ''),
                'solution' => $incidentSolution,
                'photos' => $incidentPhotos
            ];
            
            $guideIncidentModel->store($guideIncidentData);
        }
        
        $_SESSION['success'] = "Gửi nhật ký thành công! Đang chờ duyệt.";
        header("Location: index.php?act=hdv_schedule_detail&departure_id=" . $departureId);
        exit;
    }
}

