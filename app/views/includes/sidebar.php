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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tìm form chứa nút "Đổi mật khẩu" (btn_RunDoiPassword)
        // Chúng ta tìm nút submit có name='btn_RunDoiPassword' rồi lấy form cha của nó
        const btnSubmit = document.querySelector("button[name='btn_RunDoiPassword']");

        if (btnSubmit) {
            const form = btnSubmit.closest("form");

            form.addEventListener("submit", function(e) {
                // Lấy giá trị từ 2 ô input
                const passInput = form.querySelector("input[name='txt_passwordnew']");
                const rePassInput = form.querySelector("input[name='txt_repasswordnew']");

                const password = passInput.value;
                const rePassword = rePassInput.value;

                // Hàm hiển thị lỗi (Bạn có thể tùy chỉnh để hiện đẹp hơn thay vì alert)
                function showError(message, inputName) {
                    alert(message); // Hiện thông báo lỗi
                    // Focus vào ô bị lỗi
                    if (inputName === 'password') passInput.focus();
                    if (inputName === 'repassword') rePassInput.focus();
                }

                // --- 1. KIỂM TRA ĐỘ MẠNH MẬT KHẨU ---

                // Check rỗng
                if (password === "") {
                    e.preventDefault(); // Chặn gửi form
                    return showError("Vui lòng nhập mật khẩu mới.", "password");
                }

                // Check độ dài
                if (password.length < 8) {
                    e.preventDefault();
                    return showError("Mật khẩu phải có ít nhất 8 ký tự.", "password");
                }

                // Check chữ in HOA
                if (!/[A-Z]/.test(password)) {
                    e.preventDefault();
                    return showError("Mật khẩu phải chứa ít nhất một chữ IN HOA.", "password");
                }

                // Check chữ thường
                if (!/[a-z]/.test(password)) {
                    e.preventDefault();
                    return showError("Mật khẩu phải chứa ít nhất một chữ in thường.", "password");
                }

                // Check số
                if (!/[0-9]/.test(password)) {
                    e.preventDefault();
                    return showError("Mật khẩu phải chứa ít nhất một chữ số.", "password");
                }

                // Check KÝ TỰ ĐẶC BIỆT
                if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    e.preventDefault();
                    return showError("Mật khẩu phải chứa ít nhất một ký tự đặc biệt (VD: @, #, !, ...).", "password");
                }

                // --- 2. KIỂM TRA MẬT KHẨU NHẬP LẠI (RE-PASSWORD) ---

                if (rePassword === "") {
                    e.preventDefault();
                    return showError("Vui lòng nhập lại mật khẩu mới.", "repassword");
                }

                if (password !== rePassword) {
                    e.preventDefault();
                    return showError("Mật khẩu nhập lại không khớp. Vui lòng kiểm tra lại.", "repassword");
                }

                // Nếu chạy đến đây nghĩa là mọi thứ OK -> Form sẽ tự động submit
            });
        }
    });
</script>