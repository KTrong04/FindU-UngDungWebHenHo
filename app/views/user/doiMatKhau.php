<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controllers/thanhVienController.php';
$controller = new thanhVienController();

$thongBao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matKhauCu = $_POST['matKhauCu'] ?? '';
    $matKhauMoi = $_POST['matKhauMoi'] ?? '';
    $nhapLai = $_POST['nhapLai'] ?? '';

    $ketQua = $controller->doiMatKhau($matKhauCu, $matKhauMoi, $nhapLai);

    // ✅ Nếu đổi mật khẩu thành công → đăng xuất & chuyển hướng
    if ($ketQua['success'] === true) {
        session_destroy();

        // ⚠️ Gọi header NGAY trước khi in ra bất kỳ HTML nào
        header('Location: /Project-FindU/app/views/user/dangNhap.php');
        exit();
    } else {
        $thongBao = $ketQua['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu - FindU</title>
    <link rel="stylesheet" href="/Project-FindU/public/assets/css/doiMatKhau.css">
</head>

<body>
    <div class="wrapper">
        <div class="title-area">
            <h2>💞 Đổi mật khẩu</h2>
            <p class="subtitle">Giữ tài khoản của bạn an toàn nhé 💌</p>
        </div>

        <?php if (!empty($thongBao)): ?>
        <p class="message <?php echo $ketQua['success'] ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($thongBao); ?>
        </p>
        <?php endif; ?>

        <form method="POST">
            <label>Mật khẩu cũ:</label>
            <input type="password" name="matKhauCu" required>

            <label>Mật khẩu mới:</label>
            <input type="password" name="matKhauMoi" required>

            <label>Nhập lại mật khẩu mới:</label>
            <input type="password" name="nhapLai" required>

            <button type="submit">💗 Xác nhận</button>
        </form>
    </div>
</body>

</html>