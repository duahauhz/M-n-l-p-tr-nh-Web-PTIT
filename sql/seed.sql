-- =============================================
-- SupplierHub - Dữ liệu mẫu (Seed Data)
-- =============================================

USE supplierhub;

-- =============================================
-- Users (chỉ admin - mật khẩu mặc định: 123456)
-- =============================================
INSERT INTO users (username, email, password_hash, full_name, phone) VALUES
('admin', 'admin@supplierhub.vn', '$2y$12$kf2OCDfEjRmJeXUnJTMYxekm4LE1rLZ3y0Dfl1KxkShWuoeplfsDu', 'Nguyễn Văn Admin', '0901000001');


-- =============================================
-- Suppliers - 10 nhà cung cấp
-- =============================================
INSERT INTO suppliers (company_name, contact_person, email, phone, address, tax_code, status, rating_avg, notes) VALUES
('Công ty TNHH Thiết Bị Văn Phòng Hòa Phát', 'Nguyễn Minh Tuấn', 'tuannm@hoaphat-vp.vn', '028-3822-1100', '45 Nguyễn Trãi, Q.1, TP.HCM', '0301234567', 'active', 4.50, 'NCC uy tín, giao hàng đúng hẹn'),
('CTCP Công Nghệ Phần Mềm FPT', 'Trần Đức Hùng', 'hungtd@fpt.com.vn', '024-7300-7300', '17 Duy Tân, Cầu Giấy, Hà Nội', '0105678901', 'active', 4.75, 'Cung cấp thiết bị CNTT chất lượng cao'),
('Công ty TNHH Samsung Vina', 'Park Ji Sung', 'jisung@samsung-vina.com', '028-3825-6789', '2 Hai Bà Trưng, Q.1, TP.HCM', '0302345678', 'active', 4.25, 'Đại lý chính hãng Samsung'),
('Công ty CP Nhựa Bình Minh', 'Lê Thị Hồng', 'hongle@binhminhplastic.vn', '028-3896-0001', '240 Hậu Giang, Q.6, TP.HCM', '0303456789', 'active', 3.80, 'Chuyên vật liệu nhựa công nghiệp'),
('CTCP Thép Pomina', 'Võ Văn Thành', 'thanhvv@pomina.com.vn', '0254-383-6666', 'KCN Phú Mỹ I, Bà Rịa-Vũng Tàu', '3501234567', 'active', 4.10, 'Thép xây dựng chất lượng cao'),
('Công ty TNHH Giấy Sài Gòn', 'Trương Minh Đức', 'ductm@saigonpaper.com', '028-3829-4567', '165 Võ Thị Sáu, Q.3, TP.HCM', '0304567890', 'active', 3.50, 'Giấy văn phòng, giấy in'),
('CTCP Điện Máy Xanh Supply', 'Huỳnh Thị Mai', 'maihuynh@dmx-supply.vn', '028-3636-3636', '128 Trần Quang Khải, Q.1, TP.HCM', '0305678901', 'active', 4.60, 'Thiết bị điện tử tiêu dùng'),
('Công ty TNHH Hóa Chất Việt Hưng', 'Đặng Quốc Bảo', 'baodq@viethungchem.vn', '028-3851-2345', '88 Lý Thường Kiệt, Q.10, TP.HCM', '0306789012', 'inactive', 2.80, 'Tạm ngưng hợp tác do chất lượng giảm'),
('CTCP Đầu Tư An Phát Holdings', 'Ngô Thị Thanh', 'thanhnt@anphat.com.vn', '0225-381-8888', 'KCN Nam Sách, Hải Dương', '0800123456', 'active', 4.30, 'Bao bì nhựa, vật liệu đóng gói'),
('Công ty TNHH MTV Cao Su Đồng Nai', 'Bùi Xuân Hải', 'haibx@dongnairubber.vn', '0251-382-1234', '2 Nguyễn Trãi, Biên Hòa, Đồng Nai', '3600987654', 'active', 3.90, 'Sản phẩm từ cao su thiên nhiên'),
('Nhà cung cấp Demo 11 (Trang 2)', 'Nguyễn Văn A', 'demo11@ncc.vn', '0901111111', 'Hà Nội', '1111111111', 'active', 5.00, 'Dữ liệu thêm để test phân trang'),
('Nhà cung cấp Demo 12 (Trang 2)', 'Nguyễn Văn B', 'demo12@ncc.vn', '0902222222', 'Đà Nẵng', '2222222222', 'active', 4.00, 'Dữ liệu thêm để test phân trang'),
('Nhà cung cấp Demo 13 (Trang 2)', 'Nguyễn Văn C', 'demo13@ncc.vn', '0903333333', 'Cần Thơ', '3333333333', 'active', 3.50, 'Dữ liệu thêm để test phân trang'),
('Nhà cung cấp Demo 14 (Trang 2)', 'Nguyễn Văn D', 'demo14@ncc.vn', '0904444444', 'Hải Phòng', '4444444444', 'active', 4.50, 'Dữ liệu thêm để test phân trang'),
('Nhà cung cấp Demo 15 (Trang 2)', 'Nguyễn Văn E', 'demo15@ncc.vn', '0905555555', 'Nha Trang', '5555555555', 'active', 4.80, 'Dữ liệu thêm để test phân trang');

