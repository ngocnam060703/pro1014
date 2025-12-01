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
$adminBookingController = new AdminBookingController();

$guideAuth              = new GuideAuthController();



// =====================
// GET ACTION
// =====================
$act = $_GET["act"] ?? "";


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

    /* ==== HDV LOGIN ==== */
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



/* ============================================================
    2) ROUTER CLIENT – HƯỚNG DẪN VIÊN
============================================================ */

if (strpos($act, "hdv_") === 0) {

    // Bắt buộc login trước khi vào panel HDV
    if (!isset($_SESSION['guide_logged_in'])) {
        header("Location: index.php?act=hdv_login");
        exit;
    }

    switch ($act) {

        case "hdv_home":
            $guideController->home(); // Controller xử lý và load view
            break;

        case "hdv_lichtrinh":
            include "controllers/hdv/lichtrinh.php";
            break;

        case "hdv_nhatky":
            include "controllers/hdv/nhatky.php";
            break;

        case "hdv_data":
            include "controllers/hdv/data.php";
            break;

        default:
            $guideController->home();
            break;
    }

    exit;  // RẤT QUAN TRỌNG ‼
}



/* ============================================================
    3) BẢO VỆ ADMIN – Chặn người chưa login
============================================================ */

$adminPublic = [
    "login", "loginForm",
    "hdv_login", "hdv_register",
    "hdv_login_post", "hdv_register_post"
];

if (!isset($_SESSION["user"]) && !in_array($act, $adminPublic)) {

    // Nếu là route HDV → đã xử lý ở trên nên không chặn
    if (strpos($act, "hdv_") === 0) {
        // bỏ qua
    } else {
        header("Location: index.php?act=loginForm");
        exit;
    }
}


// =====================
// MAIN ROUTER
// =====================
switch ($act) {

    // =====================
    // DASHBOARD
    // =====================
    case "dashboard":
        $adminController->dashboard();
        break;


    // =====================
    // ACCOUNT MANAGEMENT
    // =====================
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


    // =====================
    // TOUR
    // =====================
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


    // =====================
    // LỊCH TOUR
    // =====================
    case "lich":
        $tourController->lichList();
        break;

    case "lich-create":
        $tourController->lichCreate();
        break;

    case "lich-store":
        $tourController->lichStore();
        break;

    case "lich-edit":
        $tourController->lichEdit();
        break;

    case "lich-update":
        $tourController->lichUpdate();
        break;

    case "lich-delete":
        $tourController->lichDelete();
        break;


    // =====================
    // SCHEDULE
    // =====================
    case "schedule":$scheduleController->scheduleList();
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


    // =====================
    // SERVICE (Fixed + Clean)
    // =====================
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


    // =====================
    // GUIDE
    // =====================
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


    // =====================
    // GUIDE ASSIGN
    // =====================
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


    // =====================
    // GUIDE JOURNAL
    // =====================
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
    
    // =====================
    // BOOKING MANAGEMENT
    // =====================
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



    // =====================
    // DEFAULT
    // =====================
    default:$adminController->dashboard();
        break;
}