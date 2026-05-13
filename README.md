---
{
  "id": "file_77kblvbr",
  "filetype": "document",
  "filename": "README",
  "created_at": "2026-04-18T01:33:20.504Z",
  "updated_at": "2026-04-18T01:33:20.504Z",
  "meta": {
    "location": "/",
    "tags": [],
    "categories": [],
    "description": "",
    "source": "markdown"
  }
}
---
# 🏪 SupplierHub - Hệ Thống Quản Lý Nhà Cung Cấp

Bài cuối khóa Lập trình Web - PTIT

## 🚀 Cài đặt & Chạy

### Yêu cầu
- **Docker Desktop** (cho MySQL)
- **PHP >= 7.4** (cho built-in server)
- **VS Code** + MySQL Extension

### Bước 1: Khởi động MySQL
```bash
docker-compose up -d
```
Chờ 10-15 giây để MySQL khởi tạo database, schema, seed data tự động.

### Bước 2: Chạy PHP Server
```bash
php -S localhost:8000
```

### Bước 3: Truy cập
Mở trình duyệt: **http://localhost:8000**

### Tài khoản demo
| Username | Mật khẩu | Vai trò |
|----------|----------|---------|
| admin    | 123456   | Admin   |
| manager1 | 123456   | Manager |
| staff1   | 123456   | Staff   |
| staff2   | 123456   | Staff   |

## 🗄️ Kết nối MySQL Extension (VS Code)
- **Host:** localhost
- **Port:** 3306
- **User:** supplierhub_user
- **Password:** supplierhub_pass
- **Database:** supplierhub

## 📋 Tính năng chính
1. **Đăng nhập/Phân quyền** - RBAC (Admin/Manager/Staff)
2. **Quản lý NCC** - CRUD + tìm kiếm/lọc/phân trang
3. **Quản lý danh mục** - Hỗ trợ phân cấp cha-con
4. **Quản lý sản phẩm** - Upload ảnh, liên kết NCC & danh mục
5. **Đơn hàng mua** - Workflow: Nháp → Chờ duyệt → Duyệt → Nhận hàng
6. **Tồn kho** - Tự động cập nhật, cảnh báo sắp hết
7. **Đánh giá NCC** - 4 tiêu chí (chất lượng, giao hàng, giá, dịch vụ)
8. **Dashboard** - Thống kê, biểu đồ Chart.js
9. **Audit Log** - Ghi lại mọi thao tác (Admin)
10. **Báo cáo** - Xuất CSV, in đơn hàng

## 🛡️ Bảo mật
- PDO Prepared Statements (chống SQL Injection)
- htmlspecialchars() (chống XSS)
- CSRF Token cho mọi form POST
- password_hash() / password_verify()

## 🏗️ Kiến trúc
```
MVC Pattern (thuần PHP, không framework)
├── controllers/   ← Xử lý logic
├── models/        ← Tương tác database
├── views/         ← Giao diện Bootstrap 5
├── helpers/       ← Hàm tiện ích
├── config/        ← Cấu hình
├── assets/        ← CSS, JS, uploads
└── sql/           ← Schema, seed, stored procedures
```

## 🎨 Công nghệ
- **Frontend:** Bootstrap 5.3, Font Awesome 6, Chart.js
- **Backend:** PHP thuần (MVC), PDO
- **Database:** MySQL 8.0, Stored Procedures, Transactions
- **Deploy:** Docker Compose
