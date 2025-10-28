<?php
require_once __DIR__ . '/../../controllers/thanhVienController.php';
$tv = new thanhVienController();
if ($tv->configLogin() === false) {
    header("Location: /project-FindU/app/views/user/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Trang Chủ</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/style.css">
</head>

<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>
    <div class="content">
        <?php include_once __DIR__ . '/../includes/dangBaiViet.php'; ?>
        <div class="bai-viet-list">
            <?php $tv->hienThiBaiViet(); ?>
        </div>
    </div>
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>

<script src="/project-FindU/public/assets/js/postBaiViet.js"></script>