-- =============================================
-- Categories - Danh mục
-- =============================================
INSERT INTO categories (name, slug, description, parent_id, sort_order, is_active) VALUES
('Thiết bị văn phòng', 'thiet-bi-van-phong', 'Bàn, ghế, tủ và thiết bị văn phòng', NULL, 1, 1),
('Thiết bị CNTT', 'thiet-bi-cntt', 'Máy tính, máy in, thiết bị mạng', NULL, 2, 1),
('Vật tư tiêu hao', 'vat-tu-tieu-hao', 'Giấy, mực in, văn phòng phẩm', NULL, 3, 1),
('Vật liệu xây dựng', 'vat-lieu-xay-dung', 'Thép, xi măng, nhựa, gỗ', NULL, 4, 1),
('Hóa chất', 'hoa-chat', 'Hóa chất công nghiệp, tẩy rửa', NULL, 5, 1),
('Bao bì đóng gói', 'bao-bi-dong-goi', 'Hộp, túi, bao bì các loại', NULL, 6, 1),
('Máy tính để bàn', 'may-tinh-de-ban', 'Desktop, All-in-one', 2, 1, 1),
('Laptop', 'laptop', 'Laptop các hãng', 2, 2, 1),
('Máy in & Scanner', 'may-in-scanner', 'Máy in, máy scan, máy photocopy', 2, 3, 1),
('Giấy in', 'giay-in', 'Giấy A4, A3, giấy đặc biệt', 3, 1, 1);

-- =============================================
-- Products - 30 sản phẩm
-- =============================================
INSERT INTO products (supplier_id, category_id, name, sku, description, unit_price, unit, status, min_stock_level) VALUES
-- NCC 1: Hòa Phát - Thiết bị văn phòng
(1, 1, 'Bàn làm việc Hòa Phát SV1200', 'HP-BAN-001', 'Bàn gỗ công nghiệp 1200x600x750mm, chân sắt sơn tĩnh điện', 1250000.00, 'cái', 'active', 5),
(1, 1, 'Ghế xoay văn phòng HP-G01', 'HP-GHE-001', 'Ghế xoay lưng lưới, tay vịn cố định, chân nhựa 5 càng', 850000.00, 'cái', 'active', 10),
(1, 1, 'Tủ hồ sơ 3 ngăn HP-TU3', 'HP-TU-001', 'Tủ sắt 3 ngăn kéo, có khóa, sơn tĩnh điện', 2100000.00, 'cái', 'active', 3),

