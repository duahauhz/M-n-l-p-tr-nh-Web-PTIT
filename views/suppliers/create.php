<?php $pageTitle = 'Thêm nhà cung cấp'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Thêm nhà cung cấp mới</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?page=suppliers&action=store" id="supplier-form">
            <?= csrfField() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên công ty <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" required placeholder="VD: Công ty TNHH ABC">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Người liên hệ</label>
                    <input type="text" name="contact_person" class="form-control" placeholder="VD: Nguyễn Văn A">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@company.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" placeholder="028-xxxx-xxxx">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mã số thuế</label>
                    <input type="text" name="tax_code" class="form-control" placeholder="0301234567">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Ngưng hoạt động</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Địa chỉ đầy đủ..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Ghi chú</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Ghi chú thêm..."></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-shopee"><i class="fas fa-save me-2"></i>Lưu</button>
                <a href="index.php?page=suppliers" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
