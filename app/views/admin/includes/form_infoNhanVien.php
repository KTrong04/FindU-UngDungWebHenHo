<?php
require_once __DIR__ . '/../../../controllers/nhanVienQLController.php';
$nvql = new nhanVienQLController();
?>

<head>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_form_info.css">
</head>

<div class="box-form-info-nhanvien">
    <h1 class="title-form-info-nv">Thêm nhân viên mới</h1>
    <form action="" method="post" class="form-info-nhanvien">
        <div class="box-left-info-nv">
            <p>
                <label for="">Họ và tên</label>
                <input type="text" name="txt_hoTen" placeholder="Nhập họ và tên">
            </p>

            <p>
                <label for="">Ngày sinh</label>
                <input type="date" name="txt_ngaySinh" placeholder="Nhập ngày sinh">
            </p>

            <p>
                <label for="">Giới tính</label>
            <p>
                <label for="rd_nam">Nam</label>
                <input type="radio" name="rd_gioiTinh" id="rd_nam" value="M">
            </p>
            <p>
                <label for="rd_nu">Nữ</label>
                <input type="radio" name="rd_gioiTinh" id="rd_nu" value="F">
            </p>

            </p>

            <p>
                <label for="">Số điện thoại</label>
                <input type="text" name="txt_sdt" placeholder="Nhập số điện thoại">
            </p>
            <p>
                <label for="">Email</label>
                <input type="text" name="txt_email" placeholder="Nhập email">
            </p>
            <p>
                <label for="">Địa chỉ</label>
                <input type="text" name="txt_diaChi" placeholder="Nhập địa chỉ">
            </p>
        </div>
        <div class="box-right-info-nv">
            <p>
                <label for="">Chức vụ</label>
                <select name="se_chucVu" id="">
                    <option value="nhanvien">Nhân viên</option>
                    <option value="quanly">Quản lý</option>
                </select>

            </p>
            <p>
                <label for="">Phòng ban</label>
                <?php $nvql->se_phongBan(''); ?>
            </p>

            <p>
                <label for="">Tên đăng nhập</label>
                <input type="text" name="txt_username" placeholder="Nhập username">
            </p>
            <p>
                <label for="">Mật khẩu</label>
                <input type="password" name="txt_password" placeholder="Nhập password">
            </p>

            <p>
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" name="txt_repassword" placeholder="Nhập lại password">
            </p>
            <p>
                <button type="submit" name="btn_save_info_nv" value="Lưu">Lưu</button>
            </p>
        </div>
    </form>
    <?php
    if (isset($_POST['btn_save_info_nv'])) {
        $hoTen = $_POST['txt_hoTen'];
        $ngaySinh = $_POST['txt_ngaySinh'];
        $gioiTinh = isset($_POST['rd_gioiTinh']) ? $_POST['rd_gioiTinh'] : "";
        $sdt = $_POST['txt_sdt'];
        $email = $_POST['txt_email'];
        $diaChi = $_POST['txt_diaChi'];
        $chucVu = $_POST['se_chucVu'];
        $phongBan = $_POST['se_phongban'];
        $username = $_POST['txt_username'];
        $password = $_POST['txt_password'];
        $repassword = $_POST['txt_repassword'];

        $nvql->addNhanVien($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan, $username, $password, $repassword);
    }
    ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('.form-info-nhanvien');

        if (form) {
            form.addEventListener('submit', function(event) {
                // --- 1. KHAI BÁO CÁC BIẾN INPUT ---
                const hoTen = form.querySelector('input[name="txt_hoTen"]');
                const ngaySinh = form.querySelector('input[name="txt_ngaySinh"]');
                // Giới tính là Radio button, lấy cái nào được check
                const gioiTinh = form.querySelector('input[name="rd_gioiTinh"]:checked');
                const sdt = form.querySelector('input[name="txt_sdt"]');
                const email = form.querySelector('input[name="txt_email"]');
                const diaChi = form.querySelector('input[name="txt_diaChi"]');
                const username = form.querySelector('input[name="txt_username"]');
                const password = form.querySelector('input[name="txt_password"]');
                const rePassword = form.querySelector('input[name="txt_repassword"]');

                // Hàm hiển thị lỗi và focus vào ô lỗi
                function showError(message, element) {
                    alert(message);
                    if (element) {
                        element.focus();
                        // Nếu là radio thì không focus được như text, bỏ qua
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
                if (!gioiTinh) { // Nếu không có radio nào được check
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

                // 2.7. Tên đăng nhập
                if (username.value.trim() === "") {
                    return showError("Vui lòng nhập tên đăng nhập.", username);
                }
                if (username.value.length < 5) {
                    return showError("Tên đăng nhập phải dài hơn 5 ký tự.", username);
                }

                // 2.8. Mật khẩu (Kiểm tra độ mạnh như yêu cầu trước)
                const passVal = password.value;
                if (passVal === "") return showError("Vui lòng nhập mật khẩu.", password);

                // Quy tắc: 8 ký tự, 1 hoa, 1 thường, 1 số, 1 ký tự đặc biệt
                const strongPassRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;

                if (!strongPassRegex.test(passVal)) {
                    return showError("Mật khẩu yếu! Cần ít nhất 8 ký tự, gồm chữ HOA, thường, số và ký tự đặc biệt.", password);
                }

                // 2.9. Nhập lại mật khẩu
                if (rePassword.value === "") {
                    return showError("Vui lòng nhập lại mật khẩu.", rePassword);
                }
                if (passVal !== rePassword.value) {
                    return showError("Mật khẩu nhập lại không khớp.", rePassword);
                }

                // Nếu chạy hết các dòng trên mà không return -> Form hợp lệ và sẽ được gửi đi
            });
        }
    });
</script>