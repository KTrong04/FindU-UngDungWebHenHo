<?php
require_once __DIR__ . '/../../controllers/thanhVienController.php';
$tv = new thanhVienController();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Signup</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/acount.css">

</head>

<body>
    <?php include('../includes/header_acount.php'); ?>
    <div class="content">
        <div class="box-acount">
            <form action="dangKy.php" method="post" id="frm-acount" onsubmit="return validateForm()">
                <h1 class="hd-frm-acount">Đăng ký</h1>
                <p>
                    <label for="fullname">Họ và tên</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên">
                </p>

                <nav>
                    <div class="box-nav-left">
                        <label for="age">Tuổi</label>
                        <input type="number" min=18 id="age" name="age" placeholder="Nhập số tuổi">
                    </div>
                    <div class="box-nav-right">
                        <label for="">Giới tính</label>
                        <section>
                            <div class="box-radio">
                                <label for="Nam">Nam</label>
                                <input type="radio" id="Nam" name="sex" value="M">
                            </div>
                            <div class="box-radio">
                                <label for="Nu">Nữ</label>
                                <input type="radio" id="Nu" name="sex" value="F">
                            </div>
                        </section>
                    </div>
                </nav>
                <p>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Nhập email">
                </p>
                <p>
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
                </p>
                <p>
                    <label for="confirm-password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm-password" name="confirm-password"
                        placeholder="Xác nhận mật khẩu">
                </p>
                <p class="box-btn-acount">
                    <button type="submit" value="Đăng ký" name="btn_dangKy" class="btn-acount">Đăng ký</button>
                </p>
            </form>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['btn_dangKy'] === "Đăng ký") {
            $hoTen = $_POST['fullname'] ?? '';
            $tuoi = $_POST['age'] ?? '';
            $gioiTinh = $_POST['sex'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $repassword = $_POST['confirm-password'] ?? '';
            
            $tv->dangKy($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword);
        }
    }
    ?>

    <?php include('../includes/footer.php'); ?>
</body>

</html>