-- NCC 2: FPT - Thiết bị CNTT
(2, 7, 'Máy tính để bàn FPT Elead M525', 'FPT-PC-001', 'CPU i5-12400, RAM 8GB, SSD 256GB, Win 11', 12500000.00, 'bộ', 'active', 5),
(2, 8, 'Laptop FPT Elead L1415', 'FPT-LT-001', 'i5-1235U, 8GB RAM, 512GB SSD, 14 inch FHD', 15800000.00, 'cái', 'active', 3),
(2, 9, 'Máy in HP LaserJet Pro M404dn', 'FPT-PR-001', 'In 2 mặt tự động, mạng LAN, 38 trang/phút', 7200000.00, 'cái', 'active', 2),

-- NCC 3: Samsung - Thiết bị CNTT
(3, 7, 'Màn hình Samsung 24 inch LS24', 'SS-MH-001', 'LED IPS, Full HD, 75Hz, HDMI + VGA', 3200000.00, 'cái', 'active', 10),
(3, 8, 'Samsung Galaxy Book3 Pro', 'SS-LT-001', 'i7-1360P, 16GB, 512GB SSD, AMOLED 14', 25900000.00, 'cái', 'active', 2),
(3, 2, 'Máy chủ Samsung PM1733 SSD', 'SS-SV-001', 'SSD NVMe Enterprise 1.92TB', 8500000.00, 'cái', 'active', 3),

-- NCC 4: Nhựa Bình Minh - Vật liệu
(4, 4, 'Ống nhựa PVC D21 Bình Minh', 'BM-ONG-001', 'Ống nước PVC D21mm, dài 4m, PN10', 28000.00, 'ống', 'active', 100),
(4, 4, 'Phụ kiện co nối PVC D21', 'BM-PK-001', 'Co nối, tê, cút PVC D21mm (bộ 10 cái)', 35000.00, 'bộ', 'active', 50),
(4, 4, 'Ống nhựa PPR D25 Bình Minh', 'BM-PPR-001', 'Ống nước nóng PPR D25mm, dài 4m', 65000.00, 'ống', 'active', 80),

-- NCC 5: Thép Pomina
(5, 4, 'Thép cuộn Pomina D10 CB300', 'PM-TC-001', 'Thép cuộn phi 10, chuẩn CB300, cuộn 2 tấn', 15200000.00, 'tấn', 'active', 5),
(5, 4, 'Thép hình chữ U Pomina U100', 'PM-TH-001', 'Thép hình U100x50x5mm, dài 6m', 285000.00, 'cây', 'active', 20),
(5, 4, 'Thép tấm Pomina 6mm', 'PM-TT-001', 'Thép tấm 6mm x 1500 x 6000mm', 950000.00, 'tấm', 'active', 10),

-- NCC 6: Giấy Sài Gòn
(6, 10, 'Giấy A4 Double A 80gsm', 'GS-A4-001', 'Giấy in A4, 80gsm, 500 tờ/ram', 85000.00, 'ram', 'active', 50),
(6, 10, 'Giấy A3 IK Plus 80gsm', 'GS-A3-001', 'Giấy in A3, 80gsm, 500 tờ/ram', 165000.00, 'ram', 'active', 20),
(6, 3, 'Giấy in nhiệt K80 Sài Gòn', 'GS-K80-001', 'Giấy in hóa đơn K80x65mm, 50 cuộn/thùng', 450000.00, 'thùng', 'active', 15),

-- NCC 7: Điện Máy Xanh
(7, 2, 'Điều hòa Daikin Inverter 9000BTU', 'DMX-DH-001', 'Điều hòa 1 chiều, Inverter, 9000BTU, R32', 8900000.00, 'bộ', 'active', 3),
(7, 2, 'Tủ lạnh Samsung RT22M4032BY', 'DMX-TL-001', 'Tủ lạnh 236L, Inverter, ngăn đá trên', 5600000.00, 'cái', 'active', 2),
(7, 2, 'Máy lọc nước Kangaroo KG100HQ', 'DMX-MLN-001', 'Máy lọc RO 10 lõi, vỏ tủ VTU', 4200000.00, 'cái', 'active', 5),

-- NCC 8: Hóa Chất Việt Hưng
(8, 5, 'Dung dịch tẩy rửa công nghiệp VH-01', 'VH-TR-001', 'Dung dịch tẩy rửa đa năng, can 20L', 350000.00, 'can', 'active', 10),
(8, 5, 'Cồn công nghiệp 96 độ', 'VH-CON-001', 'Cồn Ethanol 96%, can 30L', 680000.00, 'can', 'active', 5),

