<?php $pageTitle = 'Thêm sản phẩm'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm mới</h6></div>
    <div class="card-body">
        <form method="POST" action="index.php?page=products&action=store" enctype="multipart/form-data">
            <?= csrfField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Mã SKU <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control" required placeholder="VD: HP-BAN-002">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Đơn vị</label>
                    <select name="unit" class="form-select">
                        <option value="cái">Cái</option><option value="bộ">Bộ</option>
                        <option value="kg">Kg</option><option value="hộp">Hộp</option>
                        <option value="thùng">Thùng</option><option value="ram">Ram</option>
                        <option value="cuộn">Cuộn</option><option value="tấn">Tấn</option>
                        <option value="ống">Ống</option><option value="cây">Cây</option>
                        <option value="tấm">Tấm</option><option value="can">Can</option>
                        <option value="gói">Gói</option><option value="mét">Mét</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nhà cung cấp <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Chọn NCC --</option>
                        <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= e($s['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Không phân loại --</option>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= ($c['parent_id'] ? '  └ ' : '') . e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Đơn giá (VNĐ)</label>
                    <input type="number" name="unit_price" class="form-control" min="0" step="1000" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Mức tồn kho tối thiểu</label>
                    <input type="number" name="min_stock_level" class="form-control" min="0" value="10">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ảnh sản phẩm</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-shopee"><i class="fas fa-save me-2"></i>Lưu</button>
                <a href="index.php?page=products" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
