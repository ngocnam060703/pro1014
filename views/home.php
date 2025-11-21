<?php
// Giả sử $suggestedTours được lấy từ DB
$suggestedTours = [
    ["id"=>1,"title"=>"Hạ Long Bay","price"=>2000000,"image"=>"uploads/tour-du-lich-ha-long-5_1821_HasThumb.jpg","departure"=>"Hà Nội","duration"=>"3 ngày 2 đêm"],
    ["id"=>2,"title"=>"Đà Nẵng - Hội An","price"=>3000000,"image"=>"uploads/danang.jpeg","departure"=>"Hà Nội","duration"=>"4 ngày 3 đêm"],
    ["id"=>3,"title"=>"Phú Quốc","price"=>4000000,"image"=>"uploads/phuquoc.jpeg","departure"=>"TP.HCM","duration"=>"3 ngày 2 đêm"],
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tour  Du  Lịch - Trang Chủ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #d3cdd3ff; }
    .hero { position: relative; text-align: center; color: black; margin-bottom: 50px; }
    .hero .carousel-inner img { height: 400px; object-fit: cover; border-radius: 10px; }
    .btn-add-tour { position: absolute; top: 20px; right: 20px; z-index: 5; }
    .card-tour { border-radius: 15px; overflow: hidden; transition: 0.3s; }
    .card-tour:hover { transform: translateY(-8px); box-shadow: 0 15px 25px rgba(0,0,0,0.2); }
    .card-tour img { height: 200px; object-fit: cover; }
    .price { color: #0d6efd; font-weight: bold; }
    footer { background:#0d6efd; color:#fff; padding: 40px 0; }
    footer a { color:#fff; text-decoration:none; }
    footer a:hover { text-decoration: underline; }
    .btn-view { background:#0d6efd; color:#fff; font-weight:bold; transition:0.3s; }
    .btn-view:hover { background:#094bb5; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Tour DuLich</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Tour</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section với Carousel -->
<section class="hero container position-relative">
  <h1 class="mb-3">Khám phá Việt Nam</h1>
  <a href="index.php?act=tour-create" class="btn btn-success btn-add-tour">+ Thêm Tour</a>

  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner rounded">
      <div class="carousel-item active">
        <img src="uploads/anhto.jpg" class="d-block w-100" alt="Banner 1">
      </div>
      <div class="carousel-item">
        <img src="uploads/anhto2.webp" class="d-block w-100" alt="Banner 2">
      </div>
      <div class="carousel-item">
        <img src="uploads/anhto3.jpg" class="d-block w-100" alt="Banner 3">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</section>

<!-- Suggested Tours -->
<section class="container py-5">
  <h2 class="fw-bold mb-4 text-center">Tour gợi ý cho bạn</h2>
  <div class="row g-4">
    <?php foreach($suggestedTours as $tour): ?>
      <div class="col-md-4">
        <div class="card card-tour shadow-sm">
          <img src="<?= $tour['image'] ?>" class="card-img-top" alt="<?= $tour['title'] ?>">
          <div class="card-body">
            <h5 class="card-title"><?= $tour['title'] ?></h5>
            <p class="mb-1"><i class="bi bi-geo-alt-fill"></i> <?= $tour['departure'] ?></p>
            <p class="mb-1"><i class="bi bi-clock-fill"></i> <?= $tour['duration'] ?></p>
            <p class="price"><?= number_format($tour['price']) ?>đ</p>
            <a href="index.php?act=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-view w-100">Xem chi tiết</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-3">
        <h5>TourDuLich</h5>
        <p>Khám phá Việt Nam cùng chúng tôi. Tour đẹp, giá tốt, dịch vụ chất lượng.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h5>Liên hệ</h5>
        <p>Email: support@tourdulich.vn</p>
        <p>Hotline: 0123 456 789</p>
      </div>
      <div class="col-md-4 mb-3">
        <h5>Mạng xã hội</h5>
        <a href="#"><i class="bi bi-facebook"></i> Facebook</a><br>
        <a href="#"><i class="bi bi-instagram"></i> Instagram</a>
      </div>
    </div>
    <div class="text-center mt-3">© 2025 Tour Du Lịch</div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
