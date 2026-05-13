<?php $pageTitle = 'Quản lý nhà cung cấp'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<!-- Search & Filter -->
<div class="search-box">
    <form method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="suppliers">
        <div class="col-md-5">
            <label class="form-label fw-semibold">Tìm kiếm</label>
            <input type="text" name="search" class="form-control" placeholder="Tên, email, SĐT..."
                   value="<?= e($search) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="">Tất cả</option>
                <?php foreach (SUPPLIER_STATUS_LABELS as $key => $label): ?>
                <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-shopee"><i class="fas fa-search me-1"></i> Tìm</button>
            <a href="index.php?page=suppliers" class="btn btn-outline-secondary ms-2"><i class="fas fa-redo me-1"></i> Reset</a>
            <a href="index.php?page=suppliers&action=create" class="btn btn-success ms-2">
                <i class="fas fa-plus me-1"></i> Thêm mới
            </a>
        </div>
    </form>
</div>

<!-- Kết quả: Tìm thấy X nhà cung cấp -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">Tìm thấy <strong><?= $result['total'] ?></strong> nhà cung cấp</span>
</div>

<!-- Bảng danh sách -->
<div class="card">
    <div class="card-body p-0">
        <?php if (!empty($result['data'])): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên công ty</th>
                        <th>Liên hệ</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Đánh giá</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['data'] as $index => $s): ?>
                    <tr>
                        <td><?= ($result['page'] - 1) * $result['limit'] + $index + 1 ?></td>
                        <td>
                            <a href="index.php?page=suppliers&action=show&id=<?= $s['id'] ?>" class="fw-semibold">
                                <?= e($s['company_name']) ?>
                            </a>
                        </td>
                        <td><?= e($s['contact_person']) ?></td>
                        <td><?= e($s['email']) ?></td>
                        <td><?= e($s['phone']) ?></td>
                        <td>
                            <span class="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= round($s['rating_avg']) ? '' : 'empty' ?>"></i>
                                <?php endfor; ?>
                            </span>
                            <small class="text-muted">(<?= number_format($s['rating_avg'], 1) ?>)</small>
                        </td>
                        <td>
                            <span class="badge <?= SUPPLIER_STATUS_BADGES[$s['status']] ?>">
                                <?= SUPPLIER_STATUS_LABELS[$s['status']] ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?page=suppliers&action=show&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-info btn-action" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?page=suppliers&action=edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary btn-action" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="index.php?page=suppliers&action=delete" class="d-inline" id="delete-form-<?= $s['id'] ?>">
                                <?= csrfField() ?>
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action"
                                        onclick="confirmDelete('delete-form-<?= $s['id'] ?>', '<?= e($s['company_name']) ?>')"
                                        title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-truck"></i>
            <p>Chưa có nhà cung cấp nào.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Phân trang -->
<?php if ($result['totalPages'] > 1): ?>
<nav class="mt-3">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $result['totalPages']; $i++): ?>
        <li class="page-item <?= $i == $result['page'] ? 'active' : '' ?>">
            <a class="page-link" href="index.php?page=suppliers&search=<?= urlencode($search) ?>&status=<?= $status ?>&p=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
