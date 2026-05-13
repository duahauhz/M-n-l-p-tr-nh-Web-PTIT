<?php
/**
 * Controller: Product (Sản phẩm)
 */
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/AuditLog.php';

class ProductController {
    private $model;
    private $audit;

    public function __construct() {
        $this->model = new Product();
        $this->audit = new AuditLog();
    }

    public function index() {
        $search     = $_GET['search'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $supplierId = $_GET['supplier_id'] ?? '';
        $page       = max(1, intval($_GET['p'] ?? 1));

        $result     = $this->model->getAll($search, $categoryId, $supplierId, $page);
        $suppliers  = (new Supplier())->getAllActive();
        $categories = (new Category())->getAllActive();
        $pageTitle  = 'Quản lý sản phẩm';
        require VIEW_PATH . '/products/index.php';
    }

    public function create() {
        $suppliers  = (new Supplier())->getAllActive();
        $categories = (new Category())->getAllActive();
        $pageTitle  = 'Thêm sản phẩm';
        require VIEW_PATH . '/products/create.php';
    }

    public function store() {
        requireCSRF();
        $data = [
            'supplier_id'    => intval($_POST['supplier_id'] ?? 0),
            'category_id'    => $_POST['category_id'] ?? null,
            'name'           => trim($_POST['name'] ?? ''),
            'sku'            => trim($_POST['sku'] ?? ''),
            'description'    => trim($_POST['description'] ?? ''),
            'unit_price'     => floatval($_POST['unit_price'] ?? 0),
            'unit'           => $_POST['unit'] ?? 'cái',
            'status'         => $_POST['status'] ?? 'active',
            'min_stock_level'=> intval($_POST['min_stock_level'] ?? 10),
            'image'          => null,
        ];

        // Upload ảnh (nếu có)
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $fileName = 'product_' . time() . '.' . $ext;
                $uploadDir = ROOT_PATH . '/assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName);
                $data['image'] = $fileName;
            }
        }

        if (empty($data['name']) || empty($data['sku']) || $data['supplier_id'] == 0) {
            setFlash('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            header('Location: index.php?page=products&action=create');
            exit;
        }

        $id = $this->model->create($data);
        $this->audit->log('CREATE', 'products', $id, null, $data);
        setFlash('success', 'Thêm sản phẩm thành công!');
        header('Location: index.php?page=products');
        exit;
    }

    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $product = $this->model->getById($id);
        if (!$product) {
            setFlash('error', 'Không tìm thấy sản phẩm.');
            header('Location: index.php?page=products');
            exit;
        }
        $suppliers  = (new Supplier())->getAllActive();
        $categories = (new Category())->getAllActive();
        $pageTitle  = 'Sửa sản phẩm';
        require VIEW_PATH . '/products/edit.php';
    }

    public function update() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        $oldData = $this->model->getById($id);
        $data = [
            'supplier_id'    => intval($_POST['supplier_id'] ?? 0),
            'category_id'    => $_POST['category_id'] ?? null,
            'name'           => trim($_POST['name'] ?? ''),
            'sku'            => trim($_POST['sku'] ?? ''),
            'description'    => trim($_POST['description'] ?? ''),
            'unit_price'     => floatval($_POST['unit_price'] ?? 0),
            'unit'           => $_POST['unit'] ?? 'cái',
            'status'         => $_POST['status'] ?? 'active',
            'min_stock_level'=> intval($_POST['min_stock_level'] ?? 10),
            'image'          => null,
        ];

        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $fileName = 'product_' . time() . '.' . $ext;
                $uploadDir = ROOT_PATH . '/assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName);
                $data['image'] = $fileName;
            }
        }

        $this->model->update($id, $data);
        $this->audit->log('UPDATE', 'products', $id, $oldData, $data);
        setFlash('success', 'Cập nhật sản phẩm thành công!');
        header('Location: index.php?page=products');
        exit;
    }

    public function delete() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        $oldData = $this->model->getById($id);
        if ($this->model->delete($id)) {
            $this->audit->log('DELETE', 'products', $id, $oldData, null);
            setFlash('success', 'Đã xóa sản phẩm.');
        } else {
            setFlash('error', 'Không thể xóa: sản phẩm đang có trong đơn hàng.');
        }
        header('Location: index.php?page=products');
        exit;
    }
}
