<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<section class="sidebar">
    <p class="logo">
        <a href="/project-FindU/app/views/admin/">FindU Admin</a>
    </p>

    <ul class="menu">
        <li class="items has-submenu">
            <div class="menu-title">
                <a href="/project-FindU/app/views/admin/duyetBaoCao.php">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="nav-icon" width="24" height="24" fill="currentColor">
                        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" opacity="0.3"></path>
                        <path d="M12 15c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm0 4c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm1-5h-2v-4h2v4z"></path>
                    </svg>
                    Duyệt yêu cầu báo cáo
                </a>
            </div>
        </li>
        <li class="items has-submenu">
            <div class="menu-title">
                <a href="javascript:void(0);" class="menu-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="nav-icon">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h7v-2.5c0-1.33 1.07-2.5 2.67-2.5.21 0 .42.02.62.05C10.51 13.56 9.36 13 8 13z" opacity="0.3"></path>
                        <path d="M16 13c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"></path>
                    </svg>
                    Quản lý thành viên
                    <span class="arrow">▼</span>
                </a>
            </div>
            <ul class="menu-level-2">
                <li class="items"><a href="/project-FindU/app/views/admin/khoaTaiKhoanTV.php">Khóa tài khoản</a></li>
                <li class="items"><a href="/project-FindU/app/views/admin/quanLyThanhVien.php?moKhoaTV=1">Mở khóa tài khoản</a></li>
            </ul>
        </li>

        <?php
        // Đảm bảo session đã được start ở đầu file cha
        if (isset($_SESSION['admin_chucVu']) && $_SESSION['admin_chucVu'] === 'quanly') {
            echo '
            <li class="items has-submenu">
                <div class="menu-title">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="nav-icon">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" opacity="0.3"></path>
                            <path d="M12 14c-2.67 0-8 1.34-8 4v1c0 .55.45 1 1 1h14c.55 0 1-.45 1-1v-1c0-2.66-5.33-4-8-4z"></path>
                            <path d="M11.5 10h1v3h-1z"></path> 
                        </svg>
                        Quản lý nhân viên
                        <span class="arrow">▼</span>
                    </a>
                </div>
                <ul class="menu-level-2">
                    <li class="items"><a href="/project-FindU/app/views/admin/themNhanVien.php">Thêm nhân viên mới</a></li>
                    <li class="items"><a href="/project-FindU/app/views/admin/quanLyNhanVien.php?update_sidebar=1">Cập nhật thông tin</a></li>
                    <li class="items"><a href="/project-FindU/app/views/admin/quanLyNhanVien.php?delete_sidebar=1">Xóa thông tin</a></li>
                    <li class="items"><a href="/project-FindU/app/views/admin/quanLyNhanVien.php?info_sidebar=1">Xem thông tin</a></li>
                </ul>
            </li>';
        }
        ?>
    </ul>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Lấy tất cả các nút có class menu-toggle
        const toggles = document.querySelectorAll('.menu-toggle');

        toggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                // Ngăn chặn chuyển trang (nếu thẻ a có href)
                e.preventDefault();

                // Tìm thẻ cha (li.items)
                const parentItem = this.closest('.items');

                // Tìm menu con (ul.menu-level-2)
                const submenu = parentItem.querySelector('.menu-level-2');

                if (submenu) {
                    // Đóng tất cả các menu khác (nếu muốn chế độ Accordion - chỉ mở 1 cái 1 lúc)
                    document.querySelectorAll('.menu-level-2').forEach(item => {
                        if (item !== submenu) {
                            item.classList.remove('d-block');
                            item.closest('.items').classList.remove('menu-open');
                        }
                    });


                    // Toggle class hiển thị cho menu con
                    submenu.classList.toggle('d-block');

                    // Toggle class menu-open cho thẻ cha để xoay mũi tên
                    parentItem.classList.toggle('menu-open');
                }
            });
        });
    });
</script>