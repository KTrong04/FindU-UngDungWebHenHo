<header>
    <div class="box-header-left">
        <h3>Dashboard Overview</h3>
    </div>

    <div class="box-header-right">
        <div class="user-profile" onclick="toggleProfileMenu()">
            <img src="https://ui-avatars.com/api/?name=<?php echo isset($_SESSION['admin_hoTen']) ? $_SESSION['admin_hoTen'] : 'Quản trị viên';?>&background=00d2d3&color=fff" alt="Avatar" class="user-avatar">
            <span class="user-name">
                <?php echo isset($_SESSION['admin_hoTen']) ? $_SESSION['admin_hoTen'] : 'Quản trị viên'; ?>
                ▼
            </span>
        </div>

        <div class="profile-dropdown" id="profileDropdown">
            <form action="" method="post" style="width: 100%;">
                <button type="submit" name="btn_open_modal_password">
                    <svg style="width:16px; margin-right:8px" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17a2 2 0 0 0 2-2 2 2 0 0 0-2-2 2 2 0 0 0-2 2 2 2 0 0 0 2 2m6-9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V6a5 5 0 0 1 5-5 5 5 0 0 1 5 5v2h1m-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3Z"/></svg>
                    Đổi mật khẩu
                </button>
                <button type="submit" name="btn_signup">
                    <svg style="width:16px; margin-right:8px" fill="currentColor" viewBox="0 0 24 24"><path d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 0 1 2 2v2h-2V4H5v16h9v-2h2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9Z"/></svg>
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>
</header>

<?php
    // 1. Xử lý Đăng xuất
    if (isset($_POST['btn_signup'])) {
        $nv->dangXuat();
    }

    // 2. Logic Hiển thị Popup Đổi mật khẩu
    
    // Biến kiểm tra để hiển thị Modal
    $showModalStep1 = isset($_POST['btn_open_modal_password']);
    $showModalStep2 = false;

    // STEP 1: Nếu bấm nút trên menu -> Hiện Modal Nhập Pass Cũ
    if ($showModalStep1) {
        echo '
        <div class="modal-overlay">
            <div class="box-doi-password">
                <form method="post"><button class="btn-close-modal">×</button></form>
                
                <h1 class="title-doi-password">Xác thực mật khẩu</h1>
                <p style="color:#666; font-size:13px; margin-bottom:20px;">Vui lòng nhập mật khẩu hiện tại để tiếp tục.</p>
                
                <form method="post" class="form-doi-password">
                    <input type="password" name="txt_password" placeholder="Mật khẩu hiện tại" required autofocus>
                    <button type="submit" name="btn_checkPassword">Kiểm tra</button>
                </form>
            </div>
        </div>';
    }

    // STEP 2: Kiểm tra mật khẩu cũ -> Nếu đúng hiện Modal Nhập Pass Mới
    if (isset($_POST['btn_checkPassword'])) {
        $pass = $_POST['txt_password'];
        if ($nv->checkPasswordNow($pass, $_SESSION['admin_maNV']) == true) {
            $showModalStep2 = true;
            echo '
            <div class="modal-overlay">
                <div class="box-doi-password">
                    <form method="post"><button class="btn-close-modal">×</button></form>
                    
                    <h1 class="title-doi-password">Đổi mật khẩu mới</h1>
                    
                    <form method="post" class="form-doi-password">
                        <input type="password" name="txt_passwordnew" placeholder="Nhập mật khẩu mới" required>
                        <input type="password" name="txt_repasswordnew" placeholder="Nhập lại mật khẩu mới" required>
                        <button type="submit" name="btn_RunDoiPassword">Xác nhận đổi</button>
                    </form>
                </div>
            </div>';
        } else {
            // Thông báo sai mật khẩu (Javascript alert cho nhanh)
            echo '<script>alert("Mật khẩu hiện tại không đúng!");</script>';
        }
    }

    // STEP 3: Thực hiện đổi mật khẩu
    if (isset($_POST['btn_RunDoiPassword'])) {
        $passnew = $_POST['txt_passwordnew'];
        $repassnew = $_POST['txt_repasswordnew'];

        if($passnew === $repassnew) {
            $nv->doiMatKhau($_SESSION['admin_maNV'], $passnew, $repassnew);
            echo '<script>alert("Đổi mật khẩu thành công!"); window.location.href = window.location.href;</script>';
        } else {
            echo '<script>alert("Mật khẩu nhập lại không khớp!");</script>';
        }
    }
?>

<script>
    function toggleProfileMenu() {
        var dropdown = document.getElementById("profileDropdown");
        dropdown.classList.toggle("active");
    }

    // Click ra ngoài thì đóng menu
    window.onclick = function(event) {
        if (!event.target.closest('.user-profile') && !event.target.closest('.profile-dropdown')) {
            var dropdown = document.getElementById("profileDropdown");
            if (dropdown && dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
            }
        }
    }
</script>