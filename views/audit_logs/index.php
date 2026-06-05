<?php $pageTitle = 'Nhật ký hoạt động'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="search-box">
    <form method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="audit_logs">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Bảng dữ liệu</label>
            <select name="table_name" class="form-select">
                <option value="">Tất cả</option>
                <?php foreach (['users','suppliers','categories','products','purchase_orders','supplier_reviews'] as $t): ?>
                <option value="<?= $t ?>" <?= ($tableName ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Hành động</label>
            <select name="action_filter" class="form-select">
                <option value="">Tất cả</option>
                <?php foreach (['CREATE','UPDATE','DELETE','LOGIN','LOGOUT'] as $a): ?>
                <option value="<?= $a ?>" <?= ($action ?? '') === $a ? 'selected' : '' ?>><?= $a ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-shopee"><i class="fas fa-filter me-1"></i> Lọc</button>
            <a href="index.php?page=audit_logs" class="btn btn-outline-secondary ms-2"><i class="fas fa-redo"></i></a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="min-width: 900px;">
                <thead>
                    <tr>
                        <th class="text-nowrap">Thời gian</th>
                        <th class="text-nowrap">Người dùng</th>
                        <th class="text-nowrap">Hành động</th>
                        <th class="text-nowrap">Bảng</th>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">IP</th>
                        <th class="text-nowrap">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['data'] as $log): ?>
                    <tr>
                        <td class="text-nowrap"><small><?= formatDate($log['created_at'], true) ?></small></td>
                        <td class="text-nowrap"><?= e($log['user_name'] ?? 'System') ?></td>
                        <td class="text-nowrap">
                            <?php
                            $actionColors = ['CREATE'=>'success','UPDATE'=>'primary','DELETE'=>'danger','LOGIN'=>'info','LOGOUT'=>'secondary'];
                            $color = $actionColors[$log['action']] ?? 'dark';
                            ?>
                            <span class="badge bg-<?= $color ?>"><?= e($log['action']) ?></span>
                        </td>
                        <td class="text-nowrap"><code><?= e($log['table_name']) ?></code></td>
                        <td class="text-nowrap"><?= $log['record_id'] ?? '—' ?></td>
                        <td class="text-nowrap"><small class="text-muted"><?= e($log['ip_address']) ?></small></td>
                        <td class="text-nowrap">
                            <?php if ($log['new_data']): ?>
                            <button class="btn btn-sm btn-outline-secondary" onclick="alert(this.getAttribute('data-detail'))"
                                    data-detail="<?= e($log['new_data']) ?>"><i class="fas fa-eye"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($result['totalPages'] > 1): ?>
<nav class="mt-3"><ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $result['totalPages']; $i++): ?>
    <li class="page-item <?= $i == $result['page'] ? 'active' : '' ?>">
        <a class="page-link" href="index.php?page=audit_logs&table_name=<?= $tableName ?? '' ?>&action_filter=<?= $action ?? '' ?>&p=<?= $i ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
</ul></nav>
<?php endif; ?>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
