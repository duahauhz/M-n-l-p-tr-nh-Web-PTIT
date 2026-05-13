<?php
/**
 * Model: User (Người dùng - chỉ admin)
 */

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Tìm user theo username (dùng cho đăng nhập)
     */
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username AND is_active = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    /**
     * Lấy user theo ID
     */
    public function getById($id) {
        $sql = "SELECT id, username, email, full_name, phone, is_active, created_at FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
