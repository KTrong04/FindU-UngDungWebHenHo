<head>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/sidebar.css">
</head>
<aside class="sidebar">
    <header class="sidebar-head">
        <div class="sidebar-user">
            <div class="sidebar-user-avarta">
                <a href="/project-FindU/app/views/user/hoSo.php?sidebar=profile"><img src="/project-FindU/public/uploads/avatars/<?php echo htmlspecialchars($_SESSION['user_avatar']); ?>" alt=""></a>
            </div>
            <span>Bạn</span>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-items"><a href="#"><img src="/project-FindU/public/assets/img/icon-explore.svg" alt="" class="sidebar-icon-menu"></a></li>
        </ul>
    </header>
    <main class="sidebar-content">
        <div class="sidebar-home">
            <div class="sidebar-box-menu">
                <ul class="sidebar-content-menu">
                    <li class="sidebar-content-items" onclick="border_items(this); loadBox('tuongHop')">Các Tương Hợp</li>
                    <li class="sidebar-content-items" onclick="border_items(this); loadBox('tinNhan')">Tin Nhắn</li>
                    <!-- <li class="sidebar-content-items" onclick="border_items(this); loadBox('thongBao')">Thông báo</li> -->
                </ul>
            </div>
            <div class="sidebar-box-main">
                <div id="sidebar-main">
                    <?php
                    if (isset($_GET['sidebar'])) {
                        if ($_GET['sidebar'] == 'profile') {
                            echo '<div class="sidebar-box-center">
                        <form action="" method="post">
                            <button type="submit" name="btn_signup" class="btn-profile" value="signup">Đăng xuất</button>
                        </form> 
                    </div>';

                            echo '<div class="sidebar-box-center">
                        <form action="" method="post">
                            <button type="submit" name="btn_doipassword" class="btn-profile" value="Đổi mật khẩu">Đổi mật khẩu</button>
                        </form> 
                    </div>';
                            include_once __DIR__ . '/../includes/footer.php';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        if (isset($_POST['btn_signup'])) {
            $tv->dangXuat();
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
            if ($tv->checkPasswordNow($pass, $_SESSION['user_maTV']) == true) {
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

        if (isset($_POST['btn_RunDoiPassword'])) {
            $passnew = $_POST['txt_passwordnew'];
            $repassnew = $_POST['txt_repasswordnew'];

            $tv->doiMatKhau($_SESSION['user_maTV'], $passnew, $repassnew);
        }
        ?>
    </main>
</aside>

<script src="/project-FindU/public/assets/js/sidebar.js"></script>