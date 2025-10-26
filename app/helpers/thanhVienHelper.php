<?php
class thanhVienHelper
{
    public function validateInput($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword)
    {
        // Kiểm tra rỗng
        if (empty($hoTen) || empty($tuoi) || empty($gioiTinh) || empty($email) || empty($password) || empty($repassword)) {
            return "Vui lòng nhập đầy đủ thông tin.";
        }

        // // Kiểm tra độ dài họ tên
        // if (strlen($hoTen) < 3) {
        //     return "Họ tên phải có ít nhất 3 ký tự.";
        // }

        // // Kiểm tra tuổi hợp lệ (phải là >= 18)
        // if (!is_numeric($tuoi) || $tuoi < 18) {
        //     return "Tuổi không hợp lệ.";
        // }

        // // Kiểm tra email đúng định dạng
        // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //     return "Email không hợp lệ.";
        // }

        // // Kiểm tra độ dài mật khẩu
        // if (strlen($password) < 12) {
        //     return "Mật khẩu phải có ít nhất 12 ký tự.";
        // }

        // // Kiểm tra mật khẩu nhập lại
        // if ($password !== $repassword) {
        //     return "Mật khẩu nhập lại không khớp.";
        // }

        return true;
    }

    public function validateLoginInput($email, $password)
    {
        // Kiểm tra rỗng
        if (empty($email) || empty($password)) {
            return "Vui lòng nhập đầy đủ thông tin.";
        }

        // // Kiểm tra email đúng định dạng
        // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //     return "Email không hợp lệ.";
        // }

        return true;
    }
}
