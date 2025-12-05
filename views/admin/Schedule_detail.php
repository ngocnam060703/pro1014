<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Hàm hiển thị badge trạng thái
function getStatusBadge($status) {
    $badges = [
        'scheduled' => '<span class="badge bg-secondary">Đã lên lịch</span>',
        'confirmed' => '<span class="badge bg-primary">Đã xác nhận</span>',
        'in_progress' => '<span class="badge bg-info">Đang diễn ra</span>',
        'completed' => '<span class="badge bg-success">Hoàn tất</span>',
        'cancelled' => '<span class="badge bg-danger">Đã hủy</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">N/A</span>';
}

function getStaffTypeName($type) {
    $names = [
        'guide' => 'Hướng dẫn viên',
        'driver' => 'Tài xế',
        'logistics' => 'Nhân viên hậu cần',
        'coordinator' => 'Điều phối viên',
        'other' => 'Khác'
    ];
    return $names[$type] ?? $type;
}

function getServiceTypeName($type) {
    $names = [
        'transport' => 'Vận chuyển',
        'hotel' => 'Khách sạn',
        'flight' => 'Vé máy bay',
        'restaurant' => 'Nhà hàng',
        'attraction' => 'Điểm tham quan',
        'insurance' => 'Bảo hiểm',
        'other' => 'Khác'
    ];
    return $names[$type] ?? $type;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết lịch khởi hành</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(to right, #dfe9f3, #ffffff);
    font-family: 'Segoe UI', sans-serif;
}
.sidebar {
    height: 100vh;
    background: #343a40;
    padding-top: 20px;
}
.sidebar h4 { font-weight: 700; color:#fff; }
.sidebar a {
    color: #ccc;
    padding: 12px;
    display: block;
    text-decoration: none;
    font-size: 15px;
    border-left: 3px solid transparent;
}
.sidebar a:hover {
    background: #495057;
    color: #fff;
    border-left: 3px solid #0d6efd;
}
.sidebar a.active {
    color:#fff;
    background:#495057;
    border-left:3px solid #0d6efd;
}
.content { padding: 30px; }
.card {
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
</style>
</head>
<body>
<div class="row g-0">

  <!-- SIDEBAR -->
  <div class="col-2 sidebar">
    <h4 class="text-center mb-4">ADMIN</h4>
    <a href="index.php?act=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?act=account"><i class="bi bi-people"></i> Quản lý tài khoản</a>
    <a href="index.php?act=guide"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
    <a href="index.php?act=schedule" class="active"><i class="bi bi-calendar-event"></i> Quản lý lịch trình</a>
    <a href="index.php?act=service"><i class="bi bi-grid"></i> Quản lý dịch vụ</a>
    <a href="index.php?act=tour"><i class="bi bi-card-list"></i> Quản lý Tour</a>
    <a href="index.php?act=booking"><i class="bi bi-cart"></i> Quản lý Booking</a>
    <a href="index.php?act=special-request"><i class="bi bi-exclamation-circle"></i> Yêu cầu đặc biệt</a>
    <a href="index.php?act=guide-assign"><i class="bi bi-card-list"></i> Phân công HDV</a>
    <a href="index.php?act=guide-incident"><i class="bi bi-exclamation-triangle"></i> Danh sách sự cố</a>
    <a href="?act=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- CONTENT -->
  <div class="col-10 content">
    <div class="d-flex justify-content-between mb-4">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-calendar-check"></i> Chi tiết lịch khởi hành
      </h3>
      <div>
        <a href="index.php?act=schedule-edit&id=<?= $schedule['id'] ?>" class="btn btn-warning me-2">
          <i class="bi bi-pencil-square"></i> Sửa lịch
        </a>
        <a href="index.php?act=schedule" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Quay lại
        </a>
      </div>
    </div>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Thông tin lịch khởi hành -->
    <div class="card p-4 mb-4">
        <h5 class="mb-3"><i class="bi bi-info-circle"></i> Thông tin lịch khởi hành</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Tour:</strong> <?= htmlspecialchars($schedule['tour_name'] ?? 'N/A') ?></p>
                <p><strong>Ngày khởi hành:</strong> 
                    <?php if (!empty($schedule['departure_date'])): ?>
                        <?= date('d/m/Y', strtotime($schedule['departure_date'])) ?>
                    <?php elseif (!empty($schedule['departure_time'])): ?>
                        <?= date('d/m/Y', strtotime($schedule['departure_time'])) ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </p>
                <p><strong>Giờ xuất phát:</strong> 
                    <?php if (!empty($schedule['departure_time'])): ?>
                        <?= date('H:i', strtotime($schedule['departure_time'])) ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </p>
                <p><strong>Ngày kết thúc:</strong> 
                    <?= !empty($schedule['end_date']) ? date('d/m/Y', strtotime($schedule['end_date'])) : 'N/A' ?>
                </p>
                <p><strong>Giờ kết thúc:</strong> 
                    <?= !empty($schedule['end_time']) ? $schedule['end_time'] : 'N/A' ?>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Điểm tập trung:</strong> <?= htmlspecialchars($schedule['meeting_point'] ?? 'N/A') ?></p>
                <p><strong>Địa chỉ chi tiết:</strong> <?= htmlspecialchars($schedule['meeting_address'] ?? 'N/A') ?></p>
                <p><strong>Tổng số chỗ:</strong> <?= $schedule['total_seats'] ?? 0 ?></p>
                <p><strong>Đã đặt:</strong> <?= $schedule['seats_booked'] ?? 0 ?></p>
                <p><strong>Còn trống:</strong> <span class="text-success fw-bold"><?= $schedule['seats_available'] ?? 0 ?></span></p>
                <p><strong>Trạng thái:</strong> <?= getStatusBadge($schedule['status'] ?? 'scheduled') ?></p>
            </div>
        </div>
        <?php if (!empty($schedule['meeting_instructions'])): ?>
            <div class="mt-3">
                <strong>Hướng dẫn đến điểm tập trung:</strong>
                <p class="text-muted"><?= nl2br(htmlspecialchars($schedule['meeting_instructions'])) ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($schedule['notes'])): ?>
            <div class="mt-3">
                <strong>Ghi chú:</strong>
                <p class="text-muted"><?= nl2br(htmlspecialchars($schedule['notes'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- Phân bổ nhân sự -->
        <div class="col-md-6 mb-4">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-people"></i> Phân bổ nhân sự</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                        <i class="bi bi-plus-circle"></i> Thêm
                    </button>
                </div>

                <?php if (!empty($staffAssignments)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Loại</th>
                                    <th>Tên</th>
                                    <th>Vai trò</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($staffAssignments as $staff): ?>
                                    <tr>
                                        <td><?= getStaffTypeName($staff['staff_type']) ?></td>
                                        <td>
                                            <?= htmlspecialchars($staff['staff_name'] ?? $staff['guide_name'] ?? 'N/A') ?>
                                            <?php if (!empty($staff['staff_phone'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($staff['staff_phone']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($staff['role'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php
                                            $statusBadges = [
                                                'assigned' => 'bg-secondary',
                                                'confirmed' => 'bg-primary',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ];
                                            $status = $staff['status'] ?? 'assigned';
                                            ?>
                                            <span class="badge <?= $statusBadges[$status] ?? 'bg-secondary' ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?act=staff-assignment-delete&id=<?= $staff['id'] ?>" 
                                               onclick="return confirm('Xóa phân bổ này?')"
                                               class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Chưa có phân bổ nhân sự</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Phân bổ dịch vụ -->
        <div class="col-md-6 mb-4">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-gear"></i> Phân bổ dịch vụ</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="bi bi-plus-circle"></i> Thêm
                    </button>
                </div>

                <?php if (!empty($serviceAllocations)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Loại</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Nhà cung cấp</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($serviceAllocations as $service): ?>
                                    <tr>
                                        <td><?= getServiceTypeName($service['service_type']) ?></td>
                                        <td>
                                            <?= htmlspecialchars($service['service_name']) ?>
                                            <?php if ($service['service_type'] == 'transport' && !empty($service['vehicle_number'])): ?>
                                                <br><small class="text-muted">Xe: <?= htmlspecialchars($service['vehicle_number']) ?></small>
                                            <?php elseif ($service['service_type'] == 'flight' && !empty($service['flight_number'])): ?>
                                                <br><small class="text-muted">Chuyến: <?= htmlspecialchars($service['flight_number']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($service['provider_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php
                                            $statusBadges = [
                                                'pending' => 'bg-warning',
                                                'confirmed' => 'bg-primary',
                                                'in_use' => 'bg-info',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ];
                                            $status = $service['status'] ?? 'pending';
                                            ?>
                                            <span class="badge <?= $statusBadges[$status] ?? 'bg-secondary' ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?act=service-allocation-delete&id=<?= $service['id'] ?>" 
                                               onclick="return confirm('Xóa phân bổ này?')"
                                               class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Chưa có phân bổ dịch vụ</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

  </div>
</div>

<!-- Modal thêm nhân sự -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm phân bổ nhân sự</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?act=staff-assignment-store" method="post">
                <div class="modal-body">
                    <input type="hidden" name="departure_id" value="<?= $schedule['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Loại nhân sự <span class="text-danger">*</span></label>
                        <select name="staff_type" class="form-select" required>
                            <option value="guide">Hướng dẫn viên</option>
                            <option value="driver">Tài xế</option>
                            <option value="logistics">Nhân viên hậu cần</option>
                            <option value="coordinator">Điều phối viên</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chọn HDV (nếu là HDV)</label>
                        <select name="staff_id" class="form-select" id="staff_id_select">
                            <option value="">-- Chọn HDV --</option>
                            <?php foreach ($availableGuides as $guide): ?>
                                <option value="<?= $guide['id'] ?>">
                                    <?= htmlspecialchars($guide['fullname']) ?> 
                                    (<?= $guide['experience_years'] ?? 0 ?> năm kinh nghiệm)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tên nhân sự (nếu không có trong hệ thống)</label>
                        <input type="text" name="staff_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="staff_phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vai trò</label>
                        <input type="text" name="role" class="form-control" placeholder="Ví dụ: HDV chính, Tài xế phụ...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trách nhiệm</label>
                        <textarea name="responsibilities" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày bắt đầu</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày kết thúc</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="assigned">Đã phân công</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="completed">Hoàn tất</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal thêm dịch vụ -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm phân bổ dịch vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?act=service-allocation-store" method="post">
                <div class="modal-body">
                    <input type="hidden" name="departure_id" value="<?= $schedule['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Loại dịch vụ <span class="text-danger">*</span></label>
                        <select name="service_type" class="form-select" id="service_type_select" required>
                            <option value="transport">Vận chuyển</option>
                            <option value="hotel">Khách sạn</option>
                            <option value="flight">Vé máy bay</option>
                            <option value="restaurant">Nhà hàng</option>
                            <option value="attraction">Điểm tham quan</option>
                            <option value="insurance">Bảo hiểm</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tên dịch vụ <span class="text-danger">*</span></label>
                        <input type="text" name="service_name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nhà cung cấp</label>
                                <input type="text" name="provider_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Liên hệ</label>
                                <input type="text" name="provider_contact" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mã đặt chỗ</label>
                        <input type="text" name="booking_reference" class="form-control">
                    </div>

                    <!-- Chi tiết vận chuyển -->
                    <div id="transport_details" style="display:none;">
                        <hr>
                        <h6>Chi tiết vận chuyển</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Loại phương tiện</label>
                                    <select name="vehicle_type" class="form-select">
                                        <option value="car">Xe con</option>
                                        <option value="van">Xe van</option>
                                        <option value="bus">Xe bus</option>
                                        <option value="coach">Xe khách</option>
                                        <option value="plane">Máy bay</option>
                                        <option value="train">Tàu hỏa</option>
                                        <option value="boat">Tàu thuyền</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Biển số xe</label>
                                    <input type="text" name="vehicle_number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên tài xế</label>
                                    <input type="text" name="driver_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">SĐT tài xế</label>
                                    <input type="text" name="driver_phone" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sức chứa</label>
                            <input type="number" name="capacity" class="form-control" min="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Điểm đón</label>
                            <input type="text" name="pickup_location" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Điểm trả</label>
                            <input type="text" name="dropoff_location" class="form-control">
                        </div>
                    </div>

                    <!-- Chi tiết khách sạn -->
                    <div id="hotel_details" style="display:none;">
                        <hr>
                        <h6>Chi tiết khách sạn</h6>
                        <div class="mb-3">
                            <label class="form-label">Tên khách sạn</label>
                            <input type="text" name="hotel_name" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Loại phòng</label>
                                    <input type="text" name="room_type" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số phòng</label>
                                    <input type="text" name="room_number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày nhận phòng</label>
                                    <input type="date" name="check_in_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày trả phòng</label>
                                    <input type="date" name="check_out_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số phòng</label>
                                    <input type="number" name="number_of_rooms" class="form-control" min="1" value="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số đêm</label>
                                    <input type="number" name="number_of_nights" class="form-control" min="1" value="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết vé máy bay -->
                    <div id="flight_details" style="display:none;">
                        <hr>
                        <h6>Chi tiết vé máy bay</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số hiệu chuyến bay</label>
                                    <input type="text" name="flight_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hãng hàng không</label>
                                    <input type="text" name="airline" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sân bay đi</label>
                                    <input type="text" name="departure_airport" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sân bay đến</label>
                                    <input type="text" name="arrival_airport" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày giờ khởi hành</label>
                                    <input type="date" name="flight_departure_date" class="form-control mb-2">
                                    <input type="time" name="flight_departure_time" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày giờ đến</label>
                                    <input type="date" name="flight_arrival_date" class="form-control mb-2">
                                    <input type="time" name="flight_arrival_time" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hạng ghế</label>
                                    <select name="flight_class" class="form-select">
                                        <option value="economy">Phổ thông</option>
                                        <option value="business">Thương gia</option>
                                        <option value="first">Hạng nhất</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số vé</label>
                                    <input type="number" name="number_of_tickets" class="form-control" min="1" value="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày bắt đầu</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày kết thúc</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Số lượng</label>
                                <input type="number" name="quantity" class="form-control" min="1" value="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Đơn giá</label>
                                <input type="number" name="unit_price" class="form-control" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tổng giá</label>
                                <input type="number" name="total_price" class="form-control" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="pending">Chờ xác nhận</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="in_use">Đang sử dụng</option>
                            <option value="completed">Hoàn tất</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Hiển thị/ẩn chi tiết theo loại dịch vụ
document.getElementById('service_type_select').addEventListener('change', function() {
    const serviceType = this.value;
    document.getElementById('transport_details').style.display = serviceType === 'transport' ? 'block' : 'none';
    document.getElementById('hotel_details').style.display = serviceType === 'hotel' ? 'block' : 'none';
    document.getElementById('flight_details').style.display = serviceType === 'flight' ? 'block' : 'none';
});
</script>
</body>
</html>

