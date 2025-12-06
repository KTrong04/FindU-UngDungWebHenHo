<?php
$Path = $_SERVER['DOCUMENT_ROOT'] . '/Project-FindU/app/controllers/';
include($Path . 'nhanVienController.php');
include($Path . 'nhanVienQLController.php');
$nv = new nhanVienController();
$nvql = new nhanVienQLController();

if ($nv->configLogin() === false) {
    header("Location: /project-FindU/app/views/admin/dangNhap.php");
    exit();
}
?>

<head>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_layout.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_style.css">
</head>

<header>
    <div class="box-header-left">
        <h1>header website</h1>
    </div>
    <div class="box-header-right">
        <form action="" method="post">
            <button type="submit" name="btn_signup" value="signup">Đăng xuất</button>
            <button type="submit" name="btn_doipassword" value="Đổi mật khẩu">Đổi mật khẩu</button>
        </form>
        <?php
        if (isset($_POST['btn_signup'])) {
            $nv->dangXuat();
        }

        if (isset($_POST['btn_doipassword'])) {
            echo '<div class="box-doi-password">';
            echo    '<h1 class="title-doi-password">Đổi mật khẩu</h1>
                        <form method="post" class="form-doi-password"><button type="submit">X</button>';
            echo            '<p><lable for="txt_password">Nhập mật khẩu hiện tại</label><input type="password" name="txt_password" placeholder="Nhập mật khẩu hiện tại"></p>';
            echo            '<p><button type="submit" name="btn_checkPassword">Đổi mật khẩu</button></p>';
            echo        '</form>
                    </div>';
        }
        if (isset($_POST['btn_checkPassword'])) {
            $pass = $_POST['txt_password'];
            if ($nv->checkPasswordNow($pass, $_SESSION['admin_maNV']) == true) {
                echo '<div class="box-doi-password">';
                echo    '<h1 class="title-doi-password">Đổi mật khẩu</h1>
                        <form method="post" class="form-doi-password"> <button type="submit">X</button>';
                echo            '<p><lable for="txt_passwordnew">Nhập lại mật khẩu</label><input type="password" name="txt_passwordnew" placeholder="Nhập mật khẩu mới"> </p>
                            <p><lable for="txt_repasswordnew">Nhập lại mật khẩu</label><input type="password" name="txt_repasswordnew" placeholder="Nhập lại mật khẩu mới"></p>';
                echo            '<p><button type="submit" name="btn_RunDoiPassword">Đổi mật khẩu</button></p>';
                echo        '</form>
                    </div>';
            }
        }

        if (isset($_POST['btn_RunDoiPassword']))
        {
            $passnew = $_POST['txt_passwordnew'];
            $repassnew = $_POST['txt_repasswordnew'];

            $nv->doiMatKhau($_SESSION['admin_maNV'], $passnew, $repassnew);
        }
        ?>
    </div>

</header>