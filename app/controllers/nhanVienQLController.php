<?php
require_once __DIR__ . '/../repositories/nhanVienQLRepository.php';
require_once __DIR__ . '/../helpers/nhanVienHelper.php';


class nhanVienQLController
{
    private $repo;
    private $helper;

    public function __construct()
    {
        $this->repo = new nhanVienQLRepositories();
        $this->helper = new nhanVienHelper();
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function confignChucVuNVQL()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user = $this->repo->Read_One($_SESSION['admin_username']);

        if (!$user) {
            return false;
        } elseif ($user['chucVu'] != "quanly") {
            return false;
        }
        return true;
    }

    public function run_confignChucVu()
    {
        if ($this->confignChucVuNVQL() === false) {
            header("Location: /project-FindU/app/views/admin/");
            exit();
        }
    }

    // phong ban db
    public function se_phongBan($maPB_ac)
    {
        echo '<select name="se_phongban">';
        $phongBan = $this->repo->find_phongBan();
        foreach ($phongBan as $pb) {
            if ($pb['maPB'] == $maPB_ac) {
                echo '<option value="' . $pb['maPB'] . '" selected>' . $pb['tenPB'] . '</option>';
            } else {
                echo '<option value="' . $pb['maPB'] . '">' . $pb['tenPB'] . '</option>';
            }
        }
        echo '</select>';
    }


    public function addNhanVien($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan, $username, $password, $repassword)
    {
        $log_validate_info_NV = $this->helper->validate_info_NV($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan, $username, $password, $repassword);
        if ($log_validate_info_NV != true) {
            echo $log_validate_info_NV;
            return;
        }

        $log_kiemTraTrung = $this->helper->kiemTraTrung_info_nv($sdt, $email, $username);
        if ($log_kiemTraTrung != true) {
            echo $log_kiemTraTrung;
            return;
        }

        $password = $this->hashPassword($password);
        if ($this->repo->createNV($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan, $username, $password) === true) {
            echo 'Thêm nhân viên mới thành công';
        } else {
            echo 'Thêm nhân viên thất bại!';
        }
    }

    public function search_nhanVienQL($maNV)
    {
        $nv = $this->repo->findOne_NV($maNV);
        if (!$nv) {
            echo 'Không tìm thấy nhân viên!';
            return;
        }
        $_SESSION['maNV_search'] = $nv['maNV'];
        return $nv;
    }

    public function infoNV_update()
    {
        // Kiểm tra xem session có tồn tại không
        if (!isset($_SESSION['maNV_search'])) {
            echo 'Vui lòng chọn nhân viên cần cập nhật.';
            return;
        }

        // Lấy thông tin nhân viên
        $nv = $this->repo->findOne_NV($_SESSION['maNV_search']);

        // Nếu không tìm thấy nhân viên
        if (!$nv || !is_array($nv)) {
            echo 'Không tìm thấy thông tin nhân viên.';
            return;
        }

        // Khởi tạo biến checked/selected
        $nv_F = $nv_M = $nv_cv_nv = $nv_cv_nvql = '';

        if ($nv['gioiTinh'] === 'M') {
            $nv_M = ' checked';
        } else {
            $nv_F = ' checked';
        }

        if ($nv['chucVu'] === 'nhanvien') {
            $nv_cv_nv = ' selected';
        } else {
            $nv_cv_nvql = ' selected';
        }

        // Form hiển thị
        echo '
    <div class="box-form-info-nhanvien">
        <h1 class="title-form-info-nv">Cập nhật thông tin nhân viên</h1>
        <form action="" method="post" class="form-info-nhanvien">
            <div class="box-left-info-nv">
                <p>
                    <label for="">Họ và tên</label>
                    <input type="text" name="txt_hoTen" value="' . htmlspecialchars($nv['hoTen']) . '" placeholder="Nhập họ và tên">
                </p>

                <p>
                    <label for="">Ngày sinh</label>
                    <input type="date" name="txt_ngaySinh" value="' . htmlspecialchars($nv['ngaySinh']) . '" placeholder="Nhập ngày sinh">
                </p>

                <p>
                    <label for="">Giới tính</label>
                    <label for="rd_nam">Nam</label>
                    <input type="radio" name="rd_gioiTinh" id="rd_nam" value="M"' . $nv_M . '>
                    <label for="rd_nu">Nữ</label>
                    <input type="radio" name="rd_gioiTinh" id="rd_nu" value="F"' . $nv_F . '>
                </p>

                <p>
                    <label for="">Số điện thoại</label>
                    <input type="text" name="txt_sdt" value="' . htmlspecialchars($nv['soDienThoai']) . '" placeholder="Nhập số điện thoại">
                </p>

                <p>
                    <label for="">Email</label>
                    <input type="text" name="txt_email" value="' . htmlspecialchars($nv['email']) . '" placeholder="Nhập email">
                </p>

                <p>
                    <label for="">Địa chỉ</label>
                    <input type="text" name="txt_diaChi" value="' . htmlspecialchars($nv['diaChi']) . '" placeholder="Nhập địa chỉ">
                </p>
            </div>

            <div class="box-right-info-nv">
                <p>
                    <label for="">Chức vụ</label>
                    <select name="se_chucVu" id="">
                        <option value="nhanvien"' . $nv_cv_nv . '>Nhân viên</option>
                        <option value="quanly"' . $nv_cv_nvql . '>Quản lý</option>
                    </select>
                </p>

                <p>
                    <label for="">Phòng ban</label>';
        $this->se_phongBan($nv['maPB']);
        echo '
                </p>

                <p>
                    <button type="submit" name="btn_update_info_nv" value="Cập nhật">Lưu</button>
                </p>
            </div>
        </form>
    </div>';
    }


    public function capNhatThongTinNV($maNV, $hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan)
    {
        $log_validate_info_NV = $this->helper->validate_update_info_NV($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan);
        if ($log_validate_info_NV != true) {
            echo $log_validate_info_NV;
            return;
        }

        if ($this->repo->update_Info_NV($maNV, $hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan)) {
            echo 'Cập nhật thông tin nhân viên thành công';
            // unset($_SESSION['maNV_search']);
            return;
        } else {
            echo 'Cập nhật thông tin nhân viên thất bại';
            return;
        }
    }

    public function xoaNhanVien($maNV)
    {
        if ($this->repo->deleteNV($maNV)) {
            echo 'Xóa nhân viên thành công';
            return;
        } else {
            echo 'Xóa nhân viên thất bại';
            return;
        }
    }
}
