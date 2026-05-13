<?php require VIEW_PATH . '/layouts/header.php'; ?>

<!-- Thông tin chi tiết NCC -->
<div class="row g-4">
    <!-- Cột trái: Thông tin chung -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-building fa-3x" style="color: var(--shopee-primary)"></i>
                </div>
                <h5 class="fw-bold"><?= e($supplier['company_name']) ?></h5>
                <span class="badge <?= SUPPLIER_STATUS_BADGES[$supplier['status']] ?> mb-3">
                    <?= SUPPLIER_STATUS_LABELS[$supplier['status']] ?>
                </span>

                <!-- Rating -->
                <div class="mb-3">
                    <span class="star-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= round($supplier['rating_avg']) ? '' : 'empty' ?>"></i>
                        <?php endfor; ?>
                    </span>
                    <div class="text-muted small"><?= number_format($supplier['rating_avg'], 2) ?> / 5.00</div>
                </div>

                <hr>
                <div class="text-start">
                    <p><i class="fas fa-user me-2 text-muted"></i><?= e($supplier['contact_person']) ?: '—' ?></p>
                    <p><i class="fas fa-envelope me-2 text-muted"></i><?= e($supplier['email']) ?: '—' ?></p>
                    <p><i class="fas fa-phone me-2 text-muted"></i><?= e($supplier['phone']) ?: '—' ?></p>
                    <p><i class="fas fa-map-marker-alt me-2 text-muted"></i><?= e($supplier['address']) ?: '—' ?></p>
                    <p><i class="fas fa-file-invoice me-2 text-muted"></i>MST: <?= e($supplier['tax_code']) ?: '—' ?></p>
                </div>
                <?php if (!empty($supplier['notes'])): ?>
                <hr>
                <div class="text-start">
                    <small class="text-muted"><strong>Ghi chú:</strong></small>
                    <p class="small"><?= e($supplier['notes']) ?></p>
                </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="index.php?page=suppliers&action=edit&id=<?= $supplier['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Sửa
                    </a>
                    <a href="index.php?page=reviews&action=create&supplier_id=<?= $supplier['id'] ?>" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-star me-1"></i>Đánh giá
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột phải: Đơn hàng & Đánh giá -->
    <div class="col-lg-8">
        <!-- Lịch sử đơn hàng -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice me-2"></i>Lịch sử đơn hàng</h6>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày tạo</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Người tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $o): ?>
                            <tr>
                                <td>
                                    <a href="index.php?page=purchase_orders&action=show&id=<?= $o['id'] ?>">
                                        <code><?= e($o['order_code']) ?></code>
                                    </a>
                                </td>
                                <td><?= formatDate($o['created_at']) ?></td>
                                <td><?= formatMoney($o['total_amount']) ?></td>
                                <td><span class="badge <?= PO_STATUS_BADGES[$o['status']] ?>"><?= PO_STATUS_LABELS[$o['status']] ?></span></td>
                                <td><?= e($o['created_by_name']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state py-4"><p class="text-muted">Chưa có đơn hàng nào.</p></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Đánh giá -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-star-half-alt me-2"></i>Đánh giá</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $r): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong><?= e($r['reviewer_name']) ?></strong>
                            <small class="text-muted"><?= formatDate($r['created_at'], true) ?></small>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6"><small>Chất lượng: </small>
                                <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_quality']?'':'empty').'"></i>'; ?></span>
                            </div>
                            <div class="col-6"><small>Giao hàng: </small>
                                <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_delivery']?'':'empty').'"></i>'; ?></span>
                            </div>
                            <div class="col-6"><small>Giá cả: </small>
                                <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_price']?'':'empty').'"></i>'; ?></span>
                            </div>
                            <div class="col-6"><small>Dịch vụ: </small>
                                <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_service']?'':'empty').'"></i>'; ?></span>
                            </div>
                        </div>
                        <?php if ($r['comment']): ?>
                        <p class="mt-2 mb-0 text-muted small">"<?= e($r['comment']) ?>"</p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state py-3"><p class="text-muted">Chưa có đánh giá.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
