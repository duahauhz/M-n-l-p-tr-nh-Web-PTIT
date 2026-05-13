<?php $pageTitle = 'Sửa nhà cung cấp'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Chỉnh sửa: <?= e($supplier['company_name']) ?></h6>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?page=suppliers&action=update">
            <?= csrfField() ?>
            <input type="hidden" name="id" value="<?= $supplier['id'] ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên công ty <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" required value="<?= e($supplier['company_name']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Người liên hệ</label>
                    <input type="text" name="contact_person" class="form-control" value="<?= e($supplier['contact_person']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e($supplier['email']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="<?= e($supplier['phone']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mã số thuế</label>
                    <input type="text" name="tax_code" class="form-control" value="<?= e($supplier['tax_code']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <?php foreach (SUPPLIER_STATUS_LABELS as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $supplier['status'] === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="2"><?= e($supplier['address']) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Ghi chú</label>
                    <textarea name="notes" class="form-control" rows="2"><?= e($supplier['notes']) ?></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-shopee"><i class="fas fa-save me-2"></i>Cập nhật</button>
                <a href="index.php?page=suppliers" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
