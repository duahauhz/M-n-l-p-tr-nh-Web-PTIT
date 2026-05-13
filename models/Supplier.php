<?php
/**
 * Model: Supplier (Nhà cung cấp)
 * Xử lý mọi thao tác CRUD với bảng suppliers
 */

class Supplier {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Lấy danh sách NCC có phân trang, tìm kiếm, lọc
     */
    public function getAll($search = '', $status = '', $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        $where = "WHERE 1=1";
        $params = [];

        // Tìm kiếm theo tên, email, điện thoại
        if (!empty($search)) {
            $where .= " AND (company_name LIKE :search OR contact_person LIKE :search2 OR email LIKE :search3 OR phone LIKE :search4)";
            $params['search']  = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        // Lọc theo trạng thái
        if (!empty($status)) {
            $where .= " AND status = :status";
            $params['status'] = $status;
        }

        // Đếm tổng số dòng (cho phân trang)
        $countSql = "SELECT COUNT(*) FROM suppliers $where";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        // Lấy dữ liệu có phân trang
        $sql = "SELECT * FROM suppliers $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
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

    /**
     * Lấy tất cả NCC active (cho dropdown)
     */
    public function getAllActive() {
        $sql = "SELECT id, company_name FROM suppliers WHERE status = 'active' ORDER BY company_name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Lấy 1 NCC theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM suppliers WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Thêm mới NCC
     */
    public function create($data) {
        $sql = "INSERT INTO suppliers (company_name, contact_person, email, phone, address, tax_code, status, notes)
                VALUES (:company_name, :contact_person, :email, :phone, :address, :tax_code, :status, :notes)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'company_name'   => $data['company_name'],
            'contact_person' => $data['contact_person'],
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'address'        => $data['address'],
            'tax_code'       => $data['tax_code'],
            'status'         => $data['status'] ?? 'active',
            'notes'          => $data['notes'],
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Cập nhật NCC
     */
    public function update($id, $data) {
        $sql = "UPDATE suppliers SET
                company_name = :company_name,
                contact_person = :contact_person,
                email = :email,
                phone = :phone,
                address = :address,
                tax_code = :tax_code,
                status = :status,
                notes = :notes
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'             => $id,
            'company_name'   => $data['company_name'],
            'contact_person' => $data['contact_person'],
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'address'        => $data['address'],
            'tax_code'       => $data['tax_code'],
            'status'         => $data['status'],
            'notes'          => $data['notes'],
        ]);
    }

    /**
     * Xóa NCC (chỉ khi không có sản phẩm liên kết)
     */
    public function delete($id) {
        // Kiểm tra có sản phẩm liên kết không
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE supplier_id = :id");
        $check->execute(['id' => $id]);
        if ($check->fetchColumn() > 0) {
            return false; // Không xóa được
        }

        $stmt = $this->pdo->prepare("DELETE FROM suppliers WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Đếm tổng NCC active
     */
    public function countActive() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM suppliers WHERE status = 'active'");
        return $stmt->fetchColumn();
    }

    /**
     * Lấy đơn hàng của NCC (cho trang chi tiết)
     */
    public function getOrders($supplierId) {
        $sql = "SELECT po.*, u.full_name as created_by_name
                FROM purchase_orders po
                JOIN users u ON po.created_by = u.id
                WHERE po.supplier_id = :sid
                ORDER BY po.created_at DESC LIMIT 20";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sid' => $supplierId]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy đánh giá của NCC
     */
    public function getReviews($supplierId) {
        $sql = "SELECT sr.*, u.full_name as reviewer_name
                FROM supplier_reviews sr
                JOIN users u ON sr.user_id = u.id
                WHERE sr.supplier_id = :sid
                ORDER BY sr.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sid' => $supplierId]);
        return $stmt->fetchAll();
    }
}
