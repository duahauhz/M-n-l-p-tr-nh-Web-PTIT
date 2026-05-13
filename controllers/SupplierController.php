<?php
/**
 * Controller: Supplier (Nhà cung cấp)
 * Xử lý CRUD + tìm kiếm/lọc/phân trang
 */

require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/AuditLog.php';

class SupplierController
{
    private $model;
    private $audit;

    public function __construct()
    {
        $this->model = new Supplier();
        $this->audit = new AuditLog();
    }

    /** Danh sách NCC */
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = max(1, intval($_GET['p'] ?? 1));

        $result = $this->model->getAll($search, $status, $page);
        $pageTitle = 'Quản lý nhà cung cấp';
        require VIEW_PATH . '/suppliers/index.php';
    }

    /** Form thêm mới */
    public function create()
    {
        $pageTitle = 'Thêm nhà xxx cung cấp';
        require VIEW_PATH . '/suppliers/create.php';
    }

    /** Lưu NCC mới */
    public function store()
    {
        requireCSRF();
        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'tax_code' => trim($_POST['tax_code'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        // Validate
        if (empty($data['company_name'])) {
            setFlash('error', 'Tên công ty không được để trống.');
            header('Location: index.php?page=suppliers&action=create');
            exit;
        }

        $id = $this->model->create($data);
        $this->audit->log('CREATE', 'suppliers', $id, null, $data);

        setFlash('success', 'Thêm nhà cung cấp thành công!');
        header('Location: index.php?page=suppliers');
        exit;
    }

    /** Form chỉnh sửa */
    public function edit()
    {
        $id = intval($_GET['id'] ?? 0);
        $supplier = $this->model->getById($id);
        if (!$supplier) {
            setFlash('error', 'Không tìm thấy nhà cung cấp.');
            header('Location: index.php?page=suppliers');
            exit;
        }
        $pageTitle = 'Sửa nhà cung cấp';
        require VIEW_PATH . '/suppliers/edit.php';
    }

    /** Cập nhật NCC */
    public function update()
    {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        $oldData = $this->model->getById($id);

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'tax_code' => trim($_POST['tax_code'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            setFlash('error', 'Tên công ty không được để trống.');
            header('Location: index.php?page=suppliers&action=edit&id=' . $id);
            exit;
        }

        $this->model->update($id, $data);
        $this->audit->log('UPDATE', 'suppliers', $id, $oldData, $data);

        setFlash('success', 'Cập nhật nhà cung cấp thành công!');
        header('Location: index.php?page=suppliers');
        exit;
    }

    /** Xóa NCC */
    public function delete()
    {
        requireCSRF();
        $id = intval($_POST['id'] ?? 0);
        $oldData = $this->model->getById($id);

        if ($this->model->delete($id)) {
            $this->audit->log('DELETE', 'suppliers', $id, $oldData, null);
            setFlash('success', 'Đã xóa nhà cung cấp.');
        } else {
            setFlash('error', 'Không thể xóa: nhà cung cấp đang có sản phẩm liên kết.');
        }

        header('Location: index.php?page=suppliers');
        exit;
    }

    /** Chi tiết NCC */
    public function show()
    {
        $id = intval($_GET['id'] ?? 0);
        $supplier = $this->model->getById($id);
        if (!$supplier) {
            setFlash('error', 'Không tìm thấy nhà cung cấp.');
            header('Location: index.php?page=suppliers');
            exit;
        }

        $orders = $this->model->getOrders($id);
        $reviews = $this->model->getReviews($id);
        $pageTitle = 'Chi tiết: ' . $supplier['company_name'];
        require VIEW_PATH . '/suppliers/show.php';
    }
}
