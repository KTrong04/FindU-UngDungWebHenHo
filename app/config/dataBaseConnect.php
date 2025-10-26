<?php
class DataBaseConnect
{
    private $host = "localhost";     // Tên host 
    private $db_name = "findu_db"; // Tên database
    private $username = "admin";      // Tài khoản MySQL
    private $password = "12345";          // Mật khẩu
    public $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            // Chuỗi DSN để kết nối PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );

            // Thiết lập chế độ báo lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Trả về đối tượng kết nối
            return $this->conn;

        } catch (PDOException $e) {
            echo "Kết nối database thất bại: " . $e->getMessage();
            return null;
        }
    }
}
?>
