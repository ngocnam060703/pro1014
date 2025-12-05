# Hướng dẫn tạo dữ liệu mẫu cho hệ thống HDV

## Có 2 file SQL để tạo dữ liệu mẫu:

### 1. `seed_guide_data.sql` (Đầy đủ)
- Tạo nhiều dữ liệu mẫu
- Tự động tạo tours và departures nếu chưa có
- Tạo 5 hướng dẫn viên, 8 phân công, nhật ký và sự cố

### 2. `seed_guide_data_simple.sql` (Đơn giản)
- Tạo dữ liệu mẫu cơ bản
- Phù hợp khi đã có tours và departures
- Tạo 3 hướng dẫn viên, 2 phân công, 1 nhật ký, 1 sự cố

## Cách sử dụng:

### Cách 1: Import qua phpMyAdmin
1. Mở phpMyAdmin: `http://localhost/phpmyadmin`
2. Chọn database `travel_system`
3. Click tab "SQL"
4. Copy nội dung file SQL
5. Paste vào và click "Go"

### Cách 2: Chạy qua MySQL Command Line
```bash
mysql -u root -p travel_system < database/seed_guide_data_simple.sql
```

### Cách 3: Chạy từng câu lệnh trong MySQL
Mở MySQL và chạy từng phần của file SQL

## Thông tin đăng nhập mẫu:

Sau khi chạy script, bạn có thể đăng nhập với:

| Tài khoản | Mật khẩu | Họ tên |
|-----------|----------|--------|
| hdv001 | password | Nguyễn Văn An |
| hdv002 | password | Trần Thị Bình |
| hdv003 | password | Lê Văn Cường |

**Lưu ý:** Password mặc định là `password` (đã được hash bằng bcrypt)

## Dữ liệu sẽ được tạo:

- ✅ **guides**: 3-5 hướng dẫn viên
- ✅ **guide_assign**: 2-8 phân công tour
- ✅ **guide_journal**: 1-3 nhật ký
- ✅ **guide_incidents**: 1-3 báo cáo sự cố

## Lưu ý:

- Script sẽ không tạo dữ liệu trùng lặp (sử dụng ON DUPLICATE KEY hoặc WHERE NOT EXISTS)
- Nếu tours/departures chưa có, một số dữ liệu có thể không được tạo
- Bạn có thể chạy script nhiều lần mà không lo trùng dữ liệu

