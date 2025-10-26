<?php
require_once __DIR__ . '/../../controllers/thanhVienController.php';
$tv = new thanhVienController();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/acount.css">

</head>

<body>
    <?php include('../includes/header_acount.php'); ?>
    <div class="content">
        <div class="box-acount">
            <form action="" method="post" id="frm-acount">
                <h1 class="hd-frm-acount">Đăng nhập</h1>
                <p>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Nhập email">
                </p>
                <p>
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
                </p>
                <p class="box-btn-acount">
                    <input type="submit" id="btn_dangNhap" name="btn_dangNhap" value="Đăng nhập" class="btn-acount">
                </p>
            </form>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['btn_dangNhap'] === "Đăng nhập") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $tv->dangNhap($email, $password);

        }
    }

    ?>

    <?php include('../includes/footer.php'); ?>
</body>

</html>