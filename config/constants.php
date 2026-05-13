<?php
/**
 * SupplierHub - Hằng số cấu hình
 * File này chứa các cấu hình chung cho toàn hệ thống
 */

// === Cấu hình Database ===
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'supplierhub');
define('DB_USER', 'supplierhub_user');
define('DB_PASS', 'supplierhub_pass');

// === Cấu hình ứng dụng ===
define('APP_NAME', 'SupplierHub');
define('APP_URL', 'http://localhost:8000');
define('APP_VERSION', '1.0.0');

// === Phân trang ===
define('ITEMS_PER_PAGE', 10);



// === Đường dẫn thư mục ===
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/assets/uploads');
define('VIEW_PATH', ROOT_PATH . '/views');

// === Trạng thái đơn hàng ===
define('PO_DRAFT', 'draft');
define('PO_PENDING', 'pending');
define('PO_APPROVED', 'approved');
define('PO_RECEIVED', 'received');
define('PO_CANCELLED', 'cancelled');

// Nhãn hiển thị trạng thái đơn hàng (tiếng Việt)
define('PO_STATUS_LABELS', [
    'draft'     => 'Nháp',
    'pending'   => 'Chờ duyệt',
    'approved'  => 'Đã duyệt',
    'received'  => 'Đã nhận hàng',
    'cancelled' => 'Đã hủy',
]);

// Badge CSS class theo trạng thái
define('PO_STATUS_BADGES', [
    'draft'     => 'bg-secondary',
    'pending'   => 'bg-warning text-dark',
    'approved'  => 'bg-info',
    'received'  => 'bg-success',
    'cancelled' => 'bg-danger',
]);

// Trạng thái nhà cung cấp
define('SUPPLIER_STATUS_LABELS', [
    'active'      => 'Hoạt động',
    'inactive'    => 'Ngưng hoạt động',
    'blacklisted' => 'Danh sách đen',
]);

define('SUPPLIER_STATUS_BADGES', [
    'active'      => 'bg-success',
    'inactive'    => 'bg-secondary',
    'blacklisted' => 'bg-danger',
]);
