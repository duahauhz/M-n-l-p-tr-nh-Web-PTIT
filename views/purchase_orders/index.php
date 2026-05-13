<?php $pageTitle = 'Quản lý đơn hàng mua'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="search-box">
    <form method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="purchase_orders">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="">Tất cả</option>
                <?php foreach (PO_STATUS_LABELS as $key => $label): ?>
                <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Nhà cung cấp</label>
            <select name="supplier_id" class="form-select">
                <option value="">Tất cả</option>
                <?php foreach ($suppliers as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $supplierId == $s['id'] ? 'selected' : '' ?>><?= e($s['company_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-shopee"><i class="fas fa-filter me-1"></i> Lọc</button>
            <a href="index.php?page=purchase_orders" class="btn btn-outline-secondary ms-2"><i class="fas fa-redo me-1"></i> Reset</a>
            <a href="index.php?page=purchase_orders&action=create" class="btn btn-success ms-2"><i class="fas fa-plus me-1"></i> Tạo đơn</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (!empty($result['data'])): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Nhà cung cấp</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Người tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['data'] as $o): ?>
                    <tr>
                        <td><a href="index.php?page=purchase_orders&action=show&id=<?= $o['id'] ?>"><code><?= e($o['order_code']) ?></code></a></td>
                        <td><?= e($o['supplier_name']) ?></td>
                        <td class="fw-semibold"><?= formatMoney($o['total_amount']) ?></td>
                        <td><span class="badge <?= PO_STATUS_BADGES[$o['status']] ?>"><?= PO_STATUS_LABELS[$o['status']] ?></span></td>
                        <td><?= formatDate($o['created_at']) ?></td>
                        <td><?= e($o['created_by_name']) ?></td>
                        <td>
                            <a href="index.php?page=purchase_orders&action=show&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-info btn-action"><i class="fas fa-eye"></i></a>
                            <?php if ($o['status'] === 'received'): ?>
                            <a href="index.php?page=reports&action=print_order&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-secondary btn-action" target="_blank"><i class="fas fa-print"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-file-invoice"></i><p>Chưa có đơn hàng nào.</p></div>
        <?php endif; ?>
    </div>
</div>

<?php if ($result['totalPages'] > 1): ?>
<nav class="mt-3"><ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $result['totalPages']; $i++): ?>
    <li class="page-item <?= $i == $result['page'] ? 'active' : '' ?>">
        <a class="page-link" href="index.php?page=purchase_orders&status=<?= $status ?>&supplier_id=<?= $supplierId ?>&p=<?= $i ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
</ul></nav>
<?php endif; ?>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
