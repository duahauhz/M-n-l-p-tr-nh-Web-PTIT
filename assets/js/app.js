/**
 * SupplierHub - JavaScript đơn giản
 * Xử lý UI interactions cơ bản
 */

// === Confirm trước khi xóa ===
function confirmDelete(formId, itemName) {
    if (confirm('Bạn có chắc chắn muốn xóa "' + itemName + '"?\nHành động này không thể hoàn tác.')) {
        document.getElementById(formId).submit();
    }
}

// === Toggle Sidebar trên mobile ===
function toggleSidebar() {
    var sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('show');
}

// === Đóng sidebar khi click bên ngoài (mobile) ===
document.addEventListener('click', function(e) {
    var sidebar = document.querySelector('.sidebar');
    var toggleBtn = document.querySelector('.sidebar-toggle');
    if (sidebar && sidebar.classList.contains('show')) {
        if (!sidebar.contains(e.target) && (!toggleBtn || !toggleBtn.contains(e.target))) {
            sidebar.classList.remove('show');
        }
    }
});

// === Format số tiền khi nhập ===
function formatNumberInput(input) {
    var value = input.value.replace(/[^\d]/g, '');
    input.value = value;
}

// === Thêm dòng item trong form tạo đơn hàng ===
var itemRowIndex = 1;
function addOrderItem() {
    var tbody = document.getElementById('order-items-body');
    if (!tbody) return;

    var row = document.createElement('tr');
    row.id = 'item-row-' + itemRowIndex;
    row.innerHTML = `
        <td>
            <select name="items[${itemRowIndex}][product_id]" class="form-select form-select-sm" required
                    onchange="updatePrice(this, ${itemRowIndex})">
                <option value="">-- Chọn sản phẩm --</option>
            </select>
        </td>
        <td>
            <input type="number" name="items[${itemRowIndex}][quantity]" class="form-control form-control-sm"
                   min="1" value="1" required onchange="calcSubtotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" name="items[${itemRowIndex}][unit_price]" class="form-control form-control-sm"
                   min="0" step="1000" value="0" required onchange="calcSubtotal(${itemRowIndex})"
                   id="price-${itemRowIndex}">
        </td>
        <td>
            <span id="subtotal-${itemRowIndex}">0 ₫</span>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOrderItem(${itemRowIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);

    // Copy option từ hàng đầu tiên
    var firstSelect = tbody.querySelector('tr:first-child select');
    var newSelect = row.querySelector('select');
    if (firstSelect) {
        newSelect.innerHTML = firstSelect.innerHTML;
        newSelect.value = '';
    }

    itemRowIndex++;
}

// === Xóa dòng item ===
function removeOrderItem(index) {
    var row = document.getElementById('item-row-' + index);
    if (row) {
        row.remove();
        updateGrandTotal();
    }
}

// === Cập nhật giá khi chọn sản phẩm ===
function updatePrice(select, index) {
    var option = select.options[select.selectedIndex];
    var price = option.getAttribute('data-price') || 0;
    var priceInput = document.getElementById('price-' + index);
    if (priceInput) {
        priceInput.value = price;
        calcSubtotal(index);
    }
}

// === Tính subtotal cho 1 dòng ===
function calcSubtotal(index) {
    var row = document.getElementById('item-row-' + index);
    if (!row) return;

    var qty = parseInt(row.querySelector('[name*="quantity"]').value) || 0;
    var price = parseFloat(row.querySelector('[name*="unit_price"]').value) || 0;
    var subtotal = qty * price;

    var subtotalSpan = document.getElementById('subtotal-' + index);
    if (subtotalSpan) {
        subtotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + ' ₫';
    }

    updateGrandTotal();
}

// === Tính tổng toàn bộ đơn hàng ===
function updateGrandTotal() {
    var total = 0;
    var subtotals = document.querySelectorAll('[id^="subtotal-"]');
    subtotals.forEach(function(el) {
        var text = el.textContent.replace(/[^\d]/g, '');
        total += parseInt(text) || 0;
    });

    var grandTotal = document.getElementById('grand-total');
    if (grandTotal) {
        grandTotal.textContent = new Intl.NumberFormat('vi-VN').format(total) + ' ₫';
    }
}

// === Auto-hide flash messages sau 5 giây ===
document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });
});

// === Load sản phẩm theo NCC (AJAX-like bằng form submit) ===
function loadProductsBySupplier(supplierId) {
    if (!supplierId) return;
    // Redirect về cùng trang với supplier_id parameter
    // Sản phẩm sẽ được load bởi PHP
    window.location.href = 'index.php?page=purchase_orders&action=create&supplier_id=' + supplierId;
}