-- NCC 9: An Phát Holdings
(9, 6, 'Túi PE trong suốt 20x30cm', 'AP-TUI-001', 'Túi PE trong, 20x30cm, 1kg/gói (~200 túi)', 42000.00, 'kg', 'active', 100),
(9, 6, 'Thùng carton 3 lớp 30x25x20', 'AP-CAR-001', 'Thùng carton 3 lớp sóng B, 30x25x20cm', 8500.00, 'cái', 'active', 200),
(9, 6, 'Màng co PE 50cm', 'AP-MC-001', 'Màng co PE cuộn 50cm x 300m, 20 micron', 95000.00, 'cuộn', 'active', 30),

-- NCC 10: Cao Su Đồng Nai
(10, 4, 'Cao su tấm lót sàn 5mm', 'CS-TAM-001', 'Cao su tấm đen, dày 5mm, 1.2m x 10m/cuộn', 1850000.00, 'cuộn', 'active', 5),
(10, 4, 'Gioăng cao su O-ring D50', 'CS-GR-001', 'Gioăng O-ring NBR, D50 x 5mm, 100 cái/gói', 250000.00, 'gói', 'active', 20),
(10, 4, 'Băng tải cao su EP100', 'CS-BT-001', 'Băng tải cao su 2 lớp EP100, rộng 500mm', 4500000.00, 'mét', 'active', 10);

-- =============================================
-- Inventory - Tồn kho ban đầu
-- =============================================
INSERT INTO inventory (product_id, quantity_on_hand, quantity_reserved) VALUES
(1, 12, 0), (2, 25, 2), (3, 8, 0),
(4, 6, 1), (5, 4, 0), (6, 3, 0),
(7, 15, 3), (8, 2, 0), (9, 5, 0),
(10, 150, 10), (11, 80, 0), (12, 120, 5),
(13, 8, 2), (14, 35, 0), (15, 12, 0),
(16, 45, 5), (17, 18, 0), (18, 25, 0),
(19, 4, 1), (20, 3, 0), (21, 6, 0),
(22, 15, 0), (23, 8, 0),
(24, 200, 20), (25, 350, 30), (26, 45, 0),
(27, 7, 0), (28, 30, 5), (29, 3, 0);

-- =============================================
-- Purchase Orders - Đơn hàng mẫu
-- =============================================
INSERT INTO purchase_orders (order_code, supplier_id, created_by, approved_by, status, total_amount, notes, expected_delivery, actual_delivery, created_at) VALUES
('PO-20260301-001', 1, 1, 1, 'received', 15500000.00, 'Đơn bổ sung nội thất văn phòng', '2026-03-10', '2026-03-09', '2026-03-01 09:00:00'),
('PO-20260305-001', 2, 1, 1, 'received', 37500000.00, 'Mua máy tính cho phòng kế toán', '2026-03-15', '2026-03-14', '2026-03-05 10:30:00'),
('PO-20260310-001', 6, 1, NULL, 'approved', 4250000.00, 'Bổ sung giấy in quý 2', '2026-03-20', NULL, '2026-03-10 08:15:00'),
('PO-20260315-001', 9, 1, NULL, 'pending', 2975000.00, 'Đơn bao bì tháng 3', '2026-03-25', NULL, '2026-03-15 14:00:00'),
('PO-20260320-001', 5, 1, NULL, 'draft', 46700000.00, 'Dự trù thép quý 2/2026', '2026-04-05', NULL, '2026-03-20 11:00:00'),
('PO-20260401-001', 7, 1, 1, 'received', 22700000.00, 'Thiết bị cho chi nhánh mới', '2026-04-10', '2026-04-10', '2026-04-01 09:30:00'),
('PO-20260405-001', 3, 1, NULL, 'pending', 6400000.00, 'Bổ sung màn hình', '2026-04-15', NULL, '2026-04-05 16:00:00'),
('PO-20260410-001', 4, 1, 1, 'approved', 3780000.00, 'Vật tư ống nước dự án A', '2026-04-18', NULL, '2026-04-10 07:45:00');

