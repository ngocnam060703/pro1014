<?php
require_once __DIR__ . "/../commons/function.php";

class BookingModel {

    // Lấy tất cả booking
    public function all() {
        $sql = "SELECT b.*, t.title AS tour_title
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.id
                ORDER BY b.created_at DESC";
        return pdo_query($sql);
    }

    // Lấy booking theo ID
    public function find($id) {
        $sql = "SELECT b.*, t.title AS tour_title
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.id
                WHERE b.id = ?";
        return pdo_query_one($sql, $id);
    }

    // Lấy danh sách bookings theo departure_id
    public function getByDepartureId($departureId) {
        $sql = "SELECT b.*, t.title AS tour_title
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.id
                WHERE b.departure_id = ? AND b.status != 'cancelled'
                ORDER BY b.created_at ASC";
        return pdo_query($sql, $departureId);
    }

    // Cập nhật trạng thái (cũ - giữ lại để tương thích)
    public function updateStatus($id, $status) {
        return $this->changeStatus($id, $status, null, null);
    }

    // Thay đổi trạng thái với lưu lịch sử
    public function changeStatus($booking_id, $new_status, $new_payment_status = null, $change_reason = null, $changed_by = null) {
        // Lấy thông tin booking hiện tại
        $booking = $this->find($booking_id);
        if (!$booking) {
            return ['success' => false, 'message' => 'Booking không tồn tại'];
        }
        
        $old_status = $booking['status'] ?? 'pending';
        $old_payment_status = $booking['payment_status'] ?? 'pending';
        
        // VALIDATION: Nếu đã thanh toán (paid) hoặc đã cọc (partial) → KHÔNG ĐƯỢC HỦY BOOKING
        if ($new_status == 'cancelled' && $old_status != 'cancelled') {
            if ($old_payment_status == 'paid' || $old_payment_status == 'partial') {
                return [
                    'success' => false, 
                    'message' => 'Không thể hủy booking vì khách đã thanh toán hoặc đã đặt cọc. Vui lòng hoàn tiền trước khi hủy.'
                ];
            }
        }
        
        // VALIDATION: Nếu tour đã kết thúc → KHÔNG ĐƯỢC ghi nhận thanh toán mới
        if ($new_payment_status == 'paid' || $new_payment_status == 'partial') {
            // Kiểm tra trạng thái departure/tour
            if (!empty($booking['departure_id'])) {
                $departure_sql = "SELECT status, end_date FROM departures WHERE id = ?";
                $departure = pdo_query_one($departure_sql, $booking['departure_id']);
                
                if ($departure) {
                    // Nếu tour đã hoàn thành hoặc đã kết thúc
                    if ($departure['status'] == 'completed' || 
                        (!empty($departure['end_date']) && strtotime($departure['end_date']) < time())) {
                        return [
                            'success' => false,
                            'message' => 'Không thể ghi nhận thanh toán vì tour đã kết thúc.'
                        ];
                    }
                }
            }
        }
        
        // VALIDATION: Không được hoàn tiền vượt quá số tiền khách đã thanh toán
        if ($new_payment_status == 'refunded') {
            $total_paid = (float)($booking['deposit_amount'] ?? 0);
            // Nếu đã thanh toán đầy đủ, tổng tiền đã thanh toán = total_price
            if ($old_payment_status == 'paid') {
                $total_paid = (float)($booking['total_price'] ?? 0);
            }
            
            // Kiểm tra nếu có số tiền hoàn (có thể từ change_reason hoặc refund_amount)
            // Nếu không có thông tin cụ thể, chỉ cho phép hoàn nếu đã thanh toán
            if ($old_payment_status == 'pending') {
                return [
                    'success' => false,
                    'message' => 'Không thể hoàn tiền vì khách chưa thanh toán.'
                ];
            }
        }
        
        // Nếu trạng thái không thay đổi thì không làm gì
        if ($old_status == $new_status && 
            ($new_payment_status === null || $old_payment_status == $new_payment_status)) {
            return ['success' => true, 'message' => 'Trạng thái không thay đổi'];
        }
        
        // Cập nhật trạng thái booking
        $update_fields = ['status = ?'];
        $params = [$new_status];
        
        if ($new_payment_status !== null) {
            $update_fields[] = 'payment_status = ?';
            $params[] = $new_payment_status;
        }
        
        // Cập nhật thời gian xác nhận/hủy nếu cần
        if ($new_status == 'completed' && $old_status != 'completed') {
            $update_fields[] = 'confirmed_at = NOW()';
        }
        
        if ($new_status == 'cancelled' && $old_status != 'cancelled') {
            $update_fields[] = 'cancelled_at = NOW()';
            if ($change_reason) {
                $update_fields[] = 'cancellation_reason = ?';
                $params[] = $change_reason;
            }
        }
        
        $params[] = $booking_id;
        $sql = "UPDATE bookings SET " . implode(', ', $update_fields) . " WHERE id = ?";
        $result = pdo_execute($sql, ...$params);
        
        if ($result) {
            // Lưu lịch sử thay đổi
            require_once __DIR__ . "/BookingStatusHistoryModel.php";
            $historyModel = new BookingStatusHistoryModel();
            
            $historyModel->addHistory([
                'booking_id' => $booking_id,
                'old_status' => $old_status,
                'new_status' => $new_status,
                'old_payment_status' => $old_payment_status,
                'new_payment_status' => $new_payment_status ?? $old_payment_status,
                'changed_by' => $changed_by,
                'change_reason' => $change_reason,
                'notes' => null
            ]);
            
            return [
                'success' => true,
                'message' => 'Đã cập nhật trạng thái thành công!',
                'old_status' => $old_status,
                'new_status' => $new_status
            ];
        }
        
        return ['success' => false, 'message' => 'Không thể cập nhật trạng thái'];
    }

