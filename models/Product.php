<?php
/**
 * Model: Product (Sản phẩm)
 */

class Product {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Lấy danh sách sản phẩm có phân trang, tìm kiếm, lọc
     */
    public function getAll($search = '', $categoryId = '', $supplierId = '', $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        $where = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (p.name LIKE :search OR p.sku LIKE :search2)";
            $params['search']  = "%$search%";
            $params['search2'] = "%$search%";
        }
        if (!empty($categoryId)) {
            $where .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        if (!empty($supplierId)) {
            $where .= " AND p.supplier_id = :supplier_id";
            $params['supplier_id'] = $supplierId;
        }

        // Đếm tổng
        $countSql = "SELECT COUNT(*) FROM products p $where";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        // Lấy dữ liệu kèm tên NCC, danh mục, tồn kho
        $sql = "SELECT p.*, s.company_name as supplier_name, c.name as category_name,
                COALESCE(i.quantity_on_hand, 0) as stock
                FROM products p
                JOIN suppliers s ON p.supplier_id = s.id
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id
                $where
                ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
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
     * Lấy sản phẩm theo ID
     */
    public function getById($id) {
        $sql = "SELECT p.*, s.company_name as supplier_name, c.name as category_name
                FROM products p
                JOIN suppliers s ON p.supplier_id = s.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Lấy sản phẩm theo NCC (cho form tạo đơn hàng)
     */
    public function getBySupplier($supplierId) {
        $sql = "SELECT id, name, sku, unit_price, unit FROM products
                WHERE supplier_id = :sid AND status = 'active'
                ORDER BY name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sid' => $supplierId]);
        return $stmt->fetchAll();
    }

    /**
     * Thêm sản phẩm
     */
    public function create($data) {
        $sql = "INSERT INTO products (supplier_id, category_id, name, sku, description, unit_price, unit, image, status, min_stock_level)
                VALUES (:supplier_id, :category_id, :name, :sku, :description, :unit_price, :unit, :image, :status, :min_stock_level)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'supplier_id'    => $data['supplier_id'],
            'category_id'    => $data['category_id'] ?: null,
            'name'           => $data['name'],
            'sku'            => $data['sku'],
            'description'    => $data['description'],
            'unit_price'     => $data['unit_price'],
            'unit'           => $data['unit'],
            'image'          => $data['image'] ?? null,
            'status'         => $data['status'] ?? 'active',
            'min_stock_level'=> $data['min_stock_level'] ?? 10,
        ]);
        $productId = $this->pdo->lastInsertId();

        // Tạo bản ghi inventory tương ứng
        $invSql = "INSERT INTO inventory (product_id, quantity_on_hand) VALUES (:pid, 0)";
        $this->pdo->prepare($invSql)->execute(['pid' => $productId]);

        return $productId;
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update($id, $data) {
        $sql = "UPDATE products SET
                supplier_id = :supplier_id, category_id = :category_id,
                name = :name, sku = :sku, description = :description,
                unit_price = :unit_price, unit = :unit, status = :status,
                min_stock_level = :min_stock_level";

        $params = [
            'id'              => $id,
            'supplier_id'     => $data['supplier_id'],
            'category_id'     => $data['category_id'] ?: null,
            'name'            => $data['name'],
            'sku'             => $data['sku'],
            'description'     => $data['description'],
            'unit_price'      => $data['unit_price'],
            'unit'            => $data['unit'],
            'status'          => $data['status'],
            'min_stock_level' => $data['min_stock_level'] ?? 10,
        ];

        if (!empty($data['image'])) {
            $sql .= ", image = :image";
            $params['image'] = $data['image'];
        }

        $sql .= " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Xóa sản phẩm
     */
    public function delete($id) {
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM purchase_order_items WHERE product_id = :id");
        $check->execute(['id' => $id]);
        if ($check->fetchColumn() > 0) return false;

        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Đếm tổng sản phẩm active
     */
    public function countActive() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'");
        return $stmt->fetchColumn();
    }
}
