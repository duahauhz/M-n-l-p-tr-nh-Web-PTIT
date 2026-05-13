-- =============================================
-- SupplierHub - Stored Procedures
-- =============================================

USE supplierhub;

DELIMITER //

-- =============================================
-- SP1: Tính lại tổng tiền đơn hàng
-- Gọi sau khi thêm/sửa/xóa item trong đơn
-- =============================================
CREATE PROCEDURE sp_recalculate_order_total(IN p_order_id INT)
BEGIN
    UPDATE purchase_orders
    SET total_amount = (
        SELECT COALESCE(SUM(subtotal), 0)
        FROM purchase_order_items
        WHERE purchase_order_id = p_order_id
    )
    WHERE id = p_order_id;
END //

-- =============================================
-- SP2: Cập nhật rating trung bình của NCC
-- Gọi sau khi thêm/sửa đánh giá
-- =============================================
CREATE PROCEDURE sp_update_supplier_rating(IN p_supplier_id INT)
BEGIN
    UPDATE suppliers
    SET rating_avg = (
        SELECT COALESCE(AVG(
            (rating_quality + rating_delivery + rating_price + rating_service) / 4.0
        ), 0)
        FROM supplier_reviews
        WHERE supplier_id = p_supplier_id
    )
    WHERE id = p_supplier_id;
END //

-- =============================================
-- SP3: Nhận hàng - Cập nhật tồn kho (Transaction)
-- Đảm bảo data integrity khi nhận hàng
-- =============================================
CREATE PROCEDURE sp_receive_order(
    IN p_order_id INT,
    IN p_user_id INT
)
BEGIN
    DECLARE v_done INT DEFAULT 0;
    DECLARE v_product_id INT;
    DECLARE v_qty_received INT;
    DECLARE cur CURSOR FOR
        SELECT product_id, quantity_ordered
        FROM purchase_order_items
        WHERE purchase_order_id = p_order_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    -- Bắt đầu transaction
    START TRANSACTION;

    -- Cập nhật trạng thái đơn hàng
    UPDATE purchase_orders
    SET status = 'received',
        actual_delivery = CURDATE(),
        approved_by = p_user_id
    WHERE id = p_order_id AND status = 'approved';

    -- Kiểm tra có update thành công không
    IF ROW_COUNT() = 0 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Đơn hàng không ở trạng thái có thể nhận hàng';
    END IF;

    -- Duyệt qua từng item và cập nhật tồn kho
    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_product_id, v_qty_received;
        IF v_done THEN
            LEAVE read_loop;
        END IF;

        -- Cập nhật quantity_received trong order items
        UPDATE purchase_order_items
        SET quantity_received = quantity_ordered
        WHERE purchase_order_id = p_order_id AND product_id = v_product_id;

        -- Cập nhật hoặc thêm mới inventory
        INSERT INTO inventory (product_id, quantity_on_hand, last_updated)
        VALUES (v_product_id, v_qty_received, NOW())
        ON DUPLICATE KEY UPDATE
            quantity_on_hand = quantity_on_hand + v_qty_received,
            last_updated = NOW();
    END LOOP;
    CLOSE cur;

    -- Commit transaction
    COMMIT;
END //

-- =============================================
-- SP4: Thống kê dashboard
-- Trả về các số liệu tổng quan
-- =============================================
CREATE PROCEDURE sp_dashboard_stats()
BEGIN
    -- Tổng NCC active
    SELECT COUNT(*) AS total_suppliers FROM suppliers WHERE status = 'active';

    -- Tổng sản phẩm active
    SELECT COUNT(*) AS total_products FROM products WHERE status = 'active';

    -- Đơn hàng đang chờ duyệt
    SELECT COUNT(*) AS pending_orders FROM purchase_orders WHERE status = 'pending';

    -- Tổng giá trị tồn kho
    SELECT COALESCE(SUM(i.quantity_on_hand * p.unit_price), 0) AS inventory_value
    FROM inventory i
    JOIN products p ON i.product_id = p.id;

    -- Chi tiêu theo tháng (12 tháng gần nhất)
    SELECT
        DATE_FORMAT(created_at, '%Y-%m') AS month,
        SUM(total_amount) AS total_spent
    FROM purchase_orders
    WHERE status = 'received'
        AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month;

    -- Top 5 NCC theo giá trị đơn hàng
    SELECT
        s.id,
        s.company_name,
        COUNT(po.id) AS total_orders,
        SUM(po.total_amount) AS total_value
    FROM suppliers s
    JOIN purchase_orders po ON s.id = po.supplier_id
    WHERE po.status = 'received'
    GROUP BY s.id, s.company_name
    ORDER BY total_value DESC
    LIMIT 5;

    -- Sản phẩm sắp hết hàng
    SELECT
        p.id,
        p.name,
        p.sku,
        COALESCE(i.quantity_on_hand, 0) AS stock,
        p.min_stock_level,
        s.company_name AS supplier_name
    FROM products p
    LEFT JOIN inventory i ON p.id = i.product_id
    JOIN suppliers s ON p.supplier_id = s.id
    WHERE p.status = 'active'
        AND COALESCE(i.quantity_on_hand, 0) <= p.min_stock_level
    ORDER BY COALESCE(i.quantity_on_hand, 0) ASC
    LIMIT 10;
END //

-- =============================================
-- SP5: Tạo mã đơn hàng tự động
-- Format: PO-YYYYMMDD-XXX
-- =============================================
CREATE PROCEDURE sp_generate_order_code(OUT p_code VARCHAR(20))
BEGIN
    DECLARE v_count INT;
    DECLARE v_date VARCHAR(8);

    SET v_date = DATE_FORMAT(NOW(), '%Y%m%d');

    SELECT COUNT(*) + 1 INTO v_count
    FROM purchase_orders
    WHERE DATE(created_at) = CURDATE();

    SET p_code = CONCAT('PO-', v_date, '-', LPAD(v_count, 3, '0'));
END //

DELIMITER ;
