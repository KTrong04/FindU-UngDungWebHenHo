<?php
require_once __DIR__ . '/../config/dataBaseConnect.php';

class nhanVienRepositories
{
    private $conn;

    public function __construct()
    {
        $db = new dataBaseConnect();
        $this->conn = $db->connect();
    }

    // thanhvien db
    public function search_One_thanhVien($maTV)
    {
        $sql = "SELECT * FROM thanhvien WHERE maTV = :maTV";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function khoaTaiKhoanTV($maTV, $moTa, $ngayKhoa, $ngayMoKhoa)
    {
        $sql = "UPDATE thanhvien 
            SET moTa = :moTa, 
                ngayKhoa = :ngayKhoa, 
                ngayMoKhoa = :ngayMoKhoa,
                trangThai = :trangThai
            WHERE maTV = :maTV";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':moTa', $moTa);
        $stmt->bindParam(':ngayKhoa', $ngayKhoa);
        $stmt->bindParam(':ngayMoKhoa', $ngayMoKhoa);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->bindValue(':trangThai', 'khoa');

        if ($stmt->execute()) {
            return true;  // cập nhật thành công
        } else {
            return false; // cập nhật thất bại
        }
    }

    public function moKhoaTaiKhoanTV($maTV)
    {
        $sql = "UPDATE thanhvien 
            SET moTa = :moTa, 
                ngayKhoa = :ngayKhoa, 
                ngayMoKhoa = :ngayMoKhoa,
                trangThai = :trangThai
            WHERE maTV = :maTV";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':moTa', 'Được mở khóa lại vào ' . time());
        $stmt->bindValue(':ngayKhoa', NULL);
        $stmt->bindValue(':ngayMoKhoa', NULL);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->bindValue(':trangThai', 'hoatdong');

        if ($stmt->execute()) {
            return true;  // cập nhật thành công
        } else {
            return false; // cập nhật thất bại
        }
    }

    // nhanvien db
    public function Read_One($username)
    {
        $sql = "SELECT * FROM nhanvien WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createNV($hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan, $username, $password)
    {
        $sql = "INSERT INTO nhanvien 
            (username, password, hoTen, ngaySinh, gioiTinh, soDienThoai, email, chucVu, maPB, diaChi) 
            VALUES 
            (:username, :password, :hoTen, :ngaySinh, :gioiTinh, :sdt, :email, :chucVu, :phongBan, :diaChi)";

        $stmt = $this->conn->prepare($sql);

        // Gán giá trị vào các placeholder
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':hoTen', $hoTen);
        $stmt->bindParam(':ngaySinh', $ngaySinh);
        $stmt->bindParam(':gioiTinh', $gioiTinh);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':chucVu', $chucVu);
        $stmt->bindParam(':phongBan', $phongBan);
        $stmt->bindParam(':diaChi', $diaChi);

        // Thực thi truy vấn
        if ($stmt->execute()) {
            return true;  // Thêm thành công
        } else {
            return false; // Thêm thất bại
        }
    }

    public function find_password_now($maNV)
    {
        $sql = "SELECT password FROM nhanvien WHERE maNV = :maNV";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindParam(':maNV', $maNV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateNewPassword($maNV, $passwordnew)
    {
        $sql = "UPDATE nhanvien 
                SET password = :password
                WHERE maNV = :maNV";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindParam(':maNV', $maNV);
        $stmt->bindParam(':password', $passwordnew);
        return $stmt->execute();
    }


    // phongban db
    public function find_phongBan()
    {
        $sql = "SELECT * FROM phongban";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // baocao db
    // Trong file Repository
    public function find_all_baoCao()
    {
        // Sử dụng ALIAS (AS) để đổi tên cột cho khớp với Code View của bạn
        // JOIN 2 lần với bảng thanhvien: 
        // - tv1 là người báo cáo
        // - tv2 là người bị báo cáo
        $sql = "SELECT 
                b.maBC AS id,
                b.loaiViPham AS reason,
                b.moTa AS `desc`, 
                b.trangThai AS status,
                b.thoiGianBaoCao AS time,
                tv1.hoTen AS reporter,
                tv2.hoTen AS accused,
                tv2.maTV AS accused_id
            FROM baocao b
            LEFT JOIN thanhvien tv1 ON b.maTV = tv1.maTV
            LEFT JOIN thanhvien tv2 ON b.maTV_bi_bao_cao = tv2.maTV
            ORDER BY b.thoiGianBaoCao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count_baoCao($trangThai)
    {
        $sql = "SELECT COUNT(*) AS count FROM baocao WHERE trangThai = :status";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $trangThai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function update_ignore_baoCao($maBC)
    {
        $sql = "UPDATE baocao 
                SET trangThai = :status
                WHERE maBC = :maBC";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindParam(':maBC', $maBC);
        $stmt->bindValue(':status', 'da_duyet');
        return $stmt->execute();
    }
}
