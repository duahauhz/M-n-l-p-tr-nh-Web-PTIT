<?php
/**
 * Controller: Category (Danh mục)
 */
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/AuditLog.php';

class CategoryController {
    private $model;
    private $audit;

    public function __construct() {
        $this->model = new Category();
        $this->audit = new AuditLog();
    }

    public function index() {
        $categories = $this->model->getAll();
        $pageTitle = 'Quản lý danh mục';
        require VIEW_PATH . '/categories/index.php';
    }

    public function create() {
        $parents = $this->model->getParentCategories();
        $category = null;
        $pageTitle = 'Thêm danh mục';
        require VIEW_PATH . '/categories/form.php';
    }

    public function store() {
        requireCSRF();
        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'parent_id'   => $_POST['parent_id'] ?? null,
            'sort_order'  => intval($_POST['sort_order'] ?? 0),
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
        ];
        if (empty($data['name'])) {
            setFlash('error', 'Tên danh mục không được để trống.');
            header('Location: index.php?page=categories&action=create');
            exit;
        }
        $id = $this->model->create($data);
        $this->audit->log('CREATE', 'categories', $id, null, $data);
        setFlash('success', 'Thêm danh mục thành công!');
        header('Location: index.php?page=categories');
        exit;
    }

    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $category = $this->model->getById($id);
        if (!$category) {
            setFlash('error', 'Không tìm thấy danh mục.');
            header('Location: index.php?page=categories');
            exit;
        }
        $parents = $this->model->getParentCategories();
        $pageTitle = 'Sửa danh mục';
        require VIEW_PATH . '/categories/form.php';
    }

    public function update() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        $oldData = $this->model->getById($id);
        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'parent_id'   => $_POST['parent_id'] ?? null,
            'sort_order'  => intval($_POST['sort_order'] ?? 0),
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
        ];
        if (empty($data['name'])) {
            setFlash('error', 'Tên danh mục không được để trống.');
            header('Location: index.php?page=categories&action=edit&id=' . $id);
            exit;
        }
        $this->model->update($id, $data);
        $this->audit->log('UPDATE', 'categories', $id, $oldData, $data);
        setFlash('success', 'Cập nhật danh mục thành công!');
        header('Location: index.php?page=categories');
        exit;
    }

    public function delete() {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        $oldData = $this->model->getById($id);
        if ($this->model->delete($id)) {
            $this->audit->log('DELETE', 'categories', $id, $oldData, null);
            setFlash('success', 'Đã xóa danh mục.');
        } else {
            setFlash('error', 'Không thể xóa: danh mục có sản phẩm hoặc danh mục con.');
        }
        header('Location: index.php?page=categories');
        exit;
    }
}
