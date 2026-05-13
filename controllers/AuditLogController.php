<?php
/**
 * Controller: AuditLog (Nhật ký hoạt động - Admin only)
 */
require_once __DIR__ . '/../models/AuditLog.php';

class AuditLogController {
    public function index() {
        $model = new AuditLog();
        $page = max(1, intval($_GET['p'] ?? 1));
        $tableName = $_GET['table_name'] ?? '';
        $action = $_GET['action_filter'] ?? '';
        $result = $model->getAll($page, ITEMS_PER_PAGE, $tableName, $action);
        $pageTitle = 'Nhật ký hoạt động';
        require VIEW_PATH . '/audit_logs/index.php';
    }
}
