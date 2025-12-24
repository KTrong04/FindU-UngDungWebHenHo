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