<?php
require_once __DIR__ . '/../repositories/thanhVienRepository.php';
require_once __DIR__ . '/../helpers/thanhVienHelper.php';

class thanhVienController
{
    private $repo;
    private $helper;

    public function __construct()
    {
        $this->repo = new thanhVienRepository();
        $this->helper = new thanhVienHelper();
    }
    // acount
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function dangKy($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword)
    {
        $log_validateInput = $this->helper->validateInput($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword);
        if ($log_validateInput !== true)
        {
            echo $log_validateInput;
        }
        else
        {
            $password = $this->hashPassword($password);
            $this->repo->create($hoTen, $tuoi, $gioiTinh, $email, $password);
            echo "Đăng ký thành công! Chuyển hướng đến trang đăng nhập...";
            header("refresh: 2, url=/project-FindU/app/views/user/dangNhap.php");
        }
    }

    public function dangNhap($email, $password)
    {
        $log_validateInput = $this->helper->validateLoginInput($email, $password);
        if ($log_validateInput !== true)
        {
            echo $log_validateInput;
        }
        else
        {
            $user = $this->repo->Read_One($email);
            if ($user && password_verify($password, $user['password']))
            {
                // Khởi tạo session
                session_start();
                $_SESSION['user_maTV'] = $user['maTV'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['hoTen'];
                echo "Đăng nhập thành công! Chuyển hướng đến trang chủ...";
                header("refresh: 2, url=/project-FindU/app/views/user/trangChu.php");
            }
            else
            {
                echo "Email hoặc mật khẩu không đúng!";
            }
        }
    }

    public function configLogin()
    {
        session_start();
        if (!isset($_SESSION['user_maTV']) || !isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || $_SESSION['user_maTV'] == "" || $_SESSION['user_email'] == "" || $_SESSION['user_name'] == "")
        {
            return false;
        }
        else
        {
            $user = $this->repo->Read_One($_SESSION['user_email']);
            if (!$user)
            {
                return false;
            }
            elseif ($user['maTV'] != $_SESSION['user_maTV'] || $user['email'] != $_SESSION['user_email'] || $user['hoTen'] != $_SESSION['user_name'])
            {
                return false;
            }
            return true;
        }
    }

    // person
    public function dangBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $files)
    {
        return;
        // Chức năng đăng bài viết sẽ được triển khai ở đây
    }
}
