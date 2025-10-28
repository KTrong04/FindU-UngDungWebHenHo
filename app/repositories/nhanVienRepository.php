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
}
