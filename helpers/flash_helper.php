<?php
/**
 * SupplierHub - Helper Flash Messages
 * Hiển thị thông báo thành công/lỗi sau khi redirect
 */

/**
 * Đặt flash message vào session
 * @param string $type     Loại: success, error, warning, info
 * @param string $message  Nội dung thông báo
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Hiển thị flash message (nếu có) và xóa khỏi session
 * Dùng Bootstrap Alert component
 * @return string  HTML alert hoặc chuỗi rỗng
 */
function showFlash() {
    if (empty($_SESSION['flash'])) return '';

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    // Map type sang Bootstrap alert class
    $alertClass = [
        'success' => 'alert-success',
        'error'   => 'alert-danger',
        'warning' => 'alert-warning',
        'info'    => 'alert-info',
    ];

    $class = $alertClass[$flash['type']] ?? 'alert-info';
    $icon = [
        'success' => 'fa-check-circle',
        'error'   => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info'    => 'fa-info-circle',
    ];
    $iconClass = $icon[$flash['type']] ?? 'fa-info-circle';

    return '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">
        <i class="fas ' . $iconClass . ' me-2"></i>'
        . htmlspecialchars($flash['message'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}
