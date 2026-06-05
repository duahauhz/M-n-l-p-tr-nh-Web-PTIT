# Hướng dẫn setup dự án trên máy mới

Tài liệu này hướng dẫn từ lúc cài Docker trên máy mới cho tới khi chạy được dự án `SupplierHub`.

## 1. Yêu cầu cài đặt

Trên máy mới, cần cài sẵn:

- `Docker Desktop`
- `PHP >= 7.4`
- Trình duyệt web
- Nên có thêm: `Git`, `VS Code`

## 2. Clone source code

Nếu chưa có source code, chạy:

```bash
git clone <repo-url>
cd BT_3_WEB_PTIT
```

Nếu đã có source code sẵn, chỉ cần mở thư mục dự án:

```bash
cd BT_3_WEB_PTIT
```

## 3. Khởi động Docker trên máy mới

Mở `Docker Desktop` và chờ Docker chạy hoàn tất.

Kiểm tra Docker hoạt động:

```bash
docker --version
docker compose version
```

Nếu máy dùng bản Compose cũ, có thể dùng `docker-compose --version` để kiểm tra thêm.

## 4. Chạy MySQL bằng Docker

Trong thư mục gốc dự án, chạy:

```bash
docker compose up -d
```

Nếu máy bạn chỉ hỗ trợ cú pháp cũ thì dùng:

```bash
docker-compose up -d
```

Sau khi chạy, Docker sẽ tạo container MySQL với các thông tin:

- Container: `supplierhub_mysql`
- Host: `localhost`
- Port: `3306`
- Database: `supplierhub`
- User: `supplierhub_user`
- Password: `supplierhub_pass`
- Root password: `root123`

## 5. Dữ liệu database được import như thế nào

Khi container MySQL khởi tạo lần đầu, Docker sẽ tự động import các file SQL sau:

- `sql/schema.sql`
- `sql/seed.sql`
- `sql/stored_procedures.sql`

Lưu ý:

- Việc import tự động chỉ chắc chắn xảy ra ở lần khởi tạo volume dữ liệu đầu tiên.
- Nếu trước đó bạn đã chạy container rồi, dữ liệu cũ sẽ được giữ lại trong volume `mysql_data`.

Nếu muốn reset database từ đầu trên máy mới hoặc làm sạch dữ liệu cũ:

```bash
docker compose down -v
docker compose up -d
```

Hoặc với cú pháp cũ:

```bash
docker-compose down -v
docker-compose up -d
```

Sau đó chờ khoảng `10-15 giây` để MySQL khởi tạo xong.

## 6. Kiểm tra container MySQL

Có thể kiểm tra nhanh bằng lệnh:

```bash
docker ps
```

Bạn nên thấy container `supplierhub_mysql` đang ở trạng thái chạy.

Nếu cần xem log:

```bash
docker logs supplierhub_mysql
```

## 7. Cấu hình ứng dụng

Dự án hiện đang cấu hình sẵn để kết nối tới MySQL local Docker:

- `DB_HOST=localhost`
- `DB_PORT=3306`
- `DB_NAME=supplierhub`
- `DB_USER=supplierhub_user`
- `DB_PASS=supplierhub_pass`
- `APP_URL=http://localhost:8000`

Vì vậy, trên máy mới thường không cần sửa thêm file cấu hình.

## 8. Khởi động dự án PHP

Trong thư mục gốc dự án, chạy:

```bash
php -S localhost:8000
```

Sau đó mở trình duyệt và truy cập:

[http://localhost:8000](http://localhost:8000)

## 9. Tài khoản đăng nhập demo

Có thể dùng các tài khoản có sẵn:

- `admin / 123456`
- `manager1 / 123456`
- `staff1 / 123456`
- `staff2 / 123456`

## 10. Kết nối MySQL bằng VS Code hoặc công cụ DB

Thông tin kết nối:

- Host: `localhost`
- Port: `3306`
- User: `supplierhub_user`
- Password: `supplierhub_pass`
- Database: `supplierhub`

## 11. Quy trình chạy nhanh mỗi lần mở máy

Sau khi đã setup xong, mỗi lần mở máy chỉ cần:

### Bước 1: mở Docker Desktop
Chờ Docker chạy xong.

### Bước 2: vào thư mục dự án

```bash
cd BT_3_WEB_PTIT
```

### Bước 3: chạy database

```bash
docker compose up -d
```

### Bước 4: chạy web PHP

```bash
php -S localhost:8000
```

### Bước 5: mở trình duyệt

Truy cập:

[http://localhost:8000](http://localhost:8000)

## 12. Một số lỗi thường gặp

### Docker chưa chạy
Nếu chạy `docker compose up -d` bị lỗi, hãy kiểm tra lại `Docker Desktop` đã bật chưa.

### Port 3306 đã bị chiếm
Nếu MySQL không lên được, có thể máy đang có MySQL khác dùng cổng `3306`.

Cách xử lý:
- Tắt MySQL đang chạy trên máy
- Hoặc đổi port trong `docker-compose.yml`

### Port 8000 đã bị chiếm
Nếu PHP báo không bind được cổng `8000`, có thể đổi sang cổng khác:

```bash
php -S localhost:8080
```

Khi đó truy cập:

[http://localhost:8080](http://localhost:8080)

### Database không tự import
Nếu dữ liệu không được tạo đúng như mong muốn, reset lại volume:

```bash
docker compose down -v
docker compose up -d
```

## 13. Lệnh tổng hợp dễ dùng

### Chạy lần đầu hoặc chạy lại từ đầu

```bash
docker compose down -v
docker compose up -d
php -S localhost:8000
```

### Chạy bình thường

```bash
docker compose up -d
php -S localhost:8000
```

---

Nếu muốn, tôi có thể viết tiếp cho bạn một file `SETUP_WINDOWS.md` ngắn gọn hơn theo kiểu checklist để chỉ cần làm tuần tự từng bước là chạy được ngay.