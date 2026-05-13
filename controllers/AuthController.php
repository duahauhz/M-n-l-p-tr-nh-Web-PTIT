<?php
/**
 * Controller: Auth (Xác thực)
 * Xử lý đăng nhập / đăng xuất
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class AuthController {

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm() {
        require VIEW_PATH . '/auth/login.php';
    }

    /**
     * Xử lý đăng nhập
     */
    public function login() {
        // Kiểm tra CSRF
        requireCSRF();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate input
        if (empty($username) || empty($password)) {
            setFlash('error', 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.');
            header('Location: index.php?page=login');
            exit;
        }

        // Tìm user trong database
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        // Kiểm tra mật khẩu bằng password_verify (so sánh với bcrypt hash)
        if ($user && password_verify($password, $user['password_hash'])) {
            // Đăng nhập thành công
            loginUser($user);

            // Ghi audit log
            $audit = new AuditLog();
            $audit->log('LOGIN', 'users', $user['id']);

            setFlash('success', 'Chào mừng ' . $user['full_name'] . '!');
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            // Sai thông tin
            setFlash('error', 'Tên đăng nhập hoặc mật khẩu không đúng.');
            header('Location: index.php?page=login');
            exit;
        }
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        // Ghi audit log trước khi xóa session
        if (isLoggedIn()) {
            $audit = new AuditLog();
            $audit->log('LOGOUT', 'users', currentUser('id'));
        }

        logoutUser();
        setFlash('success', 'Đã đăng xuất thành công.');
        header('Location: index.php?page=login');
        exit;
    }
}
