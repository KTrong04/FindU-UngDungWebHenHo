<?php
require_once __DIR__ . '/../config/dataBaseConnect.php';

class nhanVienQLRepositories
{
    private $conn;

    public function __construct()
    {
        $db = new dataBaseConnect();
        $this->conn = $db->connect();
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

    public function findOne_NV($maNV)
    {
        $sql = "
        SELECT n.*, p.tenPB
        FROM nhanvien n
        JOIN phongban p ON n.maPB = p.maPB
        WHERE n.maNV = :maNV
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maNV', $maNV);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findOne_NV_by_username($username)
    {
        $sql = "SELECT * FROM nhanvien WHERE username = :username
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_Info_NV($maNV, $hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan)
    {
        $sql = "
        UPDATE nhanvien
        SET 
            hoTen = :hoTen,
            ngaySinh = :ngaySinh,
            gioiTinh = :gioiTinh,
            soDienThoai = :sdt,
            email = :email,
            diaChi = :diaChi,
            chucVu = :chucVu,
            maPB = :phongBan
        WHERE maNV = :maNV
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':hoTen', $hoTen);
        $stmt->bindParam(':ngaySinh', $ngaySinh);
        $stmt->bindParam(':gioiTinh', $gioiTinh);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':diaChi', $diaChi);
        $stmt->bindParam(':chucVu', $chucVu);
        $stmt->bindParam(':phongBan', $phongBan);
        $stmt->bindParam(':maNV', $maNV);

        return $stmt->execute();
    }

    public function deleteNV($maNV)
    {
        $sql = "DELETE FROM nhanvien WHERE maNV = :maNV";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maNV', $maNV);
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
}
