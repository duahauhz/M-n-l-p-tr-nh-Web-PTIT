<?php
/**
 * Model: Category (Danh mục sản phẩm)
 * Hỗ trợ danh mục phân cấp cha-con
 */

class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Lấy tất cả danh mục
     */
    public function getAll() {
        $sql = "SELECT c.*, p.name as parent_name,
                (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count
                FROM categories c
                LEFT JOIN categories p ON c.parent_id = p.id
                ORDER BY COALESCE(p.sort_order, c.sort_order), COALESCE(p.name, c.name), c.parent_id IS NOT NULL, c.sort_order, c.name";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Lấy danh mục active (cho dropdown)
     */
    public function getAllActive() {
        $sql = "SELECT id, name, parent_id FROM categories WHERE is_active = 1 ORDER BY sort_order, name";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Lấy danh mục cha (parent_id IS NULL)
     */
    public function getParentCategories() {
        $sql = "SELECT id, name FROM categories WHERE parent_id IS NULL AND is_active = 1 ORDER BY sort_order, name";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Lấy 1 danh mục theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Thêm danh mục
     */
    public function create($data) {
        $sql = "INSERT INTO categories (name, slug, description, parent_id, sort_order, is_active)
                VALUES (:name, :slug, :description, :parent_id, :sort_order, :is_active)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name'        => $data['name'],
            'slug'        => createSlug($data['name']),
            'description' => $data['description'],
            'parent_id'   => $data['parent_id'] ?: null,
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => $data['is_active'] ?? 1,
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Cập nhật danh mục
     */
    public function update($id, $data) {
        $sql = "UPDATE categories SET
                name = :name, slug = :slug, description = :description,
                parent_id = :parent_id, sort_order = :sort_order, is_active = :is_active
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'slug'        => createSlug($data['name']),
            'description' => $data['description'],
            'parent_id'   => $data['parent_id'] ?: null,
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => $data['is_active'] ?? 1,
        ]);
    }

    /**
     * Xóa danh mục (kiểm tra ràng buộc)
     */
    public function delete($id) {
        // Kiểm tra có sản phẩm liên kết không
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $check->execute(['id' => $id]);
        if ($check->fetchColumn() > 0) return false;

        // Kiểm tra có danh mục con không
        $check2 = $this->pdo->prepare("SELECT COUNT(*) FROM categories WHERE parent_id = :id");
        $check2->execute(['id' => $id]);
        if ($check2->fetchColumn() > 0) return false;

        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
