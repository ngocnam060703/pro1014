<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HDV Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* Navbar custom styles */
.navbar {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.navbar-brand {
    font-weight: 600;
    font-size: 1.4rem;
}
.navbar .btn {
    transition: 0.2s;
}
.navbar .btn:hover {
    transform: translateY(-2px);
}
</style>
</head>
<body style="background:#eef2f7">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php?act=hdv_home">
        <i class="bi bi-compass-fill text-primary me-2"></i>HDV Panel
    </a>

    <div class="d-flex align-items-center ms-auto">
        <?php if(isset($_SESSION['guide_logged_in'])){ ?>
            <span class="me-3 text-secondary fw-medium">
                Xin chào, <strong><?= htmlspecialchars($_SESSION['guide']['fullname']) ?></strong>
            </span>
            <a href="index.php?act=hdv_home" class="btn btn-sm btn-outline-primary me-2">
                <i class="bi bi-house-door-fill me-1"></i> Home
            </a>
            <a href="index.php?act=hdv_logout" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        <?php } ?>
    </div>
  </div>
</nav>

<div class="container mt-4 p-3">