-- =============================================
-- Purchase Order Items
-- =============================================
INSERT INTO purchase_order_items (purchase_order_id, product_id, quantity_ordered, quantity_received, unit_price, subtotal) VALUES
-- PO1: Nội thất Hòa Phát
(1, 1, 5, 5, 1250000.00, 6250000.00),
(1, 2, 8, 8, 850000.00, 6800000.00),
(1, 3, 1, 1, 2100000.00, 2100000.00),
-- PO2: Máy tính FPT
(2, 4, 3, 3, 12500000.00, 37500000.00),
-- PO3: Giấy in
(3, 16, 50, 0, 85000.00, 4250000.00),
-- PO4: Bao bì An Phát
(4, 24, 30, 0, 42000.00, 1260000.00),
(4, 25, 100, 0, 8500.00, 850000.00),
(4, 26, 10, 0, 95000.00, 950000.00),
-- PO5: Thép Pomina (draft)
(5, 13, 3, 0, 15200000.00, 45600000.00),
(5, 14, 4, 0, 285000.00, 1140000.00),
-- PO6: Điện Máy Xanh (received)
(6, 19, 2, 2, 8900000.00, 17800000.00),
(6, 21, 1, 1, 4200000.00, 4200000.00),
-- PO7: Samsung (pending)
(7, 7, 2, 0, 3200000.00, 6400000.00),
-- PO8: Nhựa Bình Minh (approved)
(8, 10, 100, 0, 28000.00, 2800000.00),
(8, 11, 30, 0, 35000.00, 1050000.00);

-- =============================================
-- Supplier Reviews - Đánh giá mẫu
-- =============================================
INSERT INTO supplier_reviews (supplier_id, user_id, purchase_order_id, rating_quality, rating_delivery, rating_price, rating_service, comment) VALUES
(1, 1, 1, 5, 4, 4, 5, 'Hàng chất lượng tốt, giao sớm 1 ngày. Giá hợp lý.'),
(2, 1, 2, 5, 5, 4, 5, 'Máy tính chạy ổn định, hỗ trợ bảo hành rất tốt.'),
(7, 1, 6, 5, 4, 4, 5, 'Thiết bị chính hãng, đóng gói cẩn thận.'),
(1, 1, NULL, 4, 5, 4, 4, 'NCC đáng tin cậy, phản hồi nhanh.'),
(3, 1, NULL, 4, 4, 3, 5, 'Hàng Samsung chính hãng nhưng giá hơi cao.');

-- =============================================
-- Audit Logs - Nhật ký mẫu
-- =============================================
INSERT INTO audit_logs (user_id, action, table_name, record_id, ip_address, created_at) VALUES
(1, 'CREATE', 'suppliers', 1, '127.0.0.1', '2026-02-15 08:00:00'),
(1, 'CREATE', 'suppliers', 2, '127.0.0.1', '2026-02-15 08:05:00'),
(1, 'CREATE', 'purchase_orders', 1, '127.0.0.1', '2026-03-01 09:00:00'),
(1, 'UPDATE', 'purchase_orders', 1, '127.0.0.1', '2026-03-02 10:00:00'),
(1, 'UPDATE', 'purchase_orders', 1, '127.0.0.1', '2026-03-09 14:00:00');

-- =============================================
-- Settings - Cấu hình
-- =============================================
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', 'SupplierHub', 'Tên hệ thống'),
('currency', 'VND', 'Đơn vị tiền tệ'),
('items_per_page', '10', 'Số dòng trên mỗi trang'),
('low_stock_threshold', '10', 'Ngưỡng cảnh báo tồn kho thấp'),
('company_name', 'Công ty TNHH Thương Mại ABC', 'Tên công ty sử dụng'),
('company_address', '123 Nguyễn Huệ, Q.1, TP.HCM', 'Địa chỉ công ty'),
('company_phone', '028-3823-4567', 'Điện thoại công ty');