    // Xóa booking
    public function delete($id) {
        $sql = "DELETE FROM bookings WHERE id = ?";
        return pdo_execute($sql, $id);
    }

    // Lấy số đơn đặt hôm nay
    public function getOrdersCountToday() {
        $sql = "SELECT COUNT(*) as total 
                FROM bookings 
                WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'";
        $row = pdo_query_one($sql);
        return $row['total'] ?? 0;
    }

    // Lấy doanh thu hôm nay
    public function getRevenueToday() {
        $sql = "SELECT IFNULL(SUM(total_price), 0) as revenue 
                FROM bookings 
                WHERE DATE(created_at) = CURDATE() 
                AND status != 'cancelled'";
        $row = pdo_query_one($sql);
        return $row['revenue'] ?? 0;
    }

    // Lấy doanh thu 7 ngày gần đây
    public function getRevenueLast7Days() {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    IFNULL(SUM(total_price), 0) as revenue
                FROM bookings
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                AND status != 'cancelled'
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        return pdo_query($sql);
    }

    // Lấy số đơn theo ngày trong 7 ngày gần đây
    public function getOrdersCountLast7Days() {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as count
                FROM bookings
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                AND status != 'cancelled'
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        return pdo_query($sql);
    }

    // Lấy top tour bán chạy
    public function getTopTours($limit = 5) {
        // LIMIT không thể dùng placeholder, phải nối trực tiếp (đã validate là số nguyên)
        $limit = (int)$limit;
        $limit = max(1, min(100, $limit)); // Giới hạn từ 1 đến 100
        
        $sql = "SELECT 
                    t.id,
                    t.title,
                    COUNT(b.id) as booking_count,
                    SUM(b.total_price) as total_revenue
                FROM bookings b
                INNER JOIN tours t ON b.tour_id = t.id
                WHERE b.status != 'cancelled'
                GROUP BY t.id, t.title
                ORDER BY booking_count DESC
                LIMIT " . $limit;
        return pdo_query($sql);
    }

    // Lấy phân bố trạng thái booking
    public function getBookingStatusDistribution() {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM bookings
                GROUP BY status";
        return pdo_query($sql);
    }

    // Kiểm tra chỗ trống cho tour và ngày khởi hành
    public function checkAvailability($tour_id, $departure_id = null, $departure_date = null, $num_people = 1) {
        // Lấy thông tin tour
        $tour_sql = "SELECT slots FROM tours WHERE id = ?";
        $tour = pdo_query_one($tour_sql, $tour_id);
        
        if (!$tour) {
            return ['available' => false, 'message' => 'Tour không tồn tại'];
        }
        
        $total_slots = (int)$tour['slots'];
        
        // Đếm số chỗ đã đặt
        // Kiểm tra xem cột departure_date có tồn tại không
        $has_departure_date = false;
        try {
            $check_sql = "SHOW COLUMNS FROM bookings LIKE 'departure_date'";
            $check = pdo_query($check_sql);
            $has_departure_date = !empty($check);
        } catch (Exception $e) {
            // Bỏ qua nếu có lỗi
        }
        
        $booked_sql = "SELECT COALESCE(SUM(num_people), 0) as booked 
                       FROM bookings 
                       WHERE tour_id = ? 
                       AND status IN ('pending', 'confirmed')";
        $params = [$tour_id];
        
        if ($departure_id) {
            $booked_sql .= " AND departure_id = ?";
            $params[] = $departure_id;
        }
        
        if ($departure_date && $has_departure_date) {
            $booked_sql .= " AND departure_date = ?";
            $params[] = $departure_date;
        }
        
        $booked = pdo_query_one($booked_sql, ...$params);
        $booked_slots = (int)($booked['booked'] ?? 0);
        $available_slots = $total_slots - $booked_slots;
        
        if ($available_slots >= $num_people) {
            return [
                'available' => true,
                'total_slots' => $total_slots,
                'booked_slots' => $booked_slots,
                'available_slots' => $available_slots,
                'message' => "Còn {$available_slots} chỗ trống"
            ];
        } else {
            return [
                'available' => false,
                'total_slots' => $total_slots,
                'booked_slots' => $booked_slots,
                'available_slots' => $available_slots,
                'message' => "Chỉ còn {$available_slots} chỗ trống, không đủ cho {$num_people} người"
            ];
        }
    }

