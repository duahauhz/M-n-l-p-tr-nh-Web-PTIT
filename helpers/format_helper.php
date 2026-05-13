<?php
/**
 * SupplierHub - Helper định dạng dữ liệu
 * Các hàm hỗ trợ format hiển thị
 */

/**
 * Format số tiền theo định dạng Việt Nam
 * Ví dụ: 15000000 → "15.000.000 ₫"
 * @param float $amount
 * @return string
 */
function formatMoney($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}

/**
 * Format ngày giờ theo định dạng Việt Nam
 * Ví dụ: "2026-04-18 08:00:00" → "18/04/2026 08:00"
 * @param string $datetime
 * @param bool $showTime  Có hiển thị giờ không
 * @return string
 */
function formatDate($datetime, $showTime = false) {
    if (empty($datetime)) return '—';
    $format = $showTime ? 'd/m/Y H:i' : 'd/m/Y';
    return date($format, strtotime($datetime));
}

/**
 * Escape HTML để chống XSS
 * Dùng cho mọi output dữ liệu người dùng nhập
 * @param string $text
 * @return string
 */
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Tạo slug từ tên (dùng cho category)
 * Ví dụ: "Thiết bị văn phòng" → "thiet-bi-van-phong"
 * @param string $text
 * @return string
 */
function createSlug($text) {
    // Bảng chuyển đổi tiếng Việt
    $vietnamese = [
        'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
        'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
        'ì','í','ị','ỉ','ĩ',
        'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
        'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
        'ỳ','ý','ỵ','ỷ','ỹ',
        'đ',
        'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
        'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
        'Ì','Í','Ị','Ỉ','Ĩ',
        'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
        'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
        'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
        'Đ'
    ];
    $ascii = [
        'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
        'e','e','e','e','e','e','e','e','e','e','e',
        'i','i','i','i','i',
        'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
        'u','u','u','u','u','u','u','u','u','u','u',
        'y','y','y','y','y',
        'd',
        'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
        'e','e','e','e','e','e','e','e','e','e','e',
        'i','i','i','i','i',
        'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
        'u','u','u','u','u','u','u','u','u','u','u',
        'y','y','y','y','y',
        'd'
    ];

    $text = str_replace($vietnamese, $ascii, $text);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Rút gọn text dài
 * @param string $text
 * @param int $length
 * @return string
 */
function truncate($text, $length = 50) {
    if (mb_strlen($text) <= $length) return e($text);
    return e(mb_substr($text, 0, $length)) . '...';
}
