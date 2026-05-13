<?php
/**
 * Controller: Inventory, Review, User, Report, AuditLog
 * Các controller nhỏ gọn hơn
 */

// ===== InventoryController =====
require_once __DIR__ . '/../models/Inventory.php';

class InventoryController {
    public function index() {
        $model = new Inventory();
        $search = $_GET['search'] ?? '';
        $lowStock = isset($_GET['low_stock']);
        $page = max(1, intval($_GET['p'] ?? 1));

        $result = $model->getAll($search, $lowStock, $page);
        $pageTitle = 'Quản lý tồn kho';
        require VIEW_PATH . '/inventory/index.php';
    }
}
