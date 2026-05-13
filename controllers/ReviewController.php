<?php
/**
 * Controller: Review (Đánh giá NCC)
 */
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/PurchaseOrder.php';
require_once __DIR__ . '/../models/AuditLog.php';

class ReviewController {
    public function index() {
        $model = new Review();
        $supplierId = $_GET['supplier_id'] ?? '';
        $page = max(1, intval($_GET['p'] ?? 1));
        $result = $model->getAll($supplierId, $page);
        $suppliers = (new Supplier())->getAllActive();
        $pageTitle = 'Đánh giá nhà cung cấp';
        require VIEW_PATH . '/reviews/index.php';
    }

    public function create() {
        $suppliers = (new Supplier())->getAllActive();
        $selectedSupplier = $_GET['supplier_id'] ?? '';
        $pageTitle = 'Thêm đánh giá';
        require VIEW_PATH . '/reviews/create.php';
    }

    public function store() {
        requireCSRF();
        $data = [
            'supplier_id'       => intval($_POST['supplier_id'] ?? 0),
            'user_id'           => currentUser('id'),
            'purchase_order_id' => $_POST['purchase_order_id'] ?? null,
            'rating_quality'    => intval($_POST['rating_quality'] ?? 5),
            'rating_delivery'   => intval($_POST['rating_delivery'] ?? 5),
            'rating_price'      => intval($_POST['rating_price'] ?? 5),
            'rating_service'    => intval($_POST['rating_service'] ?? 5),
            'comment'           => trim($_POST['comment'] ?? ''),
        ];

        if ($data['supplier_id'] == 0) {
            setFlash('error', 'Vui lòng chọn nhà cung cấp.');
            header('Location: index.php?page=reviews&action=create');
            exit;
        }

        $model = new Review();
        $id = $model->create($data);
        (new AuditLog())->log('CREATE', 'supplier_reviews', $id, null, $data);
        setFlash('success', 'Đánh giá đã được ghi nhận!');
        header('Location: index.php?page=reviews');
        exit;
    }
}
