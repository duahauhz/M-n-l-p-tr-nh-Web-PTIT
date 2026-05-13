<?php
/**
 * Controller: PurchaseOrder (Đơn hàng mua)
 * Xử lý workflow: draft → pending → approved → received / cancelled
 */
require_once __DIR__ . '/../models/PurchaseOrder.php';
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/AuditLog.php';

class PurchaseOrderController {
    private $model;
    private $audit;

    public function __construct() {
        $this->model = new PurchaseOrder();
        $this->audit = new AuditLog();
    }

    /** Danh sách đơn hàng */
    public function index() {
        $status     = $_GET['status'] ?? '';
        $supplierId = $_GET['supplier_id'] ?? '';
        $page       = max(1, intval($_GET['p'] ?? 1));

        $result    = $this->model->getAll($status, $supplierId, $page);
        $suppliers = (new Supplier())->getAllActive();
        $pageTitle = 'Quản lý đơn hàng mua';
        require VIEW_PATH . '/purchase_orders/index.php';
    }

    /** Form tạo đơn hàng */
    public function create() {
        $suppliers = (new Supplier())->getAllActive();
        $products  = [];
        $selectedSupplier = $_GET['supplier_id'] ?? '';

        if (!empty($selectedSupplier)) {
            $products = (new Product())->getBySupplier($selectedSupplier);
        }

        $pageTitle = 'Tạo đơn hàng mua';
        require VIEW_PATH . '/purchase_orders/create.php';
    }

    /** Lưu đơn hàng mới */
    public function store() {
        requireCSRF();

        $data = [
            'supplier_id'       => intval($_POST['supplier_id'] ?? 0),
            'created_by'        => currentUser('id'),
            'notes'             => trim($_POST['notes'] ?? ''),
            'expected_delivery' => $_POST['expected_delivery'] ?? null,
        ];

        $items = [];
        if (!empty($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                if (!empty($item['product_id']) && intval($item['quantity']) > 0) {
                    $items[] = [
                        'product_id' => intval($item['product_id']),
                        'quantity'   => intval($item['quantity']),
                        'unit_price' => floatval($item['unit_price']),
                    ];
                }
            }
        }

        if ($data['supplier_id'] == 0 || empty($items)) {
            setFlash('error', 'Vui lòng chọn NCC và thêm ít nhất 1 sản phẩm.');
            header('Location: index.php?page=purchase_orders&action=create');
            exit;
        }

        try {
            $id = $this->model->create($data, $items);
            $this->audit->log('CREATE', 'purchase_orders', $id, null, $data);
            setFlash('success', 'Tạo đơn hàng thành công!');
            header('Location: index.php?page=purchase_orders&action=show&id=' . $id);
        } catch (Exception $e) {
            setFlash('error', 'Lỗi tạo đơn hàng: ' . $e->getMessage());
            header('Location: index.php?page=purchase_orders&action=create');
        }
        exit;
    }

    /** Chi tiết đơn hàng */
    public function show() {
        $id = intval($_GET['id'] ?? 0);
        $order = $this->model->getById($id);
        if (!$order) {
            setFlash('error', 'Không tìm thấy đơn hàng.');
            header('Location: index.php?page=purchase_orders');
            exit;
        }
        $items = $this->model->getItems($id);
        $pageTitle = 'Chi tiết đơn hàng: ' . $order['order_code'];
        require VIEW_PATH . '/purchase_orders/show.php';
    }

    /** Gửi duyệt: draft → pending */
    public function submit() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        if ($this->model->submit($id)) {
            $this->audit->log('UPDATE', 'purchase_orders', $id, ['status' => 'draft'], ['status' => 'pending']);
            setFlash('success', 'Đã gửi đơn hàng để duyệt.');
        } else {
            setFlash('error', 'Không thể gửi duyệt đơn hàng này.');
        }
        header('Location: index.php?page=purchase_orders&action=show&id=' . $id);
        exit;
    }

    /** Duyệt đơn: pending → approved (chỉ Manager/Admin) */
    public function approve() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        if ($this->model->approve($id, currentUser('id'))) {
            $this->audit->log('UPDATE', 'purchase_orders', $id, ['status' => 'pending'], ['status' => 'approved']);
            setFlash('success', 'Đã duyệt đơn hàng.');
        } else {
            setFlash('error', 'Không thể duyệt đơn hàng này.');
        }
        header('Location: index.php?page=purchase_orders&action=show&id=' . $id);
        exit;
    }

    /** Từ chối: pending → cancelled (chỉ Manager/Admin) */
    public function reject() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        if ($this->model->reject($id)) {
            $this->audit->log('UPDATE', 'purchase_orders', $id, ['status' => 'pending'], ['status' => 'cancelled']);
            setFlash('success', 'Đã từ chối đơn hàng.');
        } else {
            setFlash('error', 'Không thể từ chối đơn hàng này.');
        }
        header('Location: index.php?page=purchase_orders&action=show&id=' . $id);
        exit;
    }

    /** Nhận hàng: approved → received (cập nhật tồn kho qua Stored Procedure) */
    public function receive() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        try {
            $this->model->receive($id, currentUser('id'));
            $this->audit->log('UPDATE', 'purchase_orders', $id, ['status' => 'approved'], ['status' => 'received']);
            setFlash('success', 'Đã nhận hàng và cập nhật tồn kho thành công!');
        } catch (Exception $e) {
            setFlash('error', 'Lỗi nhận hàng: ' . $e->getMessage());
        }
        header('Location: index.php?page=purchase_orders&action=show&id=' . $id);
        exit;
    }
}
