<?php
/**
 * Controller: Report (Báo cáo)
 */
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/../models/PurchaseOrder.php';

class ReportController {
    public function index() {
        $pageTitle = 'Báo cáo & Xuất dữ liệu';
        require VIEW_PATH . '/reports/index.php';
    }

    /** Xuất danh sách NCC ra CSV */
    public function exportSuppliers() {
        $result = (new Supplier())->getAll('', '', 1, 10000);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=nha_cung_cap_' . date('Ymd') . '.csv');

        // BOM cho Excel đọc UTF-8
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Tên công ty', 'Liên hệ', 'Email', 'SĐT', 'Mã thuế', 'Trạng thái', 'Rating', 'Ngày tạo']);

        foreach ($result['data'] as $s) {
            fputcsv($output, [
                $s['id'], $s['company_name'], $s['contact_person'],
                $s['email'], $s['phone'], $s['tax_code'],
                SUPPLIER_STATUS_LABELS[$s['status']], $s['rating_avg'],
                $s['created_at']
            ]);
        }
        fclose($output);
        exit;
    }

    /** Xuất tồn kho ra CSV */
    public function exportInventory() {
        $result = (new Inventory())->getAll('', false, 1, 10000);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=ton_kho_' . date('Ymd') . '.csv');
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Mã SKU', 'Tên sản phẩm', 'NCC', 'Đơn vị', 'Đơn giá', 'Tồn kho', 'Mức tối thiểu', 'Giá trị']);

        foreach ($result['data'] as $item) {
            fputcsv($output, [
                $item['sku'], $item['name'], $item['supplier_name'],
                $item['unit'], $item['unit_price'], $item['quantity_on_hand'],
                $item['min_stock_level'], $item['quantity_on_hand'] * $item['unit_price']
            ]);
        }
        fclose($output);
        exit;
    }

    /** In đơn hàng (HTML print-friendly) */
    public function printOrder() {
        $id = intval($_GET['id'] ?? 0);
        $orderModel = new PurchaseOrder();
        $order = $orderModel->getById($id);
        $items = $orderModel->getItems($id);
        if (!$order) {
            setFlash('error', 'Không tìm thấy đơn hàng.');
            header('Location: index.php?page=purchase_orders');
            exit;
        }
        require VIEW_PATH . '/reports/print_order.php';
    }
}
