<?php
class thanhVienHelper
{
    public function message($type, $text)
    {
        return "
            <div class='box-tb-$type'>
                <button type='button' class='close-btn'>&times;</button>
                $text
            </div>";
    }

    // 🧍‍♂️ Kiểm tra thông tin đăng ký
    public function validateInput($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword)
    {
        // Kiểm tra rỗng
        if (empty($hoTen) || empty($tuoi) || empty($gioiTinh) || empty($email) || empty($password) || empty($repassword)) {
            return $this->message('error', 'Vui lòng nhập đầy đủ thông tin.');
        }

        // Kiểm tra độ dài họ tên
        if (strlen($hoTen) < 3) {
            return $this->message('error', 'Họ tên phải có ít nhất 3 ký tự.');
        }

        // Kiểm tra tuổi hợp lệ (phải là >= 18)
        if (!is_numeric($tuoi) || $tuoi < 18) {
            return $this->message('error', 'Tuổi không hợp lệ (phải từ 18 trở lên).');
        }

        // Kiểm tra email đúng định dạng
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->message('error', 'Email không hợp lệ.');
        }

        // Kiểm tra độ dài mật khẩu
        if (strlen($password) < 8) {
            return $this->message('error', 'Mật khẩu phải có ít nhất 8 ký tự.');
        }

        // Kiểm tra mật khẩu nhập lại
        if ($password !== $repassword) {
            return $this->message('error', 'Mật khẩu nhập lại không khớp.');
        }

        return true;
    }

    // 🔐 Kiểm tra thông tin đăng nhập
    public function validateLoginInput($email, $password)
    {
        if (empty($email) || empty($password)) {
            return $this->message('error', 'Vui lòng nhập đầy đủ thông tin.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->message('error', 'Email không hợp lệ.');
        }

        return true;
    }

    // 📝 Kiểm tra bài viết
    public function validateInputDangBaiViet($noiDung, $hashtag, $quyenXem, $files)
    {
        if (empty($noiDung) && empty($hashtag) && (empty($files) || $files['error'][0] == UPLOAD_ERR_NO_FILE)) {
            return $this->message('error', 'Vui lòng nhập nội dung bài viết hoặc chọn tệp tin.');
        }

        return true;
    }
}
