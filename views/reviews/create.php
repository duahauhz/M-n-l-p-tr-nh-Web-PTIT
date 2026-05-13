<?php $pageTitle = 'Thêm đánh giá'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-star me-2"></i>Đánh giá nhà cung cấp</h6></div>
    <div class="card-body">
        <form method="POST" action="index.php?page=reviews&action=store">
            <?= csrfField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nhà cung cấp <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Chọn NCC --</option>
                        <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $selectedSupplier == $s['id'] ? 'selected' : '' ?>><?= e($s['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Đơn hàng liên quan (không bắt buộc)</label>
                    <input type="text" name="purchase_order_id" class="form-control" placeholder="Nhập ID đơn hàng (nếu có)">
                </div>

                <!-- 4 tiêu chí đánh giá -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Chất lượng hàng</label>
                    <select name="rating_quality" class="form-select">
                        <option value="5">⭐⭐⭐⭐⭐ Xuất sắc</option>
                        <option value="4">⭐⭐⭐⭐ Tốt</option>
                        <option value="3">⭐⭐⭐ Trung bình</option>
                        <option value="2">⭐⭐ Kém</option>
                        <option value="1">⭐ Rất kém</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Thời gian giao hàng</label>
                    <select name="rating_delivery" class="form-select">
                        <option value="5">⭐⭐⭐⭐⭐ Rất nhanh</option>
                        <option value="4">⭐⭐⭐⭐ Đúng hẹn</option>
                        <option value="3">⭐⭐⭐ Chấp nhận được</option>
                        <option value="2">⭐⭐ Chậm</option>
                        <option value="1">⭐ Rất chậm</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Giá cả cạnh tranh</label>
                    <select name="rating_price" class="form-select">
                        <option value="5">⭐⭐⭐⭐⭐ Rất tốt</option>
                        <option value="4">⭐⭐⭐⭐ Hợp lý</option>
                        <option value="3">⭐⭐⭐ Trung bình</option>
                        <option value="2">⭐⭐ Đắt</option>
                        <option value="1">⭐ Rất đắt</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Dịch vụ hỗ trợ</label>
                    <select name="rating_service" class="form-select">
                        <option value="5">⭐⭐⭐⭐⭐ Tuyệt vời</option>
                        <option value="4">⭐⭐⭐⭐ Tốt</option>
                        <option value="3">⭐⭐⭐ Bình thường</option>
                        <option value="2">⭐⭐ Kém</option>
                        <option value="1">⭐ Không hỗ trợ</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Nhận xét</label>
                    <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-shopee"><i class="fas fa-paper-plane me-2"></i>Gửi đánh giá</button>
                <a href="index.php?page=reviews" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
