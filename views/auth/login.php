<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="login-page">
    <div class="login-card">
        <!-- Header gradient cam Shopee -->
        <div class="login-header">
            <i class="fas fa-store fa-2x"></i>
            <h2>SupplierHub</h2>
            <p class="mb-0">Hệ thống quản lý nhà cung cấp</p>
        </div>

        <div class="login-body">
            <!-- Flash message (nếu có lỗi) -->
            <?= showFlash() ?>

            <form method="POST" action="index.php?page=login" id="login-form">
                <?= csrfField() ?>

                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">
                        <i class="fas fa-user me-1"></i> Tên đăng nhập
                    </label>
                    <input type="text" class="form-control" id="username" name="username"
                           placeholder="Nhập tên đăng nhập" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1"></i> Mật khẩu
                    </label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Nhập mật khẩu" required>
                </div>

                <button type="submit" class="btn btn-shopee w-100 py-2" id="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <strong>Tài khoản demo:</strong><br>
                    admin / 123456
                </small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
