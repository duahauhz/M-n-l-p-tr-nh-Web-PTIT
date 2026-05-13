<?php require VIEW_PATH . '/layouts/header.php'; ?>

<!-- Timeline trạng thái đơn hàng -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <?php
            $steps = ['draft' => 'Nháp', 'pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'received' => 'Đã nhận hàng'];
            $statusOrder = array_keys($steps);
            $currentIdx = array_search($order['status'], $statusOrder);
            $isCancelled = $order['status'] === 'cancelled';
            ?>
            <?php foreach ($steps as $key => $label): ?>
                <?php
                $idx = array_search($key, $statusOrder);
                $stepClass = 'text-muted';
                $iconClass = 'far fa-circle';
                if (!$isCancelled) {
                    if ($idx < $currentIdx || ($idx === $currentIdx && $key === 'received')) {
                        $stepClass = 'text-success';
                        $iconClass = 'fas fa-check-circle';
                    } elseif ($idx === $currentIdx) {
                        $stepClass = 'text-primary fw-bold';
                        $iconClass = 'fas fa-dot-circle';
                    }
                }
                ?>
                <div class="text-center <?= $stepClass ?>" style="flex:1">
                    <i class="<?= $iconClass ?> fa-2x mb-1"></i>
                    <div class="small"><?= $label ?></div>
                </div>
                <?php if ($key !== 'received'): ?>
                <div style="flex:0.5;height:2px;background:#dee2e6;align-self:center;margin-top:-15px"></div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($isCancelled): ?>
            <div class="text-center text-danger" style="flex:1">
                <i class="fas fa-times-circle fa-2x mb-1"></i>
                <div class="small">Đã hủy</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Thông tin đơn hàng -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Thông tin đơn hàng</h6></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">Mã đơn:</td><td><code class="fw-bold"><?= e($order['order_code']) ?></code></td></tr>
                    <tr><td class="text-muted">Trạng thái:</td><td><span class="badge <?= PO_STATUS_BADGES[$order['status']] ?>"><?= PO_STATUS_LABELS[$order['status']] ?></span></td></tr>
                    <tr><td class="text-muted">NCC:</td><td><?= e($order['supplier_name']) ?></td></tr>
                    <tr><td class="text-muted">SĐT NCC:</td><td><?= e($order['supplier_phone']) ?></td></tr>
                    <tr><td class="text-muted">Người tạo:</td><td><?= e($order['created_by_name']) ?></td></tr>
                    <tr><td class="text-muted">Ngày tạo:</td><td><?= formatDate($order['created_at'], true) ?></td></tr>
                    <tr><td class="text-muted">Giao dự kiến:</td><td><?= formatDate($order['expected_delivery']) ?></td></tr>
                    <tr><td class="text-muted">Giao thực tế:</td><td><?= formatDate($order['actual_delivery']) ?></td></tr>
                    <?php if ($order['approved_by_name']): ?>
                    <tr><td class="text-muted">Người duyệt:</td><td><?= e($order['approved_by_name']) ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="text-muted fw-bold">Tổng tiền:</td><td class="fw-bold text-danger fs-5"><?= formatMoney($order['total_amount']) ?></td></tr>
                </table>
                <?php if ($order['notes']): ?>
                <hr><small class="text-muted"><strong>Ghi chú:</strong> <?= e($order['notes']) ?></small>
                <?php endif; ?>

                <!-- Nút hành động theo trạng thái -->
                <hr>
                <div class="d-flex flex-wrap gap-2">
                    <?php if ($order['status'] === 'draft'): ?>
                    <form method="POST" action="index.php?page=purchase_orders&action=submit">
                        <?= csrfField() ?><input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-shopee btn-sm" onclick="return confirm('Gửi duyệt đơn hàng này?')">
                            <i class="fas fa-paper-plane me-1"></i> Gửi duyệt
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if ($order['status'] === 'pending'): ?>
                    <form method="POST" action="index.php?page=purchase_orders&action=approve">
                        <?= csrfField() ?><input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Duyệt đơn hàng này?')">
                            <i class="fas fa-check me-1"></i> Duyệt
                        </button>
                    </form>
                    <form method="POST" action="index.php?page=purchase_orders&action=reject">
                        <?= csrfField() ?><input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Từ chối đơn hàng này?')">
                            <i class="fas fa-times me-1"></i> Từ chối
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if ($order['status'] === 'approved'): ?>
                    <form method="POST" action="index.php?page=purchase_orders&action=receive">
                        <?= csrfField() ?><input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-info btn-sm text-white" onclick="return confirm('Xác nhận đã nhận hàng? Tồn kho sẽ được cập nhật tự động.')">
                            <i class="fas fa-box-open me-1"></i> Nhận hàng
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if ($order['status'] === 'received'): ?>
                    <a href="index.php?page=reports&action=print_order&id=<?= $order['id'] ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="fas fa-print me-1"></i> In đơn
                    </a>
                    <a href="index.php?page=reviews&action=create&supplier_id=<?= $order['supplier_id'] ?>" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-star me-1"></i> Đánh giá NCC
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết items -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Chi tiết sản phẩm</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th>SKU</th>
                                <th>Đơn vị</th>
                                <th>SL đặt</th>
                                <th>SL nhận</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $i => $item): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= e($item['product_name']) ?></td>
                                <td><code><?= e($item['sku']) ?></code></td>
                                <td><?= e($item['unit']) ?></td>
                                <td><?= $item['quantity_ordered'] ?></td>
                                <td>
                                    <?php if ($item['quantity_received'] > 0): ?>
                                        <span class="text-success"><?= $item['quantity_received'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= formatMoney($item['unit_price']) ?></td>
                                <td class="fw-semibold"><?= formatMoney($item['subtotal']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="7" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="fw-bold text-danger"><?= formatMoney($order['total_amount']) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
