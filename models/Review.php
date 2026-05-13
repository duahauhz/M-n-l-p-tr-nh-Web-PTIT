<?php
/**
 * Model: Review (Đánh giá nhà cung cấp)
 */

class Review {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Lấy tất cả đánh giá có phân trang
     */
    public function getAll($supplierId = '', $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        $where = "WHERE 1=1";
        $params = [];

        if (!empty($supplierId)) {
            $where .= " AND sr.supplier_id = :supplier_id";
            $params['supplier_id'] = $supplierId;
        }

        $countSql = "SELECT COUNT(*) FROM supplier_reviews sr $where";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        $sql = "SELECT sr.*, s.company_name as supplier_name, u.full_name as reviewer_name,
                po.order_code
                FROM supplier_reviews sr
                JOIN suppliers s ON sr.supplier_id = s.id
                JOIN users u ON sr.user_id = u.id
                LEFT JOIN purchase_orders po ON sr.purchase_order_id = po.id
                $where
                ORDER BY sr.created_at DESC LIMIT :limit OFFSET :offset";
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
     * Thêm đánh giá mới
     */
    public function create($data) {
        $sql = "INSERT INTO supplier_reviews (supplier_id, user_id, purchase_order_id,
                rating_quality, rating_delivery, rating_price, rating_service, comment)
                VALUES (:supplier_id, :user_id, :purchase_order_id,
                :rating_quality, :rating_delivery, :rating_price, :rating_service, :comment)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'supplier_id'       => $data['supplier_id'],
            'user_id'           => $data['user_id'],
            'purchase_order_id' => $data['purchase_order_id'] ?: null,
            'rating_quality'    => $data['rating_quality'],
            'rating_delivery'   => $data['rating_delivery'],
            'rating_price'      => $data['rating_price'],
            'rating_service'    => $data['rating_service'],
            'comment'           => $data['comment'],
        ]);

        // Cập nhật rating trung bình bằng Stored Procedure
        $this->pdo->prepare("CALL sp_update_supplier_rating(:sid)")
                   ->execute(['sid' => $data['supplier_id']]);

        return $this->pdo->lastInsertId();
    }
}
