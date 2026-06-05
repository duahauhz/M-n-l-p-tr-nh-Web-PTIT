<?php $pageTitle = 'Quản lý tồn kho'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="search-box">
    <form method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="inventory">
        <div class="col-md-5">
            <label class="form-label fw-semibold">Tìm kiếm</label>
            <input type="text" name="search" class="form-control" placeholder="Tên sản phẩm, SKU..." value="<?= e($search) ?>">
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="low_stock" class="form-check-input" id="low_stock" <?= $lowStock ? 'checked' : '' ?>>
                <label class="form-check-label" for="low_stock">Chỉ hiện sắp hết hàng</label>
            </div>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-shopee"><i class="fas fa-search me-1"></i> Tìm</button>
            <a href="index.php?page=inventory" class="btn btn-outline-secondary ms-2"><i class="fas fa-redo me-1"></i> Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (!empty($result['data'])): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="min-width: 1100px;">
                <thead>
                    <tr>
                        <th class="text-nowrap">#</th>
                        <th class="text-nowrap">SKU</th>
                        <th class="text-nowrap">Sản phẩm</th>
                        <th class="text-nowrap">NCC</th>
                        <th class="text-nowrap">Đơn giá</th>
                        <th class="text-nowrap">Tồn kho</th>
                        <th class="text-nowrap">Đã đặt</th>
                        <th class="text-nowrap">Mức min</th>
                        <th class="text-nowrap">Giá trị</th>
                        <th class="text-nowrap">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['data'] as $i => $item): ?>
                    <?php $isLow = $item['quantity_on_hand'] <= $item['min_stock_level']; ?>
                    <tr class="<?= $isLow ? 'table-warning' : '' ?>">
                        <td class="text-nowrap"><?= ($result['page'] - 1) * $result['limit'] + $i + 1 ?></td>
                        <td class="text-nowrap"><code><?= e($item['sku']) ?></code></td>
                        <td><?= e($item['name']) ?></td>
                        <td><small><?= e($item['supplier_name']) ?></small></td>
                        <td class="text-nowrap"><?= formatMoney($item['unit_price']) ?></td>
                        <td class="text-nowrap fw-bold <?= $isLow ? 'text-danger' : 'text-success' ?>"><?= $item['quantity_on_hand'] ?> <?= e($item['unit']) ?></td>
                        <td class="text-nowrap"><?= $item['quantity_reserved'] ?></td>
                        <td class="text-nowrap"><?= $item['min_stock_level'] ?></td>
                        <td class="text-nowrap"><?= formatMoney($item['quantity_on_hand'] * $item['unit_price']) ?></td>
                        <td class="text-nowrap">
                            <?php if ($item['quantity_on_hand'] == 0): ?>
                                <span class="badge bg-danger">Hết hàng</span>
                            <?php elseif ($isLow): ?>
                                <span class="badge bg-warning text-dark">Sắp hết</span>
                            <?php else: ?>
                                <span class="badge bg-success">Đủ hàng</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-warehouse"></i><p>Không có dữ liệu.</p></div>
        <?php endif; ?>
    </div>
</div>

<?php if ($result['totalPages'] > 1): ?>
<nav class="mt-3"><ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $result['totalPages']; $i++): ?>
    <li class="page-item <?= $i == $result['page'] ? 'active' : '' ?>">
        <a class="page-link" href="index.php?page=inventory&search=<?= urlencode($search) ?>&<?= $lowStock ? 'low_stock=1&' : '' ?>p=<?= $i ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
</ul></nav>
<?php endif; ?>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
