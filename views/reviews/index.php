<?php $pageTitle = 'Đánh giá nhà cung cấp'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <form method="GET" class="d-flex gap-2">
        <input type="hidden" name="page" value="reviews">
        <select name="supplier_id" class="form-select form-select-sm" style="width:250px" onchange="this.form.submit()">
            <option value="">Tất cả NCC</option>
            <?php foreach ($suppliers as $s): ?>
            <option value="<?= $s['id'] ?>" <?= $supplierId == $s['id'] ? 'selected' : '' ?>><?= e($s['company_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <a href="index.php?page=reviews&action=create" class="btn btn-shopee btn-sm"><i class="fas fa-plus me-1"></i> Thêm đánh giá</a>
</div>

<div class="row g-4">
    <?php if (!empty($result['data'])): ?>
        <?php foreach ($result['data'] as $r): ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0"><?= e($r['supplier_name']) ?></h6>
                        <small class="text-muted"><?= formatDate($r['created_at'], true) ?></small>
                    </div>
                    <?php if ($r['order_code']): ?>
                    <small class="text-muted">Đơn hàng: <code><?= e($r['order_code']) ?></code></small>
                    <?php endif; ?>
                    <div class="row mt-3">
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Chất lượng</small>
                            <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_quality']?'':'empty').'"></i>'; ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Giao hàng</small>
                            <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_delivery']?'':'empty').'"></i>'; ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Giá cả</small>
                            <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_price']?'':'empty').'"></i>'; ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Dịch vụ</small>
                            <span class="star-rating"><?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$r['rating_service']?'':'empty').'"></i>'; ?></span>
                        </div>
                    </div>
                    <?php if ($r['comment']): ?>
                    <div class="mt-2 p-2 bg-light rounded"><small>"<?= e($r['comment']) ?>"</small></div>
                    <?php endif; ?>
                    <div class="mt-2"><small class="text-muted">Người đánh giá: <?= e($r['reviewer_name']) ?></small></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="col-12"><div class="empty-state"><i class="fas fa-star-half-alt"></i><p>Chưa có đánh giá nào.</p></div></div>
    <?php endif; ?>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
