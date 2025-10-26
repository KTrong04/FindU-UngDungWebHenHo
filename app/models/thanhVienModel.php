<?php
    class thanhVienModel
    {
        public $hoTen, $gioiTinh, $tuoi;
        public $email, $password;

        public function __construct($hoTen, $tuoi, $gioiTinh, $email, $password)
        {
            $this->hoTen = $hoTen;
            $this->tuoi = $tuoi;
            $this->gioiTinh = $gioiTinh;
            $this->email = $email;
            $this->password = $password;
        }
    }
?>