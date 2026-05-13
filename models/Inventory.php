<?php
/**
 * Model: Inventory (Tồn kho)
 */

class Inventory {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Lấy danh sách tồn kho có phân trang
     */
    public function getAll($search = '', $lowStockOnly = false, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        $where = "WHERE p.status = 'active'";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (p.name LIKE :search OR p.sku LIKE :search2)";
            $params['search']  = "%$search%";
            $params['search2'] = "%$search%";
        }
        if ($lowStockOnly) {
            $where .= " AND COALESCE(i.quantity_on_hand, 0) <= p.min_stock_level";
        }

        $countSql = "SELECT COUNT(*) FROM products p LEFT JOIN inventory i ON p.id = i.product_id $where";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        $sql = "SELECT p.id, p.name, p.sku, p.unit, p.unit_price, p.min_stock_level,
                s.company_name as supplier_name,
                COALESCE(i.quantity_on_hand, 0) as quantity_on_hand,
                COALESCE(i.quantity_reserved, 0) as quantity_reserved,
                i.last_updated
                FROM products p
                LEFT JOIN inventory i ON p.id = i.product_id
                JOIN suppliers s ON p.supplier_id = s.id
                $where
                ORDER BY COALESCE(i.quantity_on_hand, 0) ASC
                LIMIT :limit OFFSET :offset";
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
     * Tổng giá trị tồn kho
     */
    public function totalValue() {
        $sql = "SELECT COALESCE(SUM(i.quantity_on_hand * p.unit_price), 0)
                FROM inventory i JOIN products p ON i.product_id = p.id";
        return $this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Đếm sản phẩm sắp hết hàng
     */
    public function countLowStock() {
        $sql = "SELECT COUNT(*) FROM products p
                LEFT JOIN inventory i ON p.id = i.product_id
                WHERE p.status = 'active' AND COALESCE(i.quantity_on_hand, 0) <= p.min_stock_level";
        return $this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Top sản phẩm sắp hết hàng
     */
    public function getLowStock($limit = 5) {
        $sql = "SELECT p.id, p.name, p.sku, p.min_stock_level,
                COALESCE(i.quantity_on_hand, 0) as stock,
                s.company_name as supplier_name
                FROM products p
                LEFT JOIN inventory i ON p.id = i.product_id
                JOIN suppliers s ON p.supplier_id = s.id
                WHERE p.status = 'active' AND COALESCE(i.quantity_on_hand, 0) <= p.min_stock_level
                ORDER BY stock ASC LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
