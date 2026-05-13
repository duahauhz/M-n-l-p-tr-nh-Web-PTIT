<?php
/**
 * SupplierHub - Helper CSRF Token
 * Bảo vệ form khỏi tấn công Cross-Site Request Forgery
 */

/**
 * Tạo CSRF token và lưu vào session
 * @return string  Token đã tạo
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Tạo input hidden chứa CSRF token để chèn vào form
 * @return string  HTML input hidden
 */
function csrfField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Kiểm tra CSRF token từ form POST có hợp lệ không
 * @return bool
 */
function validateCSRFToken() {
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    // Dùng hash_equals để chống timing attack
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

/**
 * Kiểm tra CSRF và trả về lỗi nếu không hợp lệ
 */
function requireCSRF() {
    if (!validateCSRFToken()) {
        setFlash('error', 'Token bảo mật không hợp lệ. Vui lòng thử lại.');
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? APP_URL);
        exit;
    }
}
