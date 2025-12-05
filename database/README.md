# Hướng dẫn tạo bảng database

## Vấn đề
Nếu bạn gặp lỗi: `Table 'travel_system.guide_journal' doesn't exist`

## Giải pháp

### Cách 1: Chạy script PHP (Khuyến nghị)
1. Mở terminal/command prompt
2. Chạy lệnh:
```bash
php database/create_tables.php
```

Hoặc nếu dùng Laragon, chạy:
```bash
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe database\create_tables.php
```

### Cách 2: Chạy SQL trực tiếp trong phpMyAdmin
1. Mở phpMyAdmin (http://localhost/phpmyadmin)
2. Chọn database `travel_system`
3. Vào tab "SQL"
4. Copy và paste nội dung file `create_guide_journal.sql`
5. Click "Go" để thực thi

### Cách 3: Chạy SQL trong MySQL Command Line
```sql
USE travel_system;
SOURCE database/create_guide_journal.sql;
```

## Các bảng sẽ được tạo:
- `guide_journal` - Lưu nhật ký của hướng dẫn viên
- `guide_incidents` - Lưu báo cáo sự cố
- `guide_assign` - Lưu phân công tour cho HDV (nếu chưa có)

