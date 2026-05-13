<?php
/**
 * Model: AuditLog (Nhật ký hoạt động) - Đã tinh gọn
 * Bỏ old_data, new_data theo schema mới
 */

class AuditLog {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Ghi 1 dòng audit log
     * @param string $action     Hành động: CREATE, UPDATE, DELETE, LOGIN, LOGOUT...
     * @param string $tableName  Tên bảng bị tác động
     * @param int    $recordId   ID bản ghi bị tác động
     */
    public function log($action, $tableName, $recordId = null, $oldData = null, $newData = null) {
        // oldData và newData giữ lại signature nhưng không insert (đã xóa cột)
        $sql = "INSERT INTO audit_logs (user_id, action, table_name, record_id, ip_address)
                VALUES (:user_id, :action, :table_name, :record_id, :ip_address)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id'    => $_SESSION['user_id'] ?? null,
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        ]);
    }

    /**
     * Lấy danh sách logs có phân trang
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE, $tableName = '', $action = '') {
        $offset = ($page - 1) * $limit;
        $where = "WHERE 1=1";
        $params = [];

        if (!empty($tableName)) {
            $where .= " AND al.table_name = :table_name";
            $params['table_name'] = $tableName;
        }
        if (!empty($action)) {
            $where .= " AND al.action = :action";
            $params['action'] = $action;
        }

        $countSql = "SELECT COUNT(*) FROM audit_logs al $where";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        $sql = "SELECT al.*, u.full_name as user_name
                FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.id
                $where
                ORDER BY al.created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(":$key", $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        return [
            'data'       => $data,
            'total'      => $total,
            'page'       => $page,
            'limit'      => $limit,
            'totalPages' => ceil($total / $limit),
        ];
    }
}
