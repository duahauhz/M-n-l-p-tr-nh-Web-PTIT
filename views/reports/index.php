<?php $pageTitle = 'Báo cáo & Xuất dữ liệu'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <i class="fas fa-truck fa-3x mb-3" style="color: var(--shopee-primary)"></i>
                <h6 class="fw-bold">Danh sách nhà cung cấp</h6>
                <p class="text-muted small">Xuất toàn bộ NCC ra file CSV/Excel</p>
                <a href="index.php?page=reports&action=export_suppliers" class="btn btn-shopee btn-sm">
                    <i class="fas fa-download me-1"></i> Xuất CSV
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <i class="fas fa-warehouse fa-3x mb-3 text-info"></i>
                <h6 class="fw-bold">Báo cáo tồn kho</h6>
                <p class="text-muted small">Xuất danh sách tồn kho và giá trị</p>
                <a href="index.php?page=reports&action=export_inventory" class="btn btn-info btn-sm text-white">
                    <i class="fas fa-download me-1"></i> Xuất CSV
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <i class="fas fa-file-invoice fa-3x mb-3 text-success"></i>
                <h6 class="fw-bold">In đơn hàng</h6>
                <p class="text-muted small">Chọn đơn hàng từ danh sách để in</p>
                <a href="index.php?page=purchase_orders" class="btn btn-success btn-sm">
                    <i class="fas fa-list me-1"></i> Xem đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
