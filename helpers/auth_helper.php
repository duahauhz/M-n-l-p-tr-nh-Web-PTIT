<?php
/**
 * SupplierHub - Helper xác thực người dùng
 * Chỉ hỗ trợ admin đơn giản (không còn phân quyền đa role)
 */

/**
 * Kiểm tra user đã đăng nhập chưa
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Lấy thông tin user hiện tại từ session
 * @param string $key  Tên trường cần lấy (id, username, full_name, email...)
 * @return mixed|null
 */
function currentUser($key = null) {
    if (!isLoggedIn()) return null;

    if ($key) {
        return $_SESSION['user_' . $key] ?? null;
    }

    return [
        'id'        => $_SESSION['user_id'],
        'username'  => $_SESSION['user_username'],
        'full_name' => $_SESSION['user_full_name'],
        'email'     => $_SESSION['user_email'],
    ];
}

/**
 * Yêu cầu đăng nhập - chuyển hướng nếu chưa login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('error', 'Vui lòng đăng nhập để tiếp tục.');
        header('Location: ' . APP_URL . '/index.php?page=login');
        exit;
    }
}

/**
 * Đặt thông tin user vào session sau khi đăng nhập thành công
 * @param array $user  Dữ liệu user từ database
 */
function loginUser($user) {
    $_SESSION['user_id']        = $user['id'];
    $_SESSION['user_username']  = $user['username'];
    $_SESSION['user_full_name'] = $user['full_name'];
    $_SESSION['user_email']     = $user['email'];
}

/**
 * Đăng xuất - xóa session
 */
function logoutUser() {
    session_unset();
    session_destroy();
}
