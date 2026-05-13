<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng <?= e($order['order_code']) ?> | SupplierHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; font-size: 14px; }
        @media print { .no-print { display: none; } body { margin: 0; } }
        .company-name { color: #EE4D2D; font-size: 22px; font-weight: 700; }
    </style>
</head>
<body class="p-4">

<div class="no-print text-end mb-3">
    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> In đơn hàng</button>
    <button onclick="window.close()" class="btn btn-secondary">Đóng</button>
</div>

<div class="border p-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="company-name">SupplierHub</div>
            <small class="text-muted">Hệ thống quản lý nhà cung cấp</small>
        </div>
        <div class="col-6 text-end">
            <h4>ĐƠN HÀNG MUA</h4>
            <p class="mb-0"><strong>Mã:</strong> <?= e($order['order_code']) ?></p>
            <p class="mb-0"><strong>Ngày:</strong> <?= formatDate($order['created_at']) ?></p>
            <p class="mb-0">
                <strong>Trạng thái:</strong>
                <span class="badge <?= PO_STATUS_BADGES[$order['status']] ?>"><?= PO_STATUS_LABELS[$order['status']] ?></span>
            </p>
        </div>
    </div>

    <hr>

    <!-- Thông tin NCC -->
    <div class="row mb-4">
        <div class="col-6">
            <h6>Nhà cung cấp:</h6>
            <p class="mb-1"><strong><?= e($order['supplier_name']) ?></strong></p>
            <p class="mb-1"><?= e($order['supplier_address']) ?></p>
            <p class="mb-1">SĐT: <?= e($order['supplier_phone']) ?></p>
            <p class="mb-0">Email: <?= e($order['supplier_email']) ?></p>
        </div>
        <div class="col-6">
            <h6>Thông tin đơn hàng:</h6>
            <p class="mb-1">Người tạo: <?= e($order['created_by_name']) ?></p>
            <p class="mb-1">Ngày giao dự kiến: <?= formatDate($order['expected_delivery']) ?></p>
            <p class="mb-0">Ngày giao thực tế: <?= formatDate($order['actual_delivery']) ?></p>
        </div>
    </div>

    <!-- Bảng items -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th style="width:40px">#</th>
                <th>Sản phẩm</th>
                <th>SKU</th>
                <th>Đơn vị</th>
                <th class="text-end">Số lượng</th>
                <th class="text-end">Đơn giá</th>
                <th class="text-end">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($item['product_name']) ?></td>
                <td><?= e($item['sku']) ?></td>
                <td><?= e($item['unit']) ?></td>
                <td class="text-end"><?= $item['quantity_ordered'] ?></td>
                <td class="text-end"><?= formatMoney($item['unit_price']) ?></td>
                <td class="text-end"><?= formatMoney($item['subtotal']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-end fw-bold">TỔNG CỘNG:</td>
                <td class="text-end fw-bold text-danger fs-5"><?= formatMoney($order['total_amount']) ?></td>
            </tr>
        </tfoot>
    </table>

    <?php if ($order['notes']): ?>
    <p><strong>Ghi chú:</strong> <?= e($order['notes']) ?></p>
    <?php endif; ?>

    <!-- Chữ ký -->
    <div class="row mt-5">
        <div class="col-4 text-center">
            <p class="fw-bold">Người lập</p>
            <br><br><br>
            <p><?= e($order['created_by_name']) ?></p>
        </div>
        <div class="col-4 text-center">
            <p class="fw-bold">Người duyệt</p>
            <br><br><br>
            <p><?= e($order['approved_by_name'] ?? '') ?></p>
        </div>
        <div class="col-4 text-center">
            <p class="fw-bold">Nhà cung cấp</p>
            <br><br><br>
            <p>(Ký, ghi rõ họ tên)</p>
        </div>
    </div>
</div>

</body>
</html>
