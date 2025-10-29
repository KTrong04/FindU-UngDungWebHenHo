<?php
require_once __DIR__ . '/../config/DataBaseConnect.php';

class thanhVienModel
{
    public $hoTen, $gioiTinh, $tuoi;
    public $email, $password;
    private $conn;

    // --- Constructor để khởi tạo object dữ liệu ---
    public function __construct($hoTen = null, $tuoi = null, $gioiTinh = null, $email = null, $password = null)
    {
        $this->hoTen = $hoTen;
        $this->tuoi = $tuoi;
        $this->gioiTinh = $gioiTinh;
        $this->email = $email;
        $this->password = $password;

        // Tạo kết nối database
        $db = new DataBaseConnect();
        $this->conn = $db->connect();
    }

    // --- Hàm lấy mật khẩu hiện tại theo mã thành viên ---
    public function layMatKhau($maTV)
    {
        $sql = "SELECT password FROM thanhvien WHERE maTV = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$maTV]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password'] : null;
    }

    // ✅ Cập nhật mật khẩu mới
    public function capNhatMatKhau($maTV, $matKhauMoiHash)
    {
        $sql = "UPDATE thanhvien SET password = ? WHERE maTV = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$matKhauMoiHash, $maTV]);
    }

}
?>