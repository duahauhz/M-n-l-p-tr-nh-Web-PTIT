<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SupplierHub - Hệ thống quản lý nhà cung cấp toàn diện">
    <title><?= e($pageTitle ?? 'Dashboard') ?> | <?= APP_NAME ?></title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h4><i class="fas fa-store"></i> SupplierHub</h4>
        <small>Quản Lý Nhà Cung Cấp</small>
    </div>

    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li>
            <a href="index.php?page=dashboard" class="<?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>

        <div class="sidebar-heading">Quản lý</div>

        <!-- Nhà cung cấp -->
        <li>
            <a href="index.php?page=suppliers" class="<?= ($page ?? '') === 'suppliers' ? 'active' : '' ?>">
                <i class="fas fa-truck"></i> Nhà cung cấp
            </a>
        </li>

        <!-- Danh mục -->
        <li>
            <a href="index.php?page=categories" class="<?= ($page ?? '') === 'categories' ? 'active' : '' ?>">
                <i class="fas fa-folder-open"></i> Danh mục
            </a>
        </li>

        <!-- Sản phẩm -->
        <li>
            <a href="index.php?page=products" class="<?= ($page ?? '') === 'products' ? 'active' : '' ?>">
                <i class="fas fa-box-open"></i> Sản phẩm
            </a>
        </li>

        <div class="sidebar-heading">Nghiệp vụ</div>

        <!-- Đơn hàng mua -->
        <li>
            <a href="index.php?page=purchase_orders" class="<?= ($page ?? '') === 'purchase_orders' ? 'active' : '' ?>">
                <i class="fas fa-file-invoice"></i> Đơn hàng mua
            </a>
        </li>

        <!-- Tồn kho -->
        <li>
            <a href="index.php?page=inventory" class="<?= ($page ?? '') === 'inventory' ? 'active' : '' ?>">
                <i class="fas fa-warehouse"></i> Tồn kho
            </a>
        </li>

        <!-- Đánh giá NCC -->
        <li>
            <a href="index.php?page=reviews" class="<?= ($page ?? '') === 'reviews' ? 'active' : '' ?>">
                <i class="fas fa-star-half-alt"></i> Đánh giá NCC
            </a>
        </li>

        <div class="sidebar-heading">Báo cáo</div>

        <!-- Báo cáo -->
        <li>
            <a href="index.php?page=reports" class="<?= ($page ?? '') === 'reports' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i> Báo cáo
            </a>
        </li>

        <div class="sidebar-heading">Hệ thống</div>

        <!-- Audit Log -->
        <li>
            <a href="index.php?page=audit_logs" class="<?= ($page ?? '') === 'audit_logs' ? 'active' : '' ?>">
                <i class="fas fa-history"></i> Nhật ký hoạt động
            </a>
        </li>
    </ul>
</nav>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-secondary d-md-none me-3 sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="topbar-title"><?= e($pageTitle ?? 'Dashboard') ?></h5>
        </div>
        <div class="topbar-user">
            <div class="text-end">
                <div class="user-name"><?= e(currentUser('full_name')) ?></div>
            </div>
            <a href="index.php?page=logout" class="btn btn-sm btn-outline-danger ms-2" title="Đăng xuất">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Flash Messages -->
        <?= showFlash() ?>
