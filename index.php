<?php
session_start();

// =====================
// LOAD CONTROLLERS
// =====================
require_once "controllers/TourController.php";
require_once "controllers/AdminController.php";
require_once "controllers/GuideController.php";
require_once "controllers/GuideAssignController.php";
require_once "controllers/GuideJournalController.php";
require_once "controllers/ScheduleController.php";
require_once "controllers/ServiceController.php";
require_once "controllers/AdminBookingController.php";
require_once "controllers/GuideAuthController.php";
require_once "controllers/GuideIncidentController.php";
require_once "controllers/SpecialRequestController.php";
require_once "controllers/RevenueReportController.php";

// =====================
// MAKE CONTROLLER INSTANCE
// =====================
$adminController         = new AdminController();
$tourController          = new TourController();
$guideController         = new GuideController();
$guideAssignController   = new GuideAssignController();
$guideJournalController  = new GuideJournalController();
$scheduleController      = new ScheduleController();
$serviceController       = new ServiceController();
$adminBookingController  = new AdminBookingController();
$guideAuth               = new GuideAuthController();
$guideIncidentController = new GuideIncidentController();
$specialRequestController = new SpecialRequestController();
$revenueReportController = new RevenueReportController();

// =====================
// GET ACTION
// =====================
$act = $_GET["act"] ?? "";

// =====================
// ROUTE LOGIN / LOGOUT
// =====================
switch ($act) {
    case "loginForm":
        $adminController->loginForm();
        exit;

    case "login":
        $adminController->login();
        exit;

    case "logout":
        $adminController->logout();
        exit;

    // ==== HDV LOGIN ====
    case "hdv_login":
        $guideAuth->loginForm();
        exit;

    case "hdv_login_post":
        $guideAuth->loginPost();
        exit;

    case "hdv_register":
        $guideAuth->registerForm();
        exit;

    case "hdv_register_post":
        $guideAuth->registerPost();
        exit;

    case "hdv_logout":
        $guideAuth->logout();
        exit;
}

