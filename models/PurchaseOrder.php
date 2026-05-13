<?php
/**
 * Model: PurchaseOrder (Đơn hàng mua)
 * Workflow: draft → pending → approved → received / cancelled
 */

class PurchaseOrder {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Lấy danh sách đơn hàng có phân trang và lọc
     */
    public function getAll($status = '', $supplierId = '', $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        $where = "WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $where .= " AND po.status = :status";
            $params['status'] = $status;
        }
        if (!empty($supplierId)) {
            $where .= " AND po.supplier_id = :supplier_id";
            $params['supplier_id'] = $supplierId;
        }

        $countSql = "SELECT COUNT(*) FROM purchase_orders po $where";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        $sql = "SELECT po.*, s.company_name as supplier_name, u.full_name as created_by_name
                FROM purchase_orders po
                JOIN suppliers s ON po.supplier_id = s.id
                JOIN users u ON po.created_by = u.id
                $where
                ORDER BY po.created_at DESC LIMIT :limit OFFSET :offset";
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
     * Lấy chi tiết 1 đơn hàng
     */
    public function getById($id) {
        $sql = "SELECT po.*, s.company_name as supplier_name, s.phone as supplier_phone,
                s.email as supplier_email, s.address as supplier_address,
                u1.full_name as created_by_name,
                u2.full_name as approved_by_name
                FROM purchase_orders po
                JOIN suppliers s ON po.supplier_id = s.id
                JOIN users u1 ON po.created_by = u1.id
                LEFT JOIN users u2 ON po.approved_by = u2.id
                WHERE po.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Lấy danh sách items của đơn hàng
     */
    public function getItems($orderId) {
        $sql = "SELECT poi.*, p.name as product_name, p.sku, p.unit
                FROM purchase_order_items poi
                JOIN products p ON poi.product_id = p.id
                WHERE poi.purchase_order_id = :oid
                ORDER BY poi.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['oid' => $orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Tạo đơn hàng mới (với items) - sử dụng Transaction
     */
    public function create($data, $items) {
        $this->pdo->beginTransaction();
        try {
            // Tạo mã đơn hàng tự động bằng Stored Procedure
            $stmt = $this->pdo->prepare("CALL sp_generate_order_code(@code)");
            $stmt->execute();
            $code = $this->pdo->query("SELECT @code")->fetchColumn();

            // Insert đơn hàng
            $sql = "INSERT INTO purchase_orders (order_code, supplier_id, created_by, status, notes, expected_delivery)
                    VALUES (:order_code, :supplier_id, :created_by, 'draft', :notes, :expected_delivery)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'order_code'        => $code,
                'supplier_id'       => $data['supplier_id'],
                'created_by'        => $data['created_by'],
                'notes'             => $data['notes'],
                'expected_delivery' => $data['expected_delivery'] ?: null,
            ]);
            $orderId = $this->pdo->lastInsertId();

            // Insert từng item
            $itemSql = "INSERT INTO purchase_order_items (purchase_order_id, product_id, quantity_ordered, unit_price, subtotal)
                        VALUES (:oid, :pid, :qty, :price, :subtotal)";
            $itemStmt = $this->pdo->prepare($itemSql);
            foreach ($items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $itemStmt->execute([
                    'oid'      => $orderId,
                    'pid'      => $item['product_id'],
                    'qty'      => $item['quantity'],
                    'price'    => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Tính lại tổng tiền bằng Stored Procedure
            $this->pdo->prepare("CALL sp_recalculate_order_total(:oid)")->execute(['oid' => $orderId]);

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Chuyển trạng thái đơn hàng: draft → pending
     */
    public function submit($id) {
        $sql = "UPDATE purchase_orders SET status = 'pending' WHERE id = :id AND status = 'draft'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Duyệt đơn: pending → approved
     */
    public function approve($id, $approvedBy) {
        $sql = "UPDATE purchase_orders SET status = 'approved', approved_by = :approved_by
                WHERE id = :id AND status = 'pending'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'approved_by' => $approvedBy]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Từ chối đơn: pending → cancelled
     */
    public function reject($id) {
        $sql = "UPDATE purchase_orders SET status = 'cancelled'
                WHERE id = :id AND status = 'pending'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Nhận hàng: approved → received (gọi Stored Procedure)
     */
    public function receive($id, $userId) {
        $stmt = $this->pdo->prepare("CALL sp_receive_order(:oid, :uid)");
        return $stmt->execute(['oid' => $id, 'uid' => $userId]);
    }

    /**
     * Đếm đơn hàng theo trạng thái
     */
    public function countByStatus($status) {
        $sql = "SELECT COUNT(*) FROM purchase_orders WHERE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetchColumn();
    }

    /**
     * Tổng chi tiêu (đơn đã nhận hàng)
     */
    public function totalSpent() {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM purchase_orders WHERE status = 'received'");
        return $stmt->fetchColumn();
    }

    /**
     * Chi tiêu theo tháng (12 tháng gần nhất)
     */
    public function monthlySpending() {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
                       DATE_FORMAT(created_at, '%m/%Y') as label,
                       SUM(total_amount) as total
                FROM purchase_orders
                WHERE status = 'received' AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY month, label ORDER BY month";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Phân bố đơn hàng theo trạng thái
     */
    public function statusDistribution() {
        $sql = "SELECT status, COUNT(*) as count FROM purchase_orders GROUP BY status";
        return $this->pdo->query($sql)->fetchAll();
    }
}
