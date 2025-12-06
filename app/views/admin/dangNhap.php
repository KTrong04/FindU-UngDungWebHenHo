<?php
require_once __DIR__ . '/../../controllers/nhanVienController.php';
$nv = new nhanVienController();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Management</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/acount.css">

</head>

<body>
    <div class="title-admin"><h1>FindU - Management</h1></div>
    <div class="content">
        <div class="box-acount">
            <form action="" method="post" id="frm-acount">
                <h1 class="hd-frm-acount">Đăng nhập</h1>
                <p>
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" placeholder="Nhập username">
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
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $nv->dangNhap($username, $password);

        }
    }

    ?>

    <?php include('../includes/footer.php'); ?>
</body>

</html>