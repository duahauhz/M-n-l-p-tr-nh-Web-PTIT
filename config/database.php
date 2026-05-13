<?php
/**
 * SupplierHub - Kết nối Database
 * Sử dụng PDO (PHP Data Objects) - an toàn hơn mysqli
 */

require_once __DIR__ . '/constants.php';

/**
 * Hàm tạo kết nối PDO đến MySQL
 * Sử dụng Singleton pattern - chỉ tạo 1 kết nối duy nhất
 */
function getDBConnection() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            // charset=utf8mb4 trong DSN đã đủ để set encoding kết nối
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

            $options = [
                // Bắn exception khi có lỗi SQL (dễ debug)
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // Trả về kết quả dạng mảng kết hợp (key là tên cột)
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Dùng native prepared statement của MySQL
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

            // Ép MySQL trả dữ liệu đúng UTF-8 (tương thích PHP 8.5)
            $pdo->exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");

        } catch (PDOException $e) {
            die("Lỗi kết nối database: " . $e->getMessage());
        }
    }

    return $pdo;
}
