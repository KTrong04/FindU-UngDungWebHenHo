<?php
require_once __DIR__ . '/../repositories/nhanVienQLRepository.php';
class nhanVienHelper
{

    private $repo;

    public function __construct()
    {
        $this->repo = new nhanVienQLRepositories();
    }
    public function validateInputLogin($username, $password)
    {
        if ($username == "" || $password == "") {
            return "Vui lòng nhập đầy đủ thông tin!";
        }

        return true;
    }

    public function validate_info_NV($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan, $username, $password, $repassword)
    {
        if ($hoTen == "" || $ngaySinh == "" || $gioiTinh == "" || $sdt == "" || $email == "" || $chucVu == "" || $phongBan == "" || $username == "" || $password == "" || $repassword == "") {
            echo '<div class="alert-msg alert-error">Vui lòng nhập nhập đầy đủ thông tin!</div>';
            return;
        }

        return true;
    }

    public function validate_update_info_NV($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan)
    {
        if ($hoTen == "" || $ngaySinh == "" || $gioiTinh == "" || $sdt == "" || $email == "" || $chucVu == "" || $phongBan == "") {
            echo "Vui lòng nhập nhập đầy đủ thông tin";
            return;
        }

        return true;
    }

    public function kiemTraTrung_info_nv($sdt, $email, $username)
    {
        $nv = $this->repo->findOne_NV_by_username($username);
        if ($nv) {
            if ($sdt == $nv['soDienThoai']) {
                echo 'Số điện thoại đã tồn tại';
                return;
            }

            if ($email == $nv['email']) {
                echo 'Email đã tồn tại';
                return;
            }

            if ($username == $nv['username']) {
                echo 'username đã tồn tại';
                return;
            }
        }
        return true;
    }
}
