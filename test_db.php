<?php
require_once __DIR__ . '/config/database.php';
try {
    $pdo = getDBConnection();
    echo "Kết nối CSDL thành công!\n";
    
    // Thử truy vấn
    $stmt = $pdo->query("SELECT * FROM users LIMIT 1");
    $user = $stmt->fetch();
    echo "Lấy user thành công: " . ($user ? $user['username'] : 'Không có data') . "\n";
} catch (Exception $e) {
    echo "Lỗi kết nối CSDL: " . $e->getMessage() . "\n";
}
