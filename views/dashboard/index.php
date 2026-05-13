<?php $pageTitle = 'Dashboard'; ?>
<?php require VIEW_PATH . '/layouts/header.php'; ?>

<!-- ===== STAT CARDS ===== -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-primary-gradient">
            <div class="stat-value"><?= $stats['total_suppliers'] ?></div>
            <div class="stat-label">Nhà cung cấp</div>
            <i class="fas fa-truck stat-icon"></i>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-success-gradient">
            <div class="stat-value"><?= $stats['total_products'] ?></div>
            <div class="stat-label">Sản phẩm</div>
            <i class="fas fa-box-open stat-icon"></i>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-warning-gradient">
            <div class="stat-value"><?= $stats['pending_orders'] ?></div>
            <div class="stat-label">Đơn chờ duyệt</div>
            <i class="fas fa-clock stat-icon"></i>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-info-gradient">
            <div class="stat-value"><?= formatMoney($stats['inventory_value']) ?></div>
            <div class="stat-label">Giá trị tồn kho</div>
            <i class="fas fa-warehouse stat-icon"></i>
        </div>
    </div>
</div>

<!-- ===== CHARTS ROW ===== -->
<div class="row g-4 mb-4">
    <!-- Biểu đồ chi tiêu theo tháng -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Chi tiêu mua hàng theo tháng</h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Biểu đồ phân bố trạng thái -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i>Trạng thái đơn hàng</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ===== CẢNH BÁO TỒN KHO THẤP ===== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Sản phẩm sắp hết hàng
                </h6>
                <a href="index.php?page=inventory" class="btn btn-sm btn-outline-shopee">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($lowStockProducts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Mã SKU</th>
                                <th>Tên sản phẩm</th>
                                <th>Nhà cung cấp</th>
                                <th>Tồn kho</th>
                                <th>Mức tối thiểu</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockProducts as $item): ?>
                            <tr>
                                <td><code><?= e($item['sku']) ?></code></td>
                                <td><?= e($item['name']) ?></td>
                                <td><?= e($item['supplier_name']) ?></td>
                                <td><strong class="text-danger"><?= $item['stock'] ?></strong></td>
                                <td><?= $item['min_stock_level'] ?></td>
                                <td>
                                    <?php if ($item['stock'] == 0): ?>
                                        <span class="badge bg-danger">Hết hàng</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Sắp hết</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state py-4">
                    <i class="fas fa-check-circle text-success"></i>
                    <p class="mt-2">Tất cả sản phẩm đều đủ hàng! 🎉</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ===== CHART.JS SCRIPTS ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu từ PHP
    var monthlyData = <?= json_encode($monthlySpending) ?>;
    var statusData = <?= json_encode($statusDistribution) ?>;

    // --- Biểu đồ cột: Chi tiêu theo tháng ---
    var monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(function(item) { return item.label; }),
            datasets: [{
                label: 'Chi tiêu (VNĐ)',
                data: monthlyData.map(function(item) { return item.total; }),
                backgroundColor: 'rgba(238, 77, 45, 0.7)',
                borderColor: '#EE4D2D',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' ₫';
                        }
                    }
                }
            }
        }
    });

    // --- Biểu đồ tròn: Trạng thái đơn hàng ---
    var statusLabels = {
        'draft': 'Nháp', 'pending': 'Chờ duyệt', 'approved': 'Đã duyệt',
        'received': 'Đã nhận', 'cancelled': 'Đã hủy'
    };
    var statusColors = {
        'draft': '#6c757d', 'pending': '#FFBF00', 'approved': '#17a2b8',
        'received': '#26AA99', 'cancelled': '#D63031'
    };

    var statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(function(item) { return statusLabels[item.status] || item.status; }),
            datasets: [{
                data: statusData.map(function(item) { return item.count; }),
                backgroundColor: statusData.map(function(item) { return statusColors[item.status] || '#999'; }),
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true }
                }
            }
        }
    });
});
</script>

<?php require VIEW_PATH . '/layouts/footer.php'; ?>
