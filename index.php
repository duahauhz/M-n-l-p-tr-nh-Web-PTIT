<?php
/**
 * MVC kết hợp Front Controller
 * SupplierHub - Entry Point & Router
 * Mọi request đều đi qua file này
 * URL format: index.php?page=suppliers&action=create
 */

// Đảm bảo PHP xuất đúng UTF-8 cho tiếng Việt
header('Content-Type: text/html; charset=UTF-8');

// Bắt đầu session
session_start();

// Load config và helpers
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/flash_helper.php';
require_once __DIR__ . '/helpers/auth_helper.php';
require_once __DIR__ . '/helpers/csrf_helper.php';
require_once __DIR__ . '/helpers/format_helper.php';

// Lấy tham số page và action từ URL
$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

// Nếu đã đăng nhập mà vào trang login → chuyển đến dashboard
if ($page === 'login' && isLoggedIn()) {
    header('Location: index.php?page=dashboard');
    exit;
}

// === ROUTING TABLE ===
// Mỗi page tương ứng với 1 controller
switch ($page) {

    // --- Trang đăng nhập ---
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;

    // --- Đăng xuất ---
    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    // --- Dashboard ---
    case 'dashboard':
        requireLogin();
        require_once __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    // --- Quản lý nhà cung cấp ---
    case 'suppliers':
        requireLogin();
        require_once __DIR__ . '/controllers/SupplierController.php';
        $controller = new SupplierController();
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                $controller->store();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'update':
                $controller->update();
                break;
            case 'delete':
                $controller->delete();
                break;
            case 'show':
                $controller->show();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    // --- Quản lý danh mục ---
    case 'categories':
        requireLogin();
        require_once __DIR__ . '/controllers/CategoryController.php';
        $controller = new CategoryController();
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                $controller->store();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'update':
                $controller->update();
                break;
            case 'delete':
                $controller->delete();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    // --- Quản lý sản phẩm ---
    case 'products':
        requireLogin();
        require_once __DIR__ . '/controllers/ProductController.php';
        $controller = new ProductController();
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                $controller->store();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'update':
                $controller->update();
                break;
            case 'delete':
                $controller->delete();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    // --- Quản lý đơn hàng mua ---
    case 'purchase_orders':
        requireLogin();
        require_once __DIR__ . '/controllers/PurchaseOrderController.php';
        $controller = new PurchaseOrderController();
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                $controller->store();
                break;
            case 'show':
                $controller->show();
                break;
            case 'submit':
                $controller->submit();
                break;
            case 'approve':
                $controller->approve();
                break;
            case 'reject':
                $controller->reject();
                break;
            case 'receive':
                $controller->receive();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    // --- Tồn kho ---
    case 'inventory':
        requireLogin();
        require_once __DIR__ . '/controllers/InventoryController.php';
        $controller = new InventoryController();
        $controller->index();
        break;

    // --- Đánh giá nhà cung cấp ---
    case 'reviews':
        requireLogin();
        require_once __DIR__ . '/controllers/ReviewController.php';
        $controller = new ReviewController();
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                $controller->store();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    // --- Báo cáo ---
    case 'reports':
        requireLogin();
        require_once __DIR__ . '/controllers/ReportController.php';
        $controller = new ReportController();
        switch ($action) {
            case 'export_suppliers':
                $controller->exportSuppliers();
                break;
            case 'export_inventory':
                $controller->exportInventory();
                break;
            case 'print_order':
                $controller->printOrder();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    // --- Audit Log ---
    case 'audit_logs':
        requireLogin();
        require_once __DIR__ . '/controllers/AuditLogController.php';
        $controller = new AuditLogController();
        $controller->index();
        break;

    // --- Trang không tìm thấy ---
    default:
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
        } else {
            header('Location: index.php?page=login');
        }
        exit;
}
