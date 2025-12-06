<?php
    class thanhVienModel
    {
        public $email;
        public $password;
        public $anhDaiDien;
        public $hoTen;
        public $gioiTinh;
        public $tuoi;
        public $diaChi;
        public $soThich;
        public $trangThai;
        public $moTa;
        public $ngayKhoa;
        public $ngayMoKhoa;
        public $hocVan;
        public $hinh;
        public $bio;

        public function __construct(
            $hoTen = null, 
            $tuoi = null, 
            $gioiTinh = null, 
            $email = null, 
            $password = null,
            $anhDaiDien = null, // Giá trị mặc định như trong DB
            $diaChi = null,
            $soThich = null,
            $trangThai = 'hoatdong', // Giá trị mặc định
            $moTa = null,
            $ngayKhoa = null,
            $ngayMoKhoa = null,
            $hocVan = null,
            $hinh = null,
            $bio = null
        ) {
            $this->hoTen = $hoTen;
            $this->tuoi = $tuoi;
            $this->gioiTinh = $gioiTinh;
            $this->email = $email;
            $this->password = $password;
            $this->anhDaiDien = $anhDaiDien;
            $this->diaChi = $diaChi;
            $this->soThich = $soThich;
            $this->trangThai = $trangThai;
            $this->moTa = $moTa;
            $this->ngayKhoa = $ngayKhoa;
            $this->ngayMoKhoa = $ngayMoKhoa;
            $this->hocVan = $hocVan;
            $this->hinh = $hinh;
            $this->bio = $bio;
        }
    }
?>