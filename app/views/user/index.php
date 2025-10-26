<?php
require_once __DIR__ . '/../../controllers/thanhVienController.php';
$tv = new thanhVienController();
if ($tv->configLogin() === true) {
    header("Location: /project-FindU/app/views/user/trangChu.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Add love</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/index.css">
    
</head>

<body>
    <?php include('../includes/header_index.php'); ?>
    <div class="content">
        <div class="banner">
            <img src="/project-FindU/public/assets/img/banner-3.jpg" alt="Banner FindU" id="img-banner">
            <div class="box-icon-banner">
                <img src="/project-FindU/public/assets/img/background-icon-banner.jpg" alt="Background Icon Banner" id="img-background-icon-banner">
                <img src="/project-FindU/public/assets/img/icon-4.webp" alt="Icon Banner" id="img-icon-banner">
            </div>
            <div class="banner-box">
                <h2 class="text-banner">Tìm thấy tình yêu thông qua những gì bạn thích</h2>
                <p>Tạo hồ sơ hẹn hò của bạn ngay hôm nay 💚💛💜💙</p>
                <form action="/project-FindU/app/views/user/dangKy.php">
                    <input type="submit" value="Bắt đầu ngay" class="btn">
                </form>
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>
</body>

</html>