    // Tính giá booking dựa trên số lượng và loại khách
    public function calculatePrice($tour_id, $num_adults = 0, $num_children = 0, $num_infants = 0, $departure_date = null) {
        $adult_price = 0;
        $child_price = 0;
        $infant_price = 0;
        
        // Kiểm tra xem có TourPricingModel không
        $pricingModelPath = __DIR__ . "/TourPricingModel.php";
        if (file_exists($pricingModelPath)) {
            require_once $pricingModelPath;
            $pricingModel = new TourPricingModel();
            
            // Lấy giá từ bảng tour_pricing nếu có
            $pricings = $pricingModel->getActivePricingByDate($tour_id, $departure_date);
            
            // Tìm giá cho từng loại
            foreach ($pricings as $pricing) {
                if ($pricing['price_type'] == 'adult') {
                    $adult_price = (float)$pricing['price'];
                } elseif ($pricing['price_type'] == 'child') {
                    $child_price = (float)$pricing['price'];
                } elseif ($pricing['price_type'] == 'infant') {
                    $infant_price = (float)$pricing['price'];
                }
            }
        }
        
        // Nếu không có trong tour_pricing, lấy từ tours
        if ($adult_price == 0) {
            $tour_sql = "SELECT price FROM tours WHERE id = ?";
            $tour = pdo_query_one($tour_sql, $tour_id);
            $adult_price = (float)($tour['price'] ?? 0);
            $child_price = $adult_price * 0.7; // Trẻ em 70% giá người lớn
            $infant_price = $adult_price * 0.1; // Trẻ sơ sinh 10% giá người lớn
        }
        
        $total = ($adult_price * $num_adults) + 
                 ($child_price * $num_children) + 
                 ($infant_price * $num_infants);
        
        return [
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,
            'total' => $total,
            'num_adults' => $num_adults,
            'num_children' => $num_children,
            'num_infants' => $num_infants
        ];
    }

    // Tạo booking mới
    public function createBooking($data) {
        // VALIDATION: Không được tạo booking mới nếu tour đã đầy chỗ và đã khóa thanh toán
        // Kiểm tra chỗ trống
        $availability = $this->checkAvailability(
            $data['tour_id'],
            $data['departure_id'] ?? null,
            $data['departure_date'] ?? null,
            $data['num_people']
        );
        
        if (!$availability['available']) {
            // Kiểm tra xem departure có đang khóa thanh toán không
            if (!empty($data['departure_id'])) {
                $departure_sql = "SELECT status, seats_booked, total_seats FROM departures WHERE id = ?";
                $departure = pdo_query_one($departure_sql, $data['departure_id']);
                
                if ($departure && 
                    ($departure['seats_booked'] >= $departure['total_seats']) &&
                    ($departure['status'] == 'completed' || $departure['status'] == 'cancelled')) {
                    return [
                        'success' => false, 
                        'message' => 'Không thể tạo booking vì tour đã đầy chỗ và đã khóa thanh toán.'
                    ];
                }
            }
            return ['success' => false, 'message' => $availability['message']];
        }
        
        // Kiểm tra tour đã kết thúc chưa
        if (!empty($data['departure_id'])) {
            $departure_sql = "SELECT status, end_date, departure_time FROM departures WHERE id = ?";
            $departure = pdo_query_one($departure_sql, $data['departure_id']);
            
            if ($departure) {
                // Nếu tour đã hoàn thành
                if ($departure['status'] == 'completed') {
                    return [
                        'success' => false,
                        'message' => 'Không thể tạo booking vì tour đã hoàn thành.'
                    ];
                }
                
                // Nếu tour đã kết thúc (end_date đã qua)
                if (!empty($departure['end_date']) && strtotime($departure['end_date']) < time()) {
                    return [
                        'success' => false,
                        'message' => 'Không thể tạo booking vì tour đã kết thúc.'
                    ];
                }
            }
        }
        
        // Tính giá
        $pricing = $this->calculatePrice(
            $data['tour_id'],
            $data['num_adults'] ?? 0,
            $data['num_children'] ?? 0,
            $data['num_infants'] ?? 0,
            $data['departure_date'] ?? null
        );
        
        // Tạo mã booking
        $booking_code = $this->generateBookingCode();
        
        // Tính tổng tiền sau giảm giá
        $total_price = $pricing['total'];
        $discount_amount = 0;
        if (isset($data['discount_percentage']) && $data['discount_percentage'] > 0) {
            $discount_amount = $total_price * ($data['discount_percentage'] / 100);
        } elseif (isset($data['discount_amount']) && $data['discount_amount'] > 0) {
            $discount_amount = (float)$data['discount_amount'];
        }
        $final_price = $total_price - $discount_amount;
        
        // Insert booking
        try {
            $sql = "INSERT INTO bookings (
                booking_code, tour_id, departure_id, booking_type,
                customer_name, customer_email, customer_phone, customer_address,
                company_name, tax_code,
                num_adults, num_children, num_infants, num_people,
                booking_date, departure_date,
                special_requests, notes,
                base_price, adult_price, child_price, infant_price,
                discount_amount, discount_percentage, total_price,
                deposit_amount, remaining_amount,
                payment_status, status, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $result = pdo_execute(
                $sql,
                $booking_code,
                $data['tour_id'],
                $data['departure_id'] ?? null,
                $data['booking_type'] ?? 'individual',
                $data['customer_name'],
                $data['customer_email'],
                $data['customer_phone'],
                $data['customer_address'] ?? null,
                $data['company_name'] ?? null,
                $data['tax_code'] ?? null,
                $data['num_adults'] ?? 0,
                $data['num_children'] ?? 0,
                $data['num_infants'] ?? 0,
                $data['num_people'],
                $data['booking_date'],
                $data['departure_date'] ?? null,
                $data['special_requests'] ?? null,
                $data['notes'] ?? null,
                $pricing['total'],
                $pricing['adult_price'],
                $pricing['child_price'],
                $pricing['infant_price'],
                $discount_amount,
                $data['discount_percentage'] ?? 0,
                $final_price,
                $data['deposit_amount'] ?? 0,
                $final_price - ($data['deposit_amount'] ?? 0),
                $data['payment_status'] ?? 'pending',
                $data['status'] ?? 'pending',
                $data['created_by'] ?? null
            );
            
            if ($result) {
                $booking_id = pdo_last_insert_id();
                
                // Lưu thông tin chi tiết khách nếu là đoàn
                if (isset($data['guests']) && is_array($data['guests']) && count($data['guests']) > 0) {
                    $this->saveBookingGuests($booking_id, $data['guests']);
                }
                
                return [
                    'success' => true,
                    'booking_id' => $booking_id,
                    'booking_code' => $booking_code,
                    'message' => 'Booking đã được tạo thành công!'
                ];
            }
            
            return ['success' => false, 'message' => 'Không thể tạo booking. Vui lòng thử lại.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    // Lưu thông tin chi tiết khách
    private function saveBookingGuests($booking_id, $guests) {
        $sql = "INSERT INTO booking_guests (booking_id, guest_type, full_name, date_of_birth, gender, id_card, phone, email, special_notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        foreach ($guests as $guest) {
            pdo_execute(
                $sql,
                $booking_id,
                $guest['guest_type'] ?? 'adult',
                $guest['full_name'],
                $guest['date_of_birth'] ?? null,
                $guest['gender'] ?? null,
                $guest['id_card'] ?? null,
                $guest['phone'] ?? null,
                $guest['email'] ?? null,
                $guest['special_notes'] ?? null
            );
        }
    }

    // Tạo mã booking tự động
    private function generateBookingCode() {
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $code = "BK{$date}-{$random}";
        
        // Kiểm tra trùng lặp
        $check_sql = "SELECT COUNT(*) as count FROM bookings WHERE booking_code = ?";
        $check = pdo_query_one($check_sql, $code);
        
        if ($check['count'] > 0) {
            // Nếu trùng, tạo lại
            return $this->generateBookingCode();
        }
        
        return $code;
    }

    // Lấy danh sách khách của booking
    public function getBookingGuests($booking_id) {
        $sql = "SELECT * FROM booking_guests WHERE booking_id = ? ORDER BY id ASC";
        return pdo_query($sql, $booking_id);
    }
}