// =====================
// ROUTER HDV (HƯỚNG DẪN VIÊN)
// =====================
if (strpos($act, "hdv_") === 0) {

    // Bắt buộc login HDV
    if (!isset($_SESSION['guide_logged_in'])) {
        header("Location: index.php?act=hdv_login");
        exit;
    }

    switch ($act) {
        case "hdv_home":
            require_once "models/hdv_model.php";
            $guide_id = $_SESSION['guide']['id'] ?? 0;
            $totalToursToday = getCountToursTodayByGuide($guide_id);
            $totalTours = getCountToursByGuide($guide_id);
            $incidentsReported = getCountIncidentsByGuide($guide_id);
            $journalsCount = getCountLogsByGuide($guide_id);
            include "views/hdv/dashboard.php";
            break;

        case "hdv_lichtrinh":
            require_once "models/hdv_model.php";
            $guide_id = $_SESSION['guide']['id'] ?? 0;
            $schedules = getScheduleByGuide($guide_id);
            include "views/hdv/lichtrinh.php";
            break;

        case "hdv_nhatky":
            include "views/hdv/nhatky.php";
            break;

        case "hdv_journal_store":
            require_once "models/GuideJournalModel.php";
            require_once "commons/function.php";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $journalModel = new GuideJournalModel();
                
                // Xử lý upload ảnh
                $photos = null;
                if (!empty($_FILES['photos']['name'][0])) {
                    $photoPaths = [];
                    foreach($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                        if ($tmp_name) {
                            $file = [
                                'name' => $_FILES['photos']['name'][$key],
                                'tmp_name' => $tmp_name,
                                'type' => $_FILES['photos']['type'][$key],
                                'size' => $_FILES['photos']['size'][$key]
                            ];
                            $path = uploadFile($file, "uploads/journals/");
                            if ($path) {
                                $photoPaths[] = $path;
                            }
                        }
                    }
                    if (!empty($photoPaths)) {
                        $photos = implode(',', $photoPaths);
                    }
                }
                
                $data = [
                    "guide_id" => $_POST["guide_id"],
                    "departure_id" => $_POST["departure_id"],
                    "note" => $_POST["note"],
                    "day_number" => $_POST["day_number"] ?? null,
                    "activities" => $_POST["activities"] ?? null,
                    "photos" => $photos,
                    "customer_feedback" => $_POST["customer_feedback"] ?? null,
                    "weather" => $_POST["weather"] ?? null,
                    "mood" => $_POST["mood"] ?? null
                ];
                $journalModel->store($data);
                $_SESSION['message'] = "Thêm nhật ký thành công!";
            }
            header("Location: index.php?act=hdv_nhatky");
            exit;
            break;

        case "hdv_journal_edit":
            require_once "models/GuideJournalModel.php";
            require_once "models/GuideAssignModel.php";
            $id = $_GET["id"] ?? 0;
            $journalModel = new GuideJournalModel();
            $journal = $journalModel->find($id);
            $guide_id = $_SESSION['guide']['id'] ?? 0;
            // Kiểm tra quyền
            if ($journal && $journal['guide_id'] == $guide_id) {
                $assignModel = new GuideAssignModel();
                $myAssigns = $assignModel->getByGuide($guide_id);
                include "views/hdv/nhatky_edit.php";
            } else {
                $_SESSION['error'] = "Không có quyền truy cập!";
                header("Location: index.php?act=hdv_nhatky");
                exit;
            }
            break;

        case "hdv_journal_update":
            require_once "models/GuideJournalModel.php";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST["id"];
                $journalModel = new GuideJournalModel();
                $journal = $journalModel->find($id);
                $guide_id = $_SESSION['guide']['id'] ?? 0;
                // Kiểm tra quyền
                if ($journal && $journal['guide_id'] == $guide_id) {
                    $data = [
                        "guide_id" => $guide_id,
                        "departure_id" => $_POST["departure_id"],
                        "note" => $_POST["note"]
                    ];
                    $journalModel->updateData($id, $data);
                    $_SESSION['message'] = "Cập nhật nhật ký thành công!";
                } else {
                    $_SESSION['error'] = "Không có quyền truy cập!";
                }
            }
            header("Location: index.php?act=hdv_nhatky");
            exit;
            break;

        case "hdv_journal_delete":
            require_once "models/GuideJournalModel.php";
            $id = $_GET["id"] ?? 0;
            $journalModel = new GuideJournalModel();
            $journal = $journalModel->find($id);
            $guide_id = $_SESSION['guide']['id'] ?? 0;
            // Kiểm tra quyền
            if ($journal && $journal['guide_id'] == $guide_id) {
                $journalModel->delete($id);
                $_SESSION['message'] = "Xóa nhật ký thành công!";
            } else {
                $_SESSION['error'] = "Không có quyền truy cập!";
            }
            header("Location: index.php?act=hdv_nhatky");
            exit;
            break;

        case "hdv_data":
            include "views/hdv/data.php";
            break;

        case "hdv_schedule_detail":
            require_once "models/GuideScheduleModel.php";
            include "views/hdv/schedule_detail.php";
            break;

        case "hdv_customers":
            require_once "models/GuideScheduleModel.php";
            require_once "models/CustomerSpecialRequestModel.php";
            include "views/hdv/customers.php";
            break;

        case "hdv_checkin":
            require_once "models/GuideScheduleModel.php";
            require_once "models/GuideCheckinModel.php";
            include "views/hdv/checkin.php";
            break;

        case "hdv_checkin_store":
            require_once "models/GuideCheckinModel.php";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $checkinModel = new GuideCheckinModel();
                $guide_id = $_POST['guide_id'];
                $departure_id = $_POST['departure_id'];
                $booking_ids = $_POST['booking_ids'] ?? [];
                $statuses = $_POST['status'] ?? [];
                $notes = $_POST['notes'] ?? [];
                $location = $_POST['checkin_location'] ?? '';
                
                foreach($booking_ids as $booking_id) {
                    $data = [
                        'guide_id' => $guide_id,
                        'departure_id' => $departure_id,
                        'booking_id' => $booking_id,
                        'checkin_time' => date('Y-m-d H:i:s'),
                        'checkin_location' => $location,
                        'status' => $statuses[$booking_id] ?? 'checked_in',
                        'notes' => $notes[$booking_id] ?? null
                    ];
                    $checkinModel->checkin($data);
                }
                $_SESSION['message'] = "Check-in thành công!";
            }
            header("Location: index.php?act=hdv_checkin&departure_id=" . $_POST['departure_id']);
            exit;
            break;

        case "hdv_special_request_store":
            require_once "models/CustomerSpecialRequestModel.php";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $requestModel = new CustomerSpecialRequestModel();
                $data = [
                    'booking_id' => $_POST['booking_id'],
                    'request_type' => $_POST['request_type'],
                    'description' => $_POST['description'],
                    'status' => 'pending',
                    'notes' => $_POST['notes'] ?? null
                ];
                $requestModel->store($data);
                $_SESSION['message'] = "Đã thêm yêu cầu đặc biệt!";
            }
            header("Location: " . $_SERVER['HTTP_REFERER'] ?? "index.php?act=hdv_customers");
            exit;
            break;

        case "hdv_feedback":
            require_once "models/GuideFeedbackModel.php";
            require_once "models/GuideScheduleModel.php";
            include "views/hdv/feedback.php";
            break;

        case "hdv_feedback_store":
            require_once "models/GuideFeedbackModel.php";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $feedbackModel = new GuideFeedbackModel();
                $data = [
                    'guide_id' => $_POST['guide_id'],
                    'departure_id' => $_POST['departure_id'] ?? null,
                    'feedback_type' => $_POST['feedback_type'],
                    'provider_name' => $_POST['provider_name'] ?? null,
                    'rating' => $_POST['rating'] ?? null,
                    'comment' => $_POST['comment'],
                    'suggestions' => $_POST['suggestions'] ?? null
                ];
                $feedbackModel->store($data);
                $_SESSION['message'] = "Đã gửi phản hồi thành công!";
            }
            $redirect = $_POST['departure_id'] ? "index.php?act=hdv_feedback&departure_id=" . $_POST['departure_id'] : "index.php?act=hdv_feedback";
            header("Location: " . $redirect);
            exit;
            break;

        case "hdv_incident_store":
            require_once "models/GuideIncidentModel.php";
            require_once "commons/function.php";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $incidentModel = new GuideIncidentModel();
                $photoPath = null;
                if (!empty($_FILES["photos"]["name"])) {
                    $photoPath = uploadFile($_FILES["photos"], "uploads/incidents/");
                }
                $data = [
                    "departure_id" => $_POST["departure_id"],
                    "guide_id" => $_POST["guide_id"],
                    "incident_type" => $_POST["incident_type"],
                    "severity" => $_POST["severity"],
                    "description" => $_POST["description"],
                    "solution" => $_POST["solution"] ?? '',
                    "photos" => $photoPath
                ];
                $incidentModel->store($data);
                $_SESSION['message'] = "Báo cáo sự cố thành công!";
            }
            header("Location: index.php?act=hdv_data");
            exit;
            break;

        case "hdv_incident_edit":
            require_once "models/GuideIncidentModel.php";
            require_once "models/GuideAssignModel.php";
            $id = $_GET["id"] ?? 0;
            $incidentModel = new GuideIncidentModel();
            $incident = $incidentModel->find($id);
            $guide_id = $_SESSION['guide']['id'] ?? 0;
            // Kiểm tra quyền
            if ($incident && $incident['guide_id'] == $guide_id) {
                $assignModel = new GuideAssignModel();
                $myAssigns = $assignModel->getByGuide($guide_id);
                include "views/hdv/incident_edit.php";
            } else {
                $_SESSION['error'] = "Không có quyền truy cập!";
                header("Location: index.php?act=hdv_data");
                exit;
            }
            break;

        case "hdv_incident_update":
            require_once "models/GuideIncidentModel.php";
            require_once "commons/function.php";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST["id"];
                $incidentModel = new GuideIncidentModel();
                $incident = $incidentModel->find($id);
                $guide_id = $_SESSION['guide']['id'] ?? 0;
                // Kiểm tra quyền
                if ($incident && $incident['guide_id'] == $guide_id) {
                    $photoPath = $incident['photos'] ?? null;
                    if (!empty($_FILES["photos"]["name"])) {
                        if ($photoPath) {
                            deleteFile($photoPath);
                        }
                        $photoPath = uploadFile($_FILES["photos"], "uploads/incidents/");
                    }
                    $data = [
                        "departure_id" => $_POST["departure_id"],
                        "guide_id" => $guide_id,
                        "incident_type" => $_POST["incident_type"],
                        "severity" => $_POST["severity"],
                        "description" => $_POST["description"],
                        "solution" => $_POST["solution"] ?? '',
                        "photos" => $photoPath
                    ];
                    $incidentModel->updateData($id, $data);
                    $_SESSION['message'] = "Cập nhật báo cáo sự cố thành công!";
                } else {
                    $_SESSION['error'] = "Không có quyền truy cập!";
                }
            }
            header("Location: index.php?act=hdv_data");
            exit;
            break;

        case "hdv_incident_delete":
            require_once "models/GuideIncidentModel.php";
            require_once "commons/function.php";
            $id = $_GET["id"] ?? 0;
            $incidentModel = new GuideIncidentModel();
            $incident = $incidentModel->find($id);
            $guide_id = $_SESSION['guide']['id'] ?? 0;
            // Kiểm tra quyền
            if ($incident && $incident['guide_id'] == $guide_id) {
                if ($incident['photos']) {
                    deleteFile($incident['photos']);
                }
                $incidentModel->delete($id);
                $_SESSION['message'] = "Xóa báo cáo sự cố thành công!";
            } else {
                $_SESSION['error'] = "Không có quyền truy cập!";
            }
            header("Location: index.php?act=hdv_data");
            exit;
            break;

        default:
            require_once "models/hdv_model.php";
            $guide_id = $_SESSION['guide']['id'] ?? 0;
            $totalToursToday = getCountToursTodayByGuide($guide_id);
            $totalTours = getCountToursByGuide($guide_id);
            $incidentsReported = getCountIncidentsByGuide($guide_id);
            $journalsCount = getCountLogsByGuide($guide_id);
            include "views/hdv/dashboard.php";
            break;
    }
    exit;
}

