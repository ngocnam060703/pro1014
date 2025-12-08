<?php
require_once "models/TourModel.php";
require_once "models/LichModel.php";

class TourController {

    private $tourModel;
    private $lichModel;

    public function __construct() {
        $this->tourModel = new TourModel();
        $this->lichModel = new LichModel();
    }

    // Tour
    public function index() {
        $listTour = $this->tourModel->getAllTours();
        include "views/admin/tour_list.php";
    }

    public function create() {
        include "views/admin/tour_create.php";
    }

    public function store() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = $_POST;
        $title = trim($data['title'] ?? '');
        $tourCode = trim($data['tour_code'] ?? '');
        
        // Kiểm tra tên tour không được để trống
        if (empty($title)) {
            $_SESSION['error'] = "Tên tour (địa điểm) không được để trống!";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        // Kiểm tra tên tour đã tồn tại chưa (không phân biệt hoa thường)
        if ($this->tourModel->tourExistsByTitle($title)) {
            $_SESSION['error'] = "Tên địa điểm '" . htmlspecialchars($title) . "' đã tồn tại! Mỗi địa điểm chỉ được có 1 tour. Vui lòng chọn tên khác.";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        // Kiểm tra mã tour đã tồn tại chưa
        if (!empty($tourCode) && $this->tourModel->tourCodeExists($tourCode)) {
            $_SESSION['error'] = "Mã tour đã tồn tại! Vui lòng chọn mã khác.";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        // Kiểm tra tên tour chỉ là địa điểm (không có "ngày", "đêm", số ngày/đêm)
        $titleLower = mb_strtolower($title, 'UTF-8');
        $forbiddenPatterns = [
            '/\d+\s*ngày/i',
            '/\d+\s*đêm/i',
            '/ngày\s*\d+/i',
            '/đêm\s*\d+/i',
            '/\d+\s*ngày\s*\d+\s*đêm/i',
            '/\d+\s*đêm\s*\d+\s*ngày/i',
            '/ngày\s*đêm/i',
            '/đêm\s*ngày/i'
        ];
        
        foreach ($forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $titleLower)) {
                $_SESSION['error'] = "Tên tour chỉ nên là địa điểm, không ghi 'ngày', 'đêm' hay số ngày/đêm. Ví dụ: Hà Nội, Đà Nẵng, Phú Quốc...";
                header("Location: index.php?act=tour-create");
                exit();
            }
        }
        
        // Kiểm tra số chỗ (7-50 chỗ)
        $slots = (int)($data['slots'] ?? 0);
        if ($slots < 7 || $slots > 50) {
            $_SESSION['error'] = "Số chỗ phải từ 7 đến 50 chỗ (phù hợp với các loại xe khách hiện nay).";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        // VALIDATION: Kiểm tra các trường giá
        $adultPrice = floatval($data['adult_price'] ?? 0);
        $childPrice = floatval($data['child_price'] ?? 0);
        $infantPrice = floatval($data['infant_price'] ?? 0);
        $surcharge = floatval($data['surcharge'] ?? 0);
        
        // Kiểm tra giá không được để trống
        if (empty($data['adult_price']) || $adultPrice <= 0) {
            $_SESSION['error'] = "Giá người lớn không được để trống và phải lớn hơn 0.";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        if (empty($data['child_price']) || $childPrice < 0) {
            $_SESSION['error'] = "Giá trẻ em không được để trống và phải >= 0.";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        if (empty($data['infant_price']) || $infantPrice < 0) {
            $_SESSION['error'] = "Giá trẻ nhỏ không được để trống và phải >= 0.";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        if ($surcharge < 0) {
            $_SESSION['error'] = "Phụ phí không được nhỏ hơn 0.";
            header("Location: index.php?act=tour-create");
            exit();
        }
        
        $tourId = $this->tourModel->insertTour($data);
        
        // Xử lý lịch trình chi tiết theo ngày
        if (isset($data['itinerary_days']) && is_array($data['itinerary_days'])) {
            require_once "models/TourItineraryModel.php";
            $itineraryModel = new TourItineraryModel();
            
            // Validate: Phải có tối thiểu 1 ngày
            if (empty($data['itinerary_days'])) {
                $_SESSION['error'] = "Phải có tối thiểu 1 ngày lịch trình.";
                header("Location: index.php?act=tour-create");
                exit();
            }
            
            // Validate: Các ngày phải theo đúng thứ tự và không được để trống
            $dayNumbers = [];
            foreach ($data['itinerary_days'] as $day) {
                $dayNumber = (int)($day['day_number'] ?? 0);
                if ($dayNumber <= 0) {
                    $_SESSION['error'] = "Số ngày không hợp lệ.";
                    header("Location: index.php?act=tour-create");
                    exit();
                }
                
                // Kiểm tra các trường bắt buộc
                if (empty(trim($day['schedule'] ?? '')) || 
                    empty(trim($day['destinations'] ?? '')) || 
                    empty(trim($day['attractions'] ?? ''))) {
                    $_SESSION['error'] = "Các trường: Lịch trình, Đi đâu, Các điểm tham quan không được để trống.";
                    header("Location: index.php?act=tour-create");
                    exit();
                }
                
                $dayNumbers[] = $dayNumber;
            }
            
            // Kiểm tra thứ tự ngày
            sort($dayNumbers);
            for ($i = 0; $i < count($dayNumbers); $i++) {
                if ($dayNumbers[$i] != ($i + 1)) {
                    $_SESSION['error'] = "Các ngày phải theo đúng thứ tự (Ngày 1, Ngày 2, ...).";
                    header("Location: index.php?act=tour-create");
                    exit();
                }
            }
            
            // Lưu từng ngày
            foreach ($data['itinerary_days'] as $day) {
                $day['tour_id'] = $tourId;
                $itineraryModel->insert($day);
            }
        }
        
        $_SESSION['message'] = "Tour đã được thêm thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    public function edit() {
        $id = $_GET["id"] ?? null;
        if (!$id) header("Location: index.php?act=tour");
        $tour = $this->tourModel->getTourById($id);
        include "views/admin/tour_edit.php";
    }

    public function detail() {
        $id = $_GET["id"] ?? null;
        if (!$id) {
            header("Location: index.php?act=tour");
            exit();
        }
        
        $tour = $this->tourModel->getTourById($id);
        if (!$tour) {
            header("Location: index.php?act=tour");
            exit();
        }
        
        // Lấy lịch trình chi tiết theo ngày
        require_once "models/TourItineraryModel.php";
        $itineraryModel = new TourItineraryModel();
        $itineraryDays = $itineraryModel->getByTourId($id);
        
        include "views/admin/tour_detail.php";
    }

    public function update() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_POST["id"] ?? null;
        if (!$id) {
            header("Location: index.php?act=tour");
            exit();
        }
        
        $data = $_POST;
        $title = trim($data['title'] ?? '');
        $tourCode = trim($data['tour_code'] ?? '');
        
        // Kiểm tra tên tour không được để trống
        if (empty($title)) {
            $_SESSION['error'] = "Tên tour (địa điểm) không được để trống!";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        // Kiểm tra tên tour đã tồn tại chưa (loại trừ tour hiện tại, không phân biệt hoa thường)
        if ($this->tourModel->tourExistsByTitle($title, $id)) {
            $_SESSION['error'] = "Tên địa điểm '" . htmlspecialchars($title) . "' đã tồn tại! Mỗi địa điểm chỉ được có 1 tour. Vui lòng chọn tên khác.";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        // Kiểm tra mã tour đã tồn tại chưa (loại trừ tour hiện tại)
        if (!empty($tourCode) && $this->tourModel->tourCodeExists($tourCode, $id)) {
            $_SESSION['error'] = "Mã tour đã tồn tại! Vui lòng chọn mã khác.";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        // Kiểm tra tên tour chỉ là địa điểm (không có "ngày", "đêm", số ngày/đêm)
        $titleLower = mb_strtolower($title, 'UTF-8');
        $forbiddenPatterns = [
            '/\d+\s*ngày/i',
            '/\d+\s*đêm/i',
            '/ngày\s*\d+/i',
            '/đêm\s*\d+/i',
            '/\d+\s*ngày\s*\d+\s*đêm/i',
            '/\d+\s*đêm\s*\d+\s*ngày/i',
            '/ngày\s*đêm/i',
            '/đêm\s*ngày/i'
        ];
        
        foreach ($forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $titleLower)) {
                $_SESSION['error'] = "Tên tour chỉ nên là địa điểm, không ghi 'ngày', 'đêm' hay số ngày/đêm. Ví dụ: Hà Nội, Đà Nẵng, Phú Quốc...";
                header("Location: index.php?act=tour-edit&id=" . $id);
                exit();
            }
        }
        
        // Kiểm tra số chỗ (7-50 chỗ)
        $slots = (int)($data['slots'] ?? 0);
        if ($slots < 7 || $slots > 50) {
            $_SESSION['error'] = "Số chỗ phải từ 7 đến 50 chỗ (phù hợp với các loại xe khách hiện nay).";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        // VALIDATION: Kiểm tra tour có đang chạy không
        require_once "models/ScheduleModel.php";
        $scheduleModel = new ScheduleModel();
        $activeDepartures = $scheduleModel->getActiveDeparturesByTourId($id);
        
        if (!empty($activeDepartures)) {
            // Kiểm tra xem có thay đổi giá không
            $tour = $this->tourModel->getTourById($id);
            $oldAdultPrice = floatval($tour['adult_price'] ?? 0);
            $oldChildPrice = floatval($tour['child_price'] ?? 0);
            $oldInfantPrice = floatval($tour['infant_price'] ?? 0);
            $oldSurcharge = floatval($tour['surcharge'] ?? 0);
            
            $newAdultPrice = floatval($data['adult_price'] ?? 0);
            $newChildPrice = floatval($data['child_price'] ?? 0);
            $newInfantPrice = floatval($data['infant_price'] ?? 0);
            $newSurcharge = floatval($data['surcharge'] ?? 0);
            
            if ($oldAdultPrice != $newAdultPrice || 
                $oldChildPrice != $newChildPrice || 
                $oldInfantPrice != $newInfantPrice || 
                $oldSurcharge != $newSurcharge) {
                $_SESSION['error'] = "Không thể sửa giá khi tour đang chạy (có lịch khởi hành đang diễn ra). Vui lòng đợi tour kết thúc.";
                header("Location: index.php?act=tour-edit&id=" . $id);
                exit();
            }
        }
        
        // VALIDATION: Kiểm tra các trường giá
        $adultPrice = floatval($data['adult_price'] ?? 0);
        $childPrice = floatval($data['child_price'] ?? 0);
        $infantPrice = floatval($data['infant_price'] ?? 0);
        $surcharge = floatval($data['surcharge'] ?? 0);
        
        // Kiểm tra giá không được để trống
        if (empty($data['adult_price']) || $adultPrice <= 0) {
            $_SESSION['error'] = "Giá người lớn không được để trống và phải lớn hơn 0.";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        if (empty($data['child_price']) || $childPrice < 0) {
            $_SESSION['error'] = "Giá trẻ em không được để trống và phải >= 0.";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        if (empty($data['infant_price']) || $infantPrice < 0) {
            $_SESSION['error'] = "Giá trẻ nhỏ không được để trống và phải >= 0.";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        if ($surcharge < 0) {
            $_SESSION['error'] = "Phụ phí không được nhỏ hơn 0.";
            header("Location: index.php?act=tour-edit&id=" . $id);
            exit();
        }
        
        $this->tourModel->updateTour($id, $data);
        
        // Xử lý lịch trình chi tiết theo ngày
        if (isset($data['itinerary_days']) && is_array($data['itinerary_days'])) {
            require_once "models/TourItineraryModel.php";
            $itineraryModel = new TourItineraryModel();
            
            // Validate: Phải có tối thiểu 1 ngày
            if (empty($data['itinerary_days'])) {
                $_SESSION['error'] = "Phải có tối thiểu 1 ngày lịch trình.";
                header("Location: index.php?act=tour-edit&id=" . $id);
                exit();
            }
            
            // Validate: Các ngày phải theo đúng thứ tự và không được để trống
            $dayNumbers = [];
            foreach ($data['itinerary_days'] as $day) {
                $dayNumber = (int)($day['day_number'] ?? 0);
                if ($dayNumber <= 0) {
                    $_SESSION['error'] = "Số ngày không hợp lệ.";
                    header("Location: index.php?act=tour-edit&id=" . $id);
                    exit();
                }
                
                // Kiểm tra các trường bắt buộc
                if (empty(trim($day['schedule'] ?? '')) || 
                    empty(trim($day['destinations'] ?? '')) || 
                    empty(trim($day['attractions'] ?? ''))) {
                    $_SESSION['error'] = "Các trường: Lịch trình, Đi đâu, Các điểm tham quan không được để trống.";
                    header("Location: index.php?act=tour-edit&id=" . $id);
                    exit();
                }
                
                $dayNumbers[] = $dayNumber;
            }
            
            // Kiểm tra thứ tự ngày
            sort($dayNumbers);
            for ($i = 0; $i < count($dayNumbers); $i++) {
                if ($dayNumbers[$i] != ($i + 1)) {
                    $_SESSION['error'] = "Các ngày phải theo đúng thứ tự (Ngày 1, Ngày 2, ...).";
                    header("Location: index.php?act=tour-edit&id=" . $id);
                    exit();
                }
            }
            
            // Lấy danh sách ngày hiện có
            $existingDays = $itineraryModel->getByTourId($id);
            $existingDayIds = array_column($existingDays, 'id');
            
            // Xử lý từng ngày
            $processedDayIds = [];
            foreach ($data['itinerary_days'] as $day) {
                $day['tour_id'] = $id;
                
                if (isset($day['id']) && in_array($day['id'], $existingDayIds)) {
                    // Cập nhật ngày đã tồn tại
                    $itineraryModel->update($day['id'], $day);
                    $processedDayIds[] = $day['id'];
                } else {
                    // Thêm ngày mới
                    $itineraryModel->insert($day);
                }
            }
            
            // Xóa các ngày không còn trong danh sách
            foreach ($existingDays as $existingDay) {
                if (!in_array($existingDay['id'], $processedDayIds)) {
                    $itineraryModel->delete($existingDay['id']);
                }
            }
        } else {
            // Nếu không có lịch trình chi tiết, xóa tất cả
            require_once "models/TourItineraryModel.php";
            $itineraryModel = new TourItineraryModel();
            $itineraryModel->deleteByTourId($id);
        }
        
        $_SESSION['message'] = "Tour đã được cập nhật thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    public function delete() {
        $id = $_GET["id"] ?? null;
        if ($id) $this->tourModel->deleteTour($id);
        $_SESSION['message'] = "Tour đã được xóa thành công!";
        header("Location: index.php?act=tour");
        exit();
    }

    // Lịch
    public function lichList() {
        $tour_id = (int)$_GET["tour_id"];
        $tour = $this->tourModel->getTourById($tour_id);
        $listLich = $this->lichModel->getLichByTour($tour_id);
        include "views/admin/lich_list.php";
    }

    public function lichCreate() {
        $tour_id = (int)$_GET["tour_id"];
        $tour = $this->tourModel->getTourById($tour_id);
        include "views/admin/lich_create.php";
    }

    public function lichStore() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = $_POST;
        
        // Kiểm tra ngày khởi hành không được quá khứ
        if (!empty($data['departure_time'])) {
            $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
            $departureDateTime = strtotime($data['departure_time']);
            $now = time();
            
            if ($departureDateTime < $now) {
                $tour_id = (int)$_POST["tour_id"];
                $_SESSION['error'] = "Vui lòng chọn ngày khởi hành trong tương lai.";
                header("Location: index.php?act=lichCreate&tour_id=" . $tour_id);
                exit();
            }
        }
        
        // Kiểm tra số chỗ (7-50 chỗ)
        $seatsAvailable = (int)($data['seats_available'] ?? 0);
        if ($seatsAvailable < 7 || $seatsAvailable > 50) {
            $tour_id = (int)$_POST["tour_id"];
            $_SESSION['error'] = "Số chỗ phải từ 7 đến 50 chỗ (phù hợp với các loại xe khách hiện nay).";
            header("Location: index.php?act=lichCreate&tour_id=" . $tour_id);
            exit();
        }
        $this->lichModel->insertLich($data);
        $tour_id = (int)$_POST["tour_id"];
        $_SESSION['message'] = "Lịch khởi hành đã được thêm thành công!";
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }

    public function lichEdit() {
        $id = (int)$_GET["id"];
        $lich = $this->lichModel->getLichById($id);
        $tour = $this->tourModel->getTourById((int)$lich["tour_id"]);
        include "views/admin/lich_edit.php";
    }

    public function lichUpdate() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = (int)$_POST["id"];
        $data = $_POST;
        
        // Kiểm tra ngày khởi hành không được quá khứ
        if (!empty($data['departure_time'])) {
            $data['departure_time'] = str_replace("T", " ", $data['departure_time']);
            $departureDateTime = strtotime($data['departure_time']);
            $now = time();
            
            if ($departureDateTime < $now) {
                $_SESSION['error'] = "Vui lòng chọn ngày khởi hành trong tương lai.";
                header("Location: index.php?act=lichEdit&id=" . $id);
                exit();
            }
        }
        
        // Kiểm tra số chỗ (7-50 chỗ)
        $seatsAvailable = (int)($data['seats_available'] ?? 0);
        if ($seatsAvailable < 7 || $seatsAvailable > 50) {
            $tour_id = (int)$_POST["tour_id"];
            $_SESSION['error'] = "Số chỗ phải từ 7 đến 50 chỗ (phù hợp với các loại xe khách hiện nay).";
            header("Location: index.php?act=lichEdit&id=" . $id);
            exit();
        }
        
        $this->lichModel->updateLich($id, $data);
        $tour_id = (int)$_POST["tour_id"];
        $_SESSION['message'] = "Lịch khởi hành đã được cập nhật thành công!";
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }

    public function lichDelete() {
        $id = (int)$_GET["id"];
        $lich = $this->lichModel->getLichById($id);
        $this->lichModel->deleteLich($id);
        $tour_id = (int)$lich["tour_id"];
        header("Location: index.php?act=lich&tour_id=" . $tour_id);
        exit();
    }
}
