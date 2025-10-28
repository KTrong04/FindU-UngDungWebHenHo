<?php
require_once __DIR__ . '/../config/dataBaseConnect.php';
require_once __DIR__ . '/../models/thanhVienModel.php';

class thanhVienRepository
{
    private $conn;

    public function __construct()
    {
        $db = new dataBaseConnect();
        $this->conn = $db->connect();
    }

    //  ThanhVien
    // Create
    public function create($hoTen, $tuoi, $gioiTinh, $email, $password)
    {
        $thanhVien = new thanhVienModel($hoTen, $tuoi, $gioiTinh, $email, $password);

        // Kiểm tra email đã tồn tại chưa
        $checkSql = "SELECT COUNT(*) FROM thanhvien WHERE email = :email";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindParam(':email', $thanhVien->email);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            // Email đã tồn tại -> không thêm nữa
            return false;
        }

        // Nếu email chưa tồn tại, tiến hành thêm mới
        $sql = "INSERT INTO thanhvien (hoTen, tuoi, gioiTinh, email, password)
                VALUES (:hoTen, :tuoi, :gioiTinh, :email, :password)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':hoTen', $thanhVien->hoTen);
        $stmt->bindParam(':tuoi', $thanhVien->tuoi);
        $stmt->bindParam(':gioiTinh', $thanhVien->gioiTinh);
        $stmt->bindParam(':email', $thanhVien->email);
        $stmt->bindParam(':password', $thanhVien->password);

        return $stmt->execute();
    }

    // Read
    public function Read_One($email)
    {
        $sql = "SELECT * FROM thanhvien WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Read all
    public function Read_All()
    {
        $sql = "SELECT * FROM thanhvien";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function update($id, $hoTen, $tuoi, $gioiTinh, $email)
    {
        $sql = "UPDATE thanhvien 
                SET hoTen = :hoTen, tuoi = :tuoi, gioiTinh = :gioiTinh, email = :email 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':hoTen', $hoTen);
        $stmt->bindParam(':tuoi', $tuoi);
        $stmt->bindParam(':gioiTinh', $gioiTinh);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Delete
    public function delete($id)
    {
        $sql = "DELETE FROM thanhvien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // BaiViet
    // Create
    public function createBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $files)
    {
        // Lấy danh sách ảnh và video (mảng trả về từ handleUploadFiles)
        $images = !empty($files['images']) ? implode(',', $files['images']) : null;
        $videos = !empty($files['videos']) ? implode(',', $files['videos']) : null;

        $sql = "INSERT INTO baiviet (
                maTV, noiDung, theTag, quyenXem, thoiGianDang, hinhAnh, video, trangThai, moTa
            ) VALUES (
                :maTV, :noiDung, :theTag, :quyenXem, NOW(), :hinhAnh, :video, 'cho_duyet', NULL
            )";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->bindParam(':noiDung', $noiDung);
        $stmt->bindParam(':theTag', $hashtag);
        $stmt->bindParam(':quyenXem', $quyenXem);
        $stmt->bindParam(':hinhAnh', $images);
        $stmt->bindParam(':video', $videos);

        return $stmt->execute();
    }

    // Read all Bai Viet
    public function readAll_BaiViet($maTV)
    {
        $sql = "SELECT * FROM baiviet WHERE maTV = :maTV ORDER BY thoiGianDang DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
