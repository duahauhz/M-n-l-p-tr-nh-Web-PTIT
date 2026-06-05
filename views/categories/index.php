<?php $pageTitle = 'Quản lý danh mục'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <span class="text-muted">Tổng: <strong><?= count($categories) ?></strong> danh mục</span>
    <a href="index.php?page=categories&action=create" class="btn btn-shopee btn-sm">
        <i class="fas fa-plus me-1"></i> Thêm danh mục
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 category-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên danh mục</th>
                        <th>Danh mục cha</th>
                        <th>Số sản phẩm</th>
                        <th>Thứ tự</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $i => $c): ?>
                    <tr>
                        <td data-label="#"><?= $i + 1 ?></td>
                        <td class="category-name-cell" data-label="Danh mục">
                            <div class="category-name <?= $c['parent_id'] ? 'is-child' : '' ?>">
                                <strong><?= e($c['name']) ?></strong>
                            </div>
                        </td>
                        <td class="text-nowrap" data-label="Danh mục cha"><?= $c['parent_name'] ? e($c['parent_name']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-label="Sản phẩm"><span class="badge bg-light text-dark"><?= $c['product_count'] ?></span></td>
                        <td data-label="Thứ tự"><?= $c['sort_order'] ?></td>
                        <td data-label="Trạng thái">
                            <?php if ($c['is_active']): ?>
                                <span class="badge bg-success">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-nowrap category-actions" data-label="Hành động">
                            <a href="index.php?page=categories&action=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="index.php?page=categories&action=delete" class="d-inline" id="del-cat-<?= $c['id'] ?>">
                                <?= csrfField() ?>
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action"
                                        onclick="confirmDelete('del-cat-<?= $c['id'] ?>', '<?= e($c['name']) ?>')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
