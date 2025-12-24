<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_form_info.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_style.css">
</head>

<body>
    <?php include_once __DIR__ . '/../admin/includes/config.php'; ?>
    <div class="container">
        <?php include_once __DIR__ . '/../admin/includes/sidebar.php'; ?>
        <div class="content">
            <?php include_once __DIR__ . '/../admin/includes/header.php';
            $nvql->run_confignChucVu(); ?>
            <?php
            if (isset($_POST['btn_update_info_nv']) && isset($_SESSION['maNV_search'])) {
                $hoTen = $_POST['txt_hoTen'];
                $ngaySinh = $_POST['txt_ngaySinh'];
                $gioiTinh = isset($_POST['rd_gioiTinh']) ? $_POST['rd_gioiTinh'] : "";
                $sdt = $_POST['txt_sdt'];
                $email = $_POST['txt_email'];
                $diaChi = $_POST['txt_diaChi'];
                $chucVu = $_POST['se_chucVu'];
                $phongBan = $_POST['se_phongban'];
                $nvql->capNhatThongTinNV($_SESSION['maNV_search'], $hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan);
            }

            if (isset($_GET['btn_updateNV'])) {
                $nvql->infoNV_update();
            }
            ?>
            <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Lấy form cập nhật thông tin nhân viên
        const formUpdate = document.querySelector('.form-info-nhanvien');

        if (formUpdate) {
            formUpdate.addEventListener('submit', function(event) {
                // --- 1. KHAI BÁO CÁC BIẾN INPUT ---
                const hoTen = formUpdate.querySelector('input[name="txt_hoTen"]');
                const ngaySinh = formUpdate.querySelector('input[name="txt_ngaySinh"]');
                const gioiTinh = formUpdate.querySelector('input[name="rd_gioiTinh"]:checked');
                const sdt = formUpdate.querySelector('input[name="txt_sdt"]');
                const email = formUpdate.querySelector('input[name="txt_email"]');
                const diaChi = formUpdate.querySelector('input[name="txt_diaChi"]');
                // Các trường chức vụ và phòng ban thường có giá trị mặc định nên ít khi cần check rỗng, 
                // nhưng nếu cần bạn có thể thêm vào.

                // Hàm hiển thị lỗi và focus vào ô lỗi
                function showError(message, element) {
                    alert(message);
                    if (element) {
                        element.focus();
                    }
                    event.preventDefault(); // Chặn gửi form
                }

                // --- 2. BẮT ĐẦU KIỂM TRA TỪNG TRƯỜNG ---

                // 2.1. Họ và tên
                if (hoTen.value.trim() === "") {
                    return showError("Vui lòng nhập họ và tên.", hoTen);
                }

                // 2.2. Ngày sinh (Kiểm tra đủ 18 tuổi)
                if (ngaySinh.value === "") {
                    return showError("Vui lòng chọn ngày sinh.", ngaySinh);
                } else {
                    const today = new Date();
                    const birthDate = new Date(ngaySinh.value);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    if (age < 18) {
                        return showError("Nhân viên phải từ đủ 18 tuổi trở lên.", ngaySinh);
                    }
                }

                // 2.3. Giới tính
                if (!gioiTinh) {
                    alert("Vui lòng chọn giới tính.");
                    event.preventDefault();
                    return;
                }

                // 2.4. Số điện thoại (Regex VN: bắt đầu bằng 0, 10 số)
                const phoneRegex = /(84|0[3|5|7|8|9])+([0-9]{8})\b/g;
                if (sdt.value.trim() === "") {
                    return showError("Vui lòng nhập số điện thoại.", sdt);
                }
                if (!phoneRegex.test(sdt.value)) {
                    return showError("Số điện thoại không hợp lệ (Phải có 10 số và bắt đầu bằng số 0).", sdt);
                }

                // 2.5. Email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email.value.trim() === "") {
                    return showError("Vui lòng nhập Email.", email);
                }
                if (!emailRegex.test(email.value)) {
                    return showError("Định dạng Email không hợp lệ.", email);
                }

                // 2.6. Địa chỉ
                if (diaChi.value.trim() === "") {
                    return showError("Vui lòng nhập địa chỉ.", diaChi);
                }

                // Nếu chạy hết các dòng trên mà không return -> Form hợp lệ và sẽ được gửi đi
            });
        }
    });
</script>