<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-folder-open me-2"></i><?= $category ? 'Sửa danh mục' : 'Thêm danh mục mới' ?>
        </h6>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?page=categories&action=<?= $category ? 'update' : 'store' ?>">
            <?= csrfField() ?>
            <?php if ($category): ?>
                <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?= e($category['name'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Danh mục cha</label>
                    <select name="parent_id" class="form-select">
                        <option value="">— Không (danh mục gốc) —</option>
                        <?php foreach ($parents as $p): ?>
                            <?php if (!$category || $p['id'] != $category['id']): ?>
                            <option value="<?= $p['id'] ?>" <?= ($category['parent_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                <?= e($p['name']) ?>
                            </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Thứ tự sắp xếp</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $category['sort_order'] ?? 0 ?>">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                               <?= ($category['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Hoạt động</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="2"><?= e($category['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-shopee"><i class="fas fa-save me-2"></i>Lưu</button>
                <a href="index.php?page=categories" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
