<?php
require_once __DIR__ . '/../repositories/nhanVienRepository.php';
require_once __DIR__ . '/../helpers/nhanVienHelper.php';


class nhanVienController
{
    private $repo;
    private $helper;

    public function __construct()
    {
        $this->repo = new nhanVienRepositories();
        $this->helper = new nhanVienHelper();
    }
    

    public function dangNhap($username, $password)
    {
        $log_validateInput = $this->helper->validateInputLogin($username, $password);
        if ($log_validateInput !== true) {
            echo $log_validateInput;
        } else {
            $user = $this->repo->Read_One($username);
            if ($user && password_verify($password, $user['password'])) {
                // Khởi tạo session
                session_start();
                $_SESSION['admin_maNV'] = $user['maNV'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_hoTen'] = $user['hoTen'];
                $_SESSION['admin_chucVu'] = $user['chucVu'];
                echo "Đăng nhập thành công! Chuyển hướng đến trang chủ...";
                header("Location: /project-FindU/app/views/admin/");
            } else {
                echo "Username hoặc password không đúng!";
            }
        }
    }

    public function dangXuat()
    {
        session_destroy();
        header("Location: /project-FindU/app/views/admin/");
    }

    public function checkPasswordNow($password, $maNV)
    {
        if ($password != "")
        {
            $pass_db = $this->repo->find_password_now($maNV);
            if (!password_verify($password, $pass_db['password']))
            {
                echo 'Mật khẩu không chính xác!';
                return;
            }
            else
            {
                return true;
            }
        }
        else
        {
            echo 'Vui lòng nhập mật khẩu hiện tại!';
            return;
        }
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }


    public function doiMatKhau($maNV, $passwordnew, $repasswordnew)
    {
        if ($passwordnew == "" || $repasswordnew == "")
        {
            echo 'Vui lòng nhập đầy đủ';
            return;
        }

        if ($passwordnew != $repasswordnew)
        {
            echo 'Mật khẩu không khớp với lòng kiểm tra lại';
            return;
        }

        $passwordnew = $this->hashPassword($passwordnew);
        if ($this->repo->updateNewPassword($maNV, $passwordnew))
        {
            echo 'Đổi mật khẩu thành công';
            return;
        }
        else
        {
            echo 'Đổi mật khẩu thất bại';
            return;
        }
    }

    public function configLogin()
    {
        session_start();
        if (!isset($_SESSION['admin_maNV']) || !isset($_SESSION['admin_username']) || !isset($_SESSION['admin_hoTen']) || !isset($_SESSION['admin_chucVu']) || $_SESSION['admin_maNV'] == "" || $_SESSION['admin_username'] == "" || $_SESSION['admin_hoTen'] == "" || $_SESSION['admin_chucVu'] == "") {
            return false;
        } else {
            $user = $this->repo->Read_One($_SESSION['admin_username']);
            if (!$user) {
                return false;
            } elseif ($user['maNV'] != $_SESSION['admin_maNV'] || $user['username'] != $_SESSION['admin_username'] || $user['hoTen'] != $_SESSION['admin_hoTen'] || $user['chucVu'] != $_SESSION['admin_chucVu']) {
                return false;
            }
            return true;
        }
    }


    public function searchThanhVien($maTV)
    {
        if ($maTV == "") {
            echo "Vui lòng nhập mã thành viên cần tìm!";
            return;
        }
        $userData = $this->repo->search_One_thanhVien($maTV);
        if (!$userData) {
            echo "Thành viên không tồn tại!";
            return;
        }
        $_SESSION['maTV_Khoa'] = $userData['maTV'];
        return $userData;
    }

    // Khoá tài khoản thành viên
    public function khoaThanhVien($maTV, $moTa, $ngayKhoa, $ngayMoKhoa, $loaiKhoa)
    {
        if ($loaiKhoa === "Khóa vĩnh viễn") {
            $ngayMoKhoa = NULL;
        }
        $result = $this->repo->khoaTaiKhoanTV($maTV, $moTa, $ngayKhoa, $ngayMoKhoa);
        if ($result == true) {
            echo $loaiKhoa . ' tài khoản thành viên thành công';
            return;
        } else {
            echo $loaiKhoa . ' không thành công vui lòng thử lại sau!';
            return;
        }
    }

    public function moKhoaThanhVien($maTV)
    {
        if ($this->repo->moKhoaTaiKhoanTV($maTV) === true) {
            echo 'Mở khóa tài khoản thành công';
            return;
        } else {
            echo 'Mở khóa tài khoản thất bại!';
            return;
        }
    }
    
    public function se_phongBan()
    {
        echo '<select name="se_phongban">';
        $phongBan = $this->repo->find_phongBan();
        foreach($phongBan as $pb)
        {
            echo '<option value="' . $pb['maPB'] . '">' . $pb['tenPB'] . '</option>';
        }
        echo '</select>';
    }


}
