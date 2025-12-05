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
            include "views/hdv/dashboard.php";
            break;

        case "hdv_lichtrinh":
            include "views/hdv/lichtrinh.php";
            break;

        case "hdv_nhatky":
            include "views/hdv/nhatky.php";
            break;

        case "hdv_data":
            include "views/hdv/data.php";
            break;

        default:
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

    case "booking-detail":
        $adminBookingController->bookingDetail();
        break;

    case "booking-update-status":
        $adminBookingController->updateStatus();
        break;

    case "booking-delete":
        $adminBookingController->delete();
        break;

    // DEFAULT
    default:
        $adminController->dashboard();
        break;
}