// =====================
// BẢO VỆ ADMIN
// =====================
$adminPublic = [
    "login", "loginForm",
    "hdv_login", "hdv_register",
    "hdv_login_post", "hdv_register_post"
];

if (!isset($_SESSION["user"]) && !in_array($act, $adminPublic)) {
    // Nếu là HDV → đã xử lý ở trên
    if (strpos($act, "hdv_") !== 0) {
        header("Location: index.php?act=loginForm");
        exit;
    }
}

// =====================
// ROUTER ADMIN / MAIN
// =====================
switch ($act) {

    // DASHBOARD
    case "dashboard":
        $adminController->dashboard();
        break;

    // ACCOUNT MANAGEMENT
    case "account":
        $adminController->accountList();
        break;

    case "account-create":
        $adminController->accountCreate();
        break;

    case "account-store":
        $adminController->accountStore();
        break;

    case "account-edit":
        $adminController->accountEdit();
        break;

    case "account-update":
        $adminController->accountUpdate();
        break;

    case "account-delete":
        $adminController->accountDelete();
        break;
    // GUIDE INCIDENT (SỰ CỐ HDV)
    case "guide-incident":
        $guideIncidentController->index(); // danh sách sự cố
        break;

    case "guide-incident-create":
        $guideIncidentController->create(); // form tạo mới
        break;

    case "guide-incident-store":
        $guideIncidentController->store(); // lưu mới
        break;

    case "guide-incident-edit":
        $guideIncidentController->edit(); // form sửa
        break;

    case "guide-incident-update":
        $guideIncidentController->update(); // cập nhật
        break;

    case "guide-incident-delete":
        $guideIncidentController->delete(); // xóa
        break;
    case "guide-incident-detail":
    $guideIncidentController->detail();
    break;


    // TOUR
    case "tour":
        $tourController->index();
        break;

    case "tour-create":
        $tourController->create();
        break;

    case "tour-store":
        $tourController->store();
        break;

    case "tour-edit":
        $tourController->edit();
        break;

    case "tour-update":
        $tourController->update();
        break;

    case "tour-delete":
        $tourController->delete();
        break;

    // SCHEDULE
    case "schedule":
        $scheduleController->scheduleList();
        break;

    case "schedule-create":
        $scheduleController->scheduleCreate();
        break;

    case "schedule-store":
        $scheduleController->scheduleStore();
        break;

    case "schedule-edit":
        $scheduleController->scheduleEdit();
        break;

    case "schedule-update":
        $scheduleController->scheduleUpdate();
        break;

    case "schedule-delete":
        $scheduleController->scheduleDelete();
        break;

    case "schedule-detail":
        $scheduleController->scheduleDetail();
        break;

    case "staff-assignment-store":
        $scheduleController->staffAssignmentStore();
        break;

    case "staff-assignment-delete":
        $scheduleController->staffAssignmentDelete();
        break;

    case "service-allocation-store":
        $scheduleController->serviceAllocationStore();
        break;

    case "service-allocation-delete":
        $scheduleController->serviceAllocationDelete();
        break;

    // SERVICE
    case "service":
        $serviceController->list();
        break;

    case "service-create":
        $serviceController->create();
        break;

    case "service-store":
        $serviceController->store();
        break;

    case "service-edit":
        $serviceController->edit();
        break;

    case "service-update":
        $serviceController->update();
        break;

    case "service-delete":
        $serviceController->delete();
        break;

    // SPECIAL REQUEST
    case "special-request":
        $specialRequestController->index();
        break;

    case "special-request-create":
        $specialRequestController->create();
        break;

    case "special-request-store":
        $specialRequestController->store();
        break;

    case "special-request-edit":
        $specialRequestController->edit();
        break;

    case "special-request-update":
        $specialRequestController->update();
        break;

    case "special-request-delete":
        $specialRequestController->delete();
        break;

    case "special-request-update-status":
        $specialRequestController->updateStatus();
        break;

    // GUIDE
    case "guide":
        $guideController->index();
        break;

    case "guide-create":
        $guideController->create();
        break;

    case "guide-store":
        $guideController->store();
        break;

    case "guide-edit":
        $guideController->edit();
        break;

    case "guide-update":
        $guideController->update();
        break;

    case "guide-delete":
        $guideController->delete();
        break;

    case "guide-detail":
        $guideController->detail();
        break;

    // GUIDE ASSIGN
    case "guide-assign":
        $guideAssignController->index();
        break;

    case "guide-assign-create":
        $guideAssignController->create();
        break;

    case "guide-assign-store":
        $guideAssignController->store();
        break;

    case "guide-assign-edit":
        $guideAssignController->edit();
        break;

    case "guide-assign-update":
        $guideAssignController->update();
        break;

    case "guide-assign-delete":
        $guideAssignController->delete();
        break;

    // GUIDE JOURNAL
    case "guide-journal":
        $guideJournalController->index();
        break;

    case "guide-journal-create":
        $guideJournalController->create();
        break;

    case "guide-journal-store":
        $guideJournalController->store();
        break;

    case "guide-journal-edit":
        $guideJournalController->edit();
        break;

    case "guide-journal-update":
        $guideJournalController->update();
        break;

    case "guide-journal-delete":
        $guideJournalController->delete();
        break;

    // BOOKING MANAGEMENT
    case "booking":
        $adminBookingController->bookingList();
        break;

    case "booking-create":
        $adminBookingController->bookingCreate();
        break;

    case "booking-store":
        $adminBookingController->bookingStore();
        break;

    case "booking-check-availability":
        $adminBookingController->bookingCheckAvailability();
        break;

    case "booking-calculate-price":
        $adminBookingController->bookingCalculatePrice();
        break;

    case "booking-detail":
        $adminBookingController->bookingDetail();
        break;

    case "booking-update-status":
        $adminBookingController->updateStatus();
        break;

    case "booking-delete":
        $adminBookingController->delete();
        break;

    // REVENUE REPORT
    case "revenue-report":
        $revenueReportController->index();
        break;

    case "revenue-report-detail":
        $revenueReportController->tourDetail();
        break;

    // DEFAULT
    default:
        $adminController->dashboard();
        break;
}
