<section class="sidebar">
    <p class="logo">
        <a href="/project-FindU/app/views/admin/">Dashboard - Admin</a>
    </p>

    <ul class="menu">
        <li class="items">
            <div class="menu-title">
                <a href="/project-FindU/app/views/admin/quanLyThanhVien.php?all=1">Quản lý thành viên</a>
            </div>
            <ul class="menu-level-2">
                <li class="items"><a href="/project-FindU/app/views/admin/khoaTaiKhoanTV.php">Khóa tài khoản</a></li>
                <li class="items"><a href="/project-FindU/app/views/admin/quanLyThanhVien.php?moKhoaTV=1">Mở khóa tài khoản</a></li>
            </ul>
        </li>
        <?php

        if (isset($_SESSION['admin_chucVu']) && $_SESSION['admin_chucVu'] === 'quanly') {
            echo '
            <li class="items">
                <div class="menu-title">
                    <a href="/project-FindU/app/views/admin/quanLyNhanVien.php?managerment_sidebar=1">Quản lý nhân viên</a>
                </div>
                <ul class="menu-level-2">
                    <li class="items"><a href="/project-FindU/app/views/admin/themNhanVien.php">Thêm nhân viên mới</a></li>
                    <li class="items"><a href="/project-FindU/app/views/admin/quanLyNhanVien.php?update_sidebar=1">Cập nhật thông tin nhân viên</a></li>
                    <li class="items"><a href="/project-FindU/app/views/admin/quanLyNhanVien.php?delete_sidebar=1">Xóa thông tin nhân viên</a></li>
                    <li class="items"><a href="/project-FindU/app/views/admin/quanLyNhanVien.php?info_sidebar=1">Xem thông tin nhân viên</a></li>
                </ul>
            </li>';
        }
        ?>
    </ul>

</section>