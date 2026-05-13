<?php $pageTitle = 'Tạo đơn hàng mua'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice me-2"></i>Tạo đơn hàng mua mới</h6></div>
    <div class="card-body">
        <!-- Bước 1: Chọn NCC -->
        <div class="mb-4">
            <label class="form-label fw-semibold">1. Chọn nhà cung cấp <span class="text-danger">*</span></label>
            <select class="form-select" onchange="loadProductsBySupplier(this.value)" id="select-supplier">
                <option value="">-- Chọn NCC để xem sản phẩm --</option>
                <?php foreach ($suppliers as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $selectedSupplier == $s['id'] ? 'selected' : '' ?>><?= e($s['company_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($selectedSupplier) && !empty($products)): ?>
        <!-- Bước 2: Form tạo đơn hàng -->
        <form method="POST" action="index.php?page=purchase_orders&action=store">
            <?= csrfField() ?>
            <input type="hidden" name="supplier_id" value="<?= e($selectedSupplier) ?>">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ngày giao hàng dự kiến</label>
                    <input type="date" name="expected_delivery" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ghi chú</label>
                    <input type="text" name="notes" class="form-control" placeholder="Ghi chú cho đơn hàng...">
                </div>
            </div>

            <!-- Bảng items -->
            <h6 class="fw-bold mb-3">2. Thêm sản phẩm vào đơn</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="width:120px">Số lượng</th>
                            <th style="width:160px">Đơn giá (VNĐ)</th>
                            <th style="width:140px">Thành tiền</th>
                            <th style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody id="order-items-body">
                        <tr id="item-row-0">
                            <td>
                                <select name="items[0][product_id]" class="form-select form-select-sm" required onchange="updatePrice(this, 0)">
                                    <option value="">-- Chọn sản phẩm --</option>
                                    <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-price="<?= $p['unit_price'] ?>">
                                        <?= e($p['name']) ?> (<?= e($p['sku']) ?>) - <?= e($p['unit']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="items[0][quantity]" class="form-control form-control-sm" min="1" value="1" required onchange="calcSubtotal(0)"></td>
                            <td><input type="number" name="items[0][unit_price]" class="form-control form-control-sm" min="0" step="1000" value="0" required onchange="calcSubtotal(0)" id="price-0"></td>
                            <td><span id="subtotal-0">0 ₫</span></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="fw-bold text-danger" id="grand-total">0 ₫</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="button" class="btn btn-outline-shopee btn-sm mb-4" onclick="addOrderItem()">
                <i class="fas fa-plus me-1"></i> Thêm sản phẩm
            </button>

            <div>
                <button type="submit" class="btn btn-shopee"><i class="fas fa-save me-2"></i>Tạo đơn hàng (Nháp)</button>
                <a href="index.php?page=purchase_orders" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>
        </form>

        <?php elseif (!empty($selectedSupplier) && empty($products)): ?>
        <div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>NCC này chưa có sản phẩm nào. Vui lòng <a href="index.php?page=products&action=create">thêm sản phẩm</a> trước.</div>
        <?php endif; ?>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
