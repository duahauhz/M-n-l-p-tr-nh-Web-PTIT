<?php
/**
 * Controller: Dashboard (Trang tổng quan)
 * Hiển thị thống kê và biểu đồ
 */

require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/PurchaseOrder.php';
require_once __DIR__ . '/../models/Inventory.php';

class DashboardController {

    public function index() {
        $supplierModel  = new Supplier();
        $productModel   = new Product();
        $orderModel     = new PurchaseOrder();
        $inventoryModel = new Inventory();

        // Các thẻ thống kê
        $stats = [
            'total_suppliers'  => $supplierModel->countActive(),
            'total_products'   => $productModel->countActive(),
            'pending_orders'   => $orderModel->countByStatus('pending'),
            'inventory_value'  => $inventoryModel->totalValue(),
        ];

        // Dữ liệu cho biểu đồ
        $monthlySpending    = $orderModel->monthlySpending();
        $statusDistribution = $orderModel->statusDistribution();
        $lowStockProducts   = $inventoryModel->getLowStock(5);

        // Truyền dữ liệu sang view
        require VIEW_PATH . '/dashboard/index.php';
    }
}
