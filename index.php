<?php
session_start();

$user = $_SESSION['user'] ?? null;

if ($user) {
    $_SESSION["user"] = [
        "id" => $user["id"],
        "username" => $user["username"],
        "role" => $user["role"],
        "full_name" => $user["full_name"]
    ];
}
// =====================
// LOAD CONTROLLERS
// =====================
require_once "controllers/TourController.php";
require_once "controllers/AdminController.php";
require_once "controllers/GuideController.php";
require_once "controllers/GuideAssignController.php";
require_once "controllers/GuideJournalController.php";
require_once "controllers/GuideClientController.php";

// Instance controllers
$tourController = new TourController();
$adminController = new AdminController();
$guideController = new GuideController();
$guideAssignController = new GuideAssignController();
$guideJournalController = new GuideJournalController();
$guideClientController = new GuideClientController();

$act = $_GET["act"] ?? "";


// =====================
// ROLE & PERMISSION
// =====================
$role = $_SESSION["user"]["role"] ?? null;

// Các action của ADMIN
$adminActions = [
    "dashboard", "account", "account-create", "account-store", "account-edit",
    "account-update", "account-delete",
    "tour", "tour-create", "tour-store", "tour-edit", "tour-update", "tour-delete",
    "lich", "lich-create", "lich-store", "lich-edit", "lich-update", "lich-delete",
    "guide", "guide-create", "guide-store", "guide-edit", "guide-update", "guide-delete", "guide-detail",
    "guide-assign", "guide-assign-create", "guide-assign-store", "guide-assign-edit",
    "guide-assign-update", "guide-assign-delete",
    "guide-journal", "guide-journal-create", "guide-journal-store",
    "guide-journal-edit", "guide-journal-update", "guide-journal-delete",
];

// Các action của HƯỚNG DẪN VIÊN
$guideActions = [
    "hdv_dashboard",
    "hdv_schedule",
    "hdv_journal",
    "hdv_incident"
];  

// =====================
// CHECK PERMISSION
// =====================
if ($role === "admin") {
    // HDV không được vào trang admin
    if (in_array($act, $guideActions)) {
        header("Location: index.php?act=dashboard");
        exit;
    }
}

if ($role === "user") {
    // Admin không được vào trang HDV
    if (in_array($act, $adminActions)) {
        header("Location: index.php?act=hdv_dashboard");
        exit;
    }
}



// =====================
// GET ACTION
// =====================





// =====================
// ROUTE: LOGIN / LOGOUT
// =====================
switch ($act) {

    case "login":
        $adminController->login();
        exit;

    case "loginForm":
        $adminController->loginForm();
        exit;

    case "logout":
        $adminController->logout();
        exit;
}


// =====================
// CHECK LOGIN
// =====================
$publicActions = ["login", "loginForm"];

if (!isset($_SESSION["user"]) && !in_array($act, $publicActions)) {
    header("Location: index.php?act=loginForm");
    exit;
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
    // SCHEDULE
    // =====================
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

    //Client HDV

    case 'hdv_dashboard':
    $guideClientController->dashboard();
    break;

    case "hdv_logout":
    session_unset();
    session_destroy();
    header("Location: index.php?act=loginForm"); // hoặc login form HDV riêng nếu có
    exit;

    case 'hdv_login': // hiển thị form login HDV
        $error = isset($_GET['error']) ? "Đăng nhập thất bại!" : '';
        require 'views/client_hdv/login.php';
        break;

case 'hdv_login_post':
    $controller = new GuideClientController();
    $controller->loginPost();
    break;

case 'hdv_dashboard':
    $controller = new GuideClientController();
    $controller->dashboard();
    break;




    // =====================
    // DEFAULT
    // =====================
    default:
        $adminController->dashboard();
        break;
}
