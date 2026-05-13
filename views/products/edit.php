<?php $pageTitle = 'Sửa sản phẩm'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Sửa: <?= e($product['name']) ?></h6></div>
    <div class="card-body">
        <form method="POST" action="index.php?page=products&action=update" enctype="multipart/form-data">
            <?= csrfField() ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?= e($product['name']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Mã SKU <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control" required value="<?= e($product['sku']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Đơn vị</label>
                    <select name="unit" class="form-select">
                        <?php foreach (['cái','bộ','kg','hộp','thùng','ram','cuộn','tấn','ống','cây','tấm','can','gói','mét'] as $u): ?>
                        <option value="<?= $u ?>" <?= $product['unit'] === $u ? 'selected' : '' ?>><?= ucfirst($u) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nhà cung cấp <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" required>
                        <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $product['supplier_id'] == $s['id'] ? 'selected' : '' ?>><?= e($s['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Không phân loại --</option>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>><?= ($c['parent_id'] ? '  └ ' : '') . e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Đơn giá (VNĐ)</label>
                    <input type="number" name="unit_price" class="form-control" min="0" step="1000" value="<?= $product['unit_price'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Mức tồn kho tối thiểu</label>
                    <input type="number" name="min_stock_level" class="form-control" min="0" value="<?= $product['min_stock_level'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Đang bán</option>
                        <option value="discontinued" <?= $product['status'] === 'discontinued' ? 'selected' : '' ?>>Ngưng</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ảnh mới (để trống nếu không đổi)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3"><?= e($product['description']) ?></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-shopee"><i class="fas fa-save me-2"></i>Cập nhật</button>
                <a href="index.php?page=products" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
