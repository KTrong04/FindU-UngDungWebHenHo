<?php
require_once __DIR__ . '/../repositories/nhanVienRepository.php';


class nhanVienController
{
    private $repo;

    public function __construct()
    {
        $this->repo = new nhanVienRepositories();
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
        session_start();
        $_SESSION['maTV_Khoa'] = $userData['maTV'];
        return $userData;
    }

    // Khoá tài khoản thành viên
    public function khoaThanhVien($maTV, $moTa, $ngayKhoa, $ngayMoKhoa, $loaiKhoa)
    {        
        if ($loaiKhoa === "Khóa vĩnh viễn")
        {
            $ngayMoKhoa = NULL;
        }
        $result = $this->repo->khoaTaiKhoanTV($maTV, $moTa, $ngayKhoa, $ngayMoKhoa);
        if ($result == true)
        {
            echo $loaiKhoa . ' tài khoản thành viên thành công'; 
            return;
        }
        else
        {
            echo $loaiKhoa . ' không thành công vui lòng thử lại sau!';
            return;
        }
    }

    public function moKhoaThanhVien($maTV)
    {
        if ($this->repo->moKhoaTaiKhoanTV($maTV) === true)
        {
            echo 'Mở khóa tài khoản thành công';
            return;
        }
        else
        {
            echo 'Mở khóa tài khoản thất bại!';
            return;
        }
    }
}
