<?php $pageTitle = 'Quản lý sản phẩm'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="search-box">
    <form method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="products">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Tìm kiếm</label>
            <input type="text" name="search" class="form-control" placeholder="Tên, SKU..." value="<?= e($search) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Danh mục</label>
            <select name="category_id" class="form-select">
                <option value="">Tất cả</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $categoryId == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Nhà cung cấp</label>
            <select name="supplier_id" class="form-select">
                <option value="">Tất cả</option>
                <?php foreach ($suppliers as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $supplierId == $s['id'] ? 'selected' : '' ?>><?= e($s['company_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-shopee"><i class="fas fa-search me-1"></i> Tìm</button>
            <a href="index.php?page=products&action=create" class="btn btn-success ms-2"><i class="fas fa-plus me-1"></i> Thêm</a>
        </div>
    </form>
</div>

<div class="d-flex justify-content-between mb-2">
    <span class="text-muted">Tìm thấy <strong><?= $result['total'] ?></strong> sản phẩm</span>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (!empty($result['data'])): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="min-width: 1000px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SKU</th>
                        <th>Tên sản phẩm</th>
                        <th>NCC</th>
                        <th>Danh mục</th>
                        <th>Đơn giá</th>
                        <th>Tồn kho</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['data'] as $i => $p): ?>
                    <tr>
                        <td><?= ($result['page'] - 1) * $result['limit'] + $i + 1 ?></td>
                        <td><code><?= e($p['sku']) ?></code></td>
                        <td><?= e($p['name']) ?></td>
                        <td><small><?= e($p['supplier_name']) ?></small></td>
                        <td><small><?= e($p['category_name'] ?? '—') ?></small></td>
                        <td><?= formatMoney($p['unit_price']) ?></td>
                        <td>
                            <?php if ($p['stock'] <= $p['min_stock_level']): ?>
                                <span class="text-danger fw-bold"><?= $p['stock'] ?></span>
                                <i class="fas fa-exclamation-triangle text-warning ms-1" title="Sắp hết"></i>
                            <?php else: ?>
                                <span class="text-success"><?= $p['stock'] ?></span>
                            <?php endif; ?>
                            <small class="text-muted"><?= e($p['unit']) ?></small>
                        </td>
                        <td>
                            <a href="index.php?page=products&action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="index.php?page=products&action=delete" class="d-inline" id="del-prod-<?= $p['id'] ?>">
                                <?= csrfField() ?>
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action"
                                        onclick="confirmDelete('del-prod-<?= $p['id'] ?>', '<?= e($p['name']) ?>')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-box-open"></i><p>Chưa có sản phẩm nào.</p></div>
        <?php endif; ?>
    </div>
</div>

<?php if ($result['totalPages'] > 1): ?>
<nav class="mt-3"><ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $result['totalPages']; $i++): ?>
    <li class="page-item <?= $i == $result['page'] ? 'active' : '' ?>">
        <a class="page-link" href="index.php?page=products&search=<?= urlencode($search) ?>&category_id=<?= $categoryId ?>&supplier_id=<?= $supplierId ?>&p=<?= $i ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
</ul></nav>
<?php endif; ?>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
