-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 12:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `findu_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `baiviet`
--

CREATE TABLE `baiviet` (
  `maBV` int(11) NOT NULL,
  `noiDung` text DEFAULT NULL,
  `hinhAnh` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `quyenXem` enum('cong_khai','ban_be','rieng_tu') DEFAULT NULL,
  `theTag` varchar(255) DEFAULT NULL,
  `trangThai` enum('da_duyet','cho_duyet') DEFAULT NULL,
  `thoiGianDang` datetime DEFAULT NULL,
  `moTa` text DEFAULT NULL,
  `maTV` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baiviet`
--

INSERT INTO `baiviet` (`maBV`, `noiDung`, `hinhAnh`, `video`, `quyenXem`, `theTag`, `trangThai`, `thoiGianDang`, `moTa`, `maTV`) VALUES
(34, 'I play your heart', 'img_690f0cd8c9f801762594008.gif,img_690f0cd8ca2841762594008.jpg', NULL, 'cong_khai', '#trap', 'da_duyet', '2025-11-08 16:26:48', NULL, 21),
(35, 'I play your heart', 'img_690f0ce8e309b1762594024.gif,img_690f0ce8e34161762594024.jpg', NULL, 'cong_khai', '#trap', 'cho_duyet', '2025-11-08 16:27:04', NULL, 21);

-- --------------------------------------------------------

--
-- Table structure for table `baocao`
--

CREATE TABLE `baocao` (
  `maBC` int(11) NOT NULL,
  `loaiViPham` enum('baiviet','binhluan','thanhvien') DEFAULT NULL,
  `moTa` text DEFAULT NULL,
  `trangThai` enum('da_duyet','cho_duyet') DEFAULT NULL,
  `thoiGianXL` datetime DEFAULT NULL,
  `maTV` int(11) DEFAULT NULL,
  `maNV` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `binhluan`
--

CREATE TABLE `binhluan` (
  `maBL` int(11) NOT NULL,
  `noiDung` text DEFAULT NULL,
  `thoiGianDang` datetime DEFAULT NULL,
  `moTa` text DEFAULT NULL,
  `maBV` int(11) DEFAULT NULL,
  `maTV` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `maNV` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `hoTen` varchar(100) DEFAULT NULL,
  `ngaySinh` date DEFAULT NULL,
  `gioiTinh` char(1) DEFAULT NULL,
  `soDienThoai` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `chucVu` enum('nhanvien','quanly') DEFAULT NULL,
  `maPB` int(11) DEFAULT NULL,
  `diaChi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`maNV`, `username`, `password`, `hoTen`, `ngaySinh`, `gioiTinh`, `soDienThoai`, `email`, `chucVu`, `maPB`, `diaChi`) VALUES
(10, 'trong123', '$2y$10$PXgZDVY9ClWZAfC/h3ZsOOHlBR3Kua75Ahajm8qdXQ9VdieyXdRTS', 'DOM CHUA', '2025-10-15', 'M', '123456672', 'trong21@gmail.com', 'quanly', 2, ''),
(11, 'trong1', '$2y$10$rlshi5R38FL.TCFAo3BqGO83SmgyW..8fuxWYFInjI1/d1hgWJvMm', 'Trần Kim Trọng', '2025-10-24', 'M', '123456673', 'trong32@gmail.com', 'nhanvien', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `phongban`
--

CREATE TABLE `phongban` (
  `maPB` int(11) NOT NULL,
  `tenPB` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phongban`
--

INSERT INTO `phongban` (`maPB`, `tenPB`) VALUES
(1, 'Merketing'),
(2, 'IT'),
(3, 'Nhân sự');

-- --------------------------------------------------------

--
-- Table structure for table `thanhvien`
--

CREATE TABLE `thanhvien` (
  `maTV` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `anhDaiDien` varchar(255) DEFAULT 'avatar-default.svg',
  `hoTen` varchar(100) DEFAULT NULL,
  `gioiTinh` char(1) DEFAULT NULL,
  `tuoi` int(11) DEFAULT NULL,
  `diaChi` varchar(255) DEFAULT NULL,
  `soThich` varchar(255) DEFAULT NULL,
  `trangThai` enum('hoatdong','khoa') DEFAULT 'hoatdong',
  `moTa` text DEFAULT NULL,
  `ngayKhoa` datetime DEFAULT NULL,
  `ngayMoKhoa` datetime DEFAULT NULL,
  `hocVan` varchar(100) DEFAULT NULL,
  `hinh` varchar(500) DEFAULT NULL,
  `bio` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhvien`
--

INSERT INTO `thanhvien` (`maTV`, `email`, `password`, `anhDaiDien`, `hoTen`, `gioiTinh`, `tuoi`, `diaChi`, `soThich`, `trangThai`, `moTa`, `ngayKhoa`, `ngayMoKhoa`, `hocVan`, `hinh`, `bio`) VALUES
(21, 'trankimtrongw1@gmail.com', '$2y$10$aUNPI2jrFWs0wTHw6kis2uHonS7H86PmcrKDuOnRDBJiIgab4paUe', 'avatar_692a4ab52501c1764379317.png', 'Trần Kim Trọng', 'M', 21, 'tp Hồ Chí Minh', 'Du lịch,Cà phê,Đọc sách,Âm nhạc,Thể thao,Nấu ăn,Anime,Xem phim,Game,Nghệ thuật,Điện ảnh,Chụp ảnh,Mua sắm', 'hoatdong', NULL, NULL, NULL, 'Trường đại học Công nghiệp TP HCM', NULL, 'Nice to meet you'),
(22, 'love@gmail.com', '$2y$10$J0jYEA4tSwbc7Abgjsl08.9MEHxkR79OPU/ZcBKwv4a2ci5TgR2YK', 'avatar_690f0e84d5c0a1762594436.png', 'My love', 'F', 21, 'Tp HCM', 'Du lịch, Đọc sách, Âm nhạc, Điện ảnh, Thiền, Yoga', 'hoatdong', NULL, NULL, NULL, 'Trường đại học công nghiệp TP Hồ Chí Minh', '', NULL),
(23, 'heart@gmail.com', '$2y$10$TlbFJ/Y14vosYLchsIbhKeAlFI8.KvJ9wbvpc3ECQRx7KGeLf4hZu', 'avatar_690f0f82215e21762594690.png', 'heart', 'F', 18, 'Tp HCM', 'Du lịch,Âm nhạc,Nấu ăn,Xem phim', 'hoatdong', NULL, NULL, NULL, 'IUH University', NULL, 'Nice to meet   you'),
(24, 'rockthatbody@gmail.com', '$2y$10$vN4eMaeqIOMimL.E2KvPwOqSBlB99hsqjMUw/AQoQ3SJGT6MRfF0G', 'avatar_690f105adac641762594906.png', 'rockthatbody', 'M', 25, 'Tp HCM', 'Đọc sách, Âm nhạc, Nấu ăn', 'hoatdong', NULL, NULL, NULL, NULL, '', NULL),
(25, 'everything@gmail.com', '$2y$10$ZT9H3F454MPTVxHQFms6LuuruXAqyT4zKb5ZJzwA1oNzXuTuKoyOq', 'avatar_690f11ba1d01a1762595258.png', 'everything', 'M', 25, 'Ha Noi City', 'Đọc sách, Nấu ăn, Xem phim', 'hoatdong', NULL, NULL, NULL, NULL, '', NULL),
(26, 'post@gmail.com', '$2y$10$q1xyb6dxdk5epm005B3VBe6axbR.r3xFll1Tg79TCUauFYTc2S0RG', 'avatar_690f12c6daeec1762595526.png', 'Post', 'F', 18, 'Ha Noi City', 'Nấu ăn, Xem phim, Thiền', 'hoatdong', NULL, NULL, NULL, NULL, '', NULL),
(27, 'skyfall@gmail.com', '$2y$10$zRohxZHE2afJKs9KId6Au.uSbPecvI5RSHyYO1ihZkuL7i9J9woFm', 'avatar_690f13dc14df01762595804.png', 'skyfall', 'M', 19, 'Ha Noi City', 'Nấu ăn, Xem phim, Thiền', 'hoatdong', NULL, NULL, NULL, NULL, '', NULL),
(28, 'dynamic@gmail.com', '$2y$10$iuwf7C6QikSwcJNoQMDkpeHpy/GSglcs12Lrefi3FwrPYGYug6Lju', 'avatar_69145561a61971762940257.jpg', 'dynamic', 'M', 18, 'Tp HCM', 'Du lịch, Cà phê, Đọc sách, Âm nhạc, Thể thao, Nấu ăn, Anime, Xem phim, Game, Nghệ thuật, Điện ảnh, Chụp ảnh, Thiền, Yoga, Thể hình, Coffee', 'hoatdong', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'chi@gmail.com', '$2y$10$HHIoe7qJv/i0uXM/isi.O.8HPyCNFFGHykq8/gv.ab6pDKP0hKvoC', 'avatar_6915e067183691763041383.png', 'quynhchi', 'M', 19, 'Ha Noi City', 'Âm nhạc, Nấu ăn, Chụp ảnh', 'hoatdong', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'king@gmail.com', '$2y$10$BhVTIcM5XN0kZcyEYTIlP.Vrg3b5CQrzrBmEJbcxJgwriLaCMobr2', 'avatar_6915e937852581763043639.png', 'king', 'F', 18, 'Tp HCM', 'Du lịch, Cà phê, Đọc sách, Âm nhạc, Thể thao, Nấu ăn, Anime, Xem phim, Game, Nghệ thuật, Điện ảnh, Chụp ảnh, Thiền, Yoga, Thể hình, Coffee', 'hoatdong', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thanhvien_capdoi`
--

CREATE TABLE `thanhvien_capdoi` (
  `maCapDoi` int(11) NOT NULL,
  `maThanhVien1` int(11) NOT NULL,
  `maThanhVien2` int(11) NOT NULL,
  `ngayGhepDoi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhvien_capdoi`
--

INSERT INTO `thanhvien_capdoi` (`maCapDoi`, `maThanhVien1`, `maThanhVien2`, `ngayGhepDoi`) VALUES
(103, 21, 22, '2025-12-01 22:02:29');

-- --------------------------------------------------------

--
-- Table structure for table `thanhvien_cuoctrochuyen`
--

CREATE TABLE `thanhvien_cuoctrochuyen` (
  `maCTC` int(11) NOT NULL,
  `maTV1` int(11) NOT NULL,
  `maTV2` int(11) NOT NULL,
  `ngayTao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhvien_cuoctrochuyen`
--

INSERT INTO `thanhvien_cuoctrochuyen` (`maCTC`, `maTV1`, `maTV2`, `ngayTao`) VALUES
(6, 22, 21, '2025-11-29 11:49:07'),
(7, 21, 23, '2025-11-29 12:35:47');

-- --------------------------------------------------------

--
-- Table structure for table `thanhvien_ghepdoi`
--

CREATE TABLE `thanhvien_ghepdoi` (
  `maGhepDoi` int(11) NOT NULL,
  `maNguoiGui` int(11) NOT NULL,
  `maNguoiNhan` int(11) NOT NULL,
  `ngayGui` datetime DEFAULT current_timestamp(),
  `trangThai` enum('nope','like','superlike') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhvien_ghepdoi`
--

INSERT INTO `thanhvien_ghepdoi` (`maGhepDoi`, `maNguoiGui`, `maNguoiNhan`, `ngayGui`, `trangThai`) VALUES
(200, 23, 21, '2025-12-01 22:01:07', 'superlike'),
(201, 22, 21, '2025-12-01 22:01:46', 'like');

-- --------------------------------------------------------

--
-- Table structure for table `thanhvien_tinnhan`
--

CREATE TABLE `thanhvien_tinnhan` (
  `maTN` int(11) NOT NULL,
  `maCTC` int(11) NOT NULL,
  `noiDung` text DEFAULT NULL,
  `ngayGui` datetime DEFAULT current_timestamp(),
  `trangThai` enum('da_xem','chua_xem') DEFAULT 'chua_xem',
  `maTVGui` int(11) DEFAULT NULL,
  `maTVNhan` int(11) DEFAULT NULL,
  `hinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhvien_tinnhan`
--

INSERT INTO `thanhvien_tinnhan` (`maTN`, `maCTC`, `noiDung`, `ngayGui`, `trangThai`, `maTVGui`, `maTVNhan`, `hinh`, `video`) VALUES
(101, 7, 'hi, nice to meet you', '2025-11-29 14:38:24', 'chua_xem', 23, 21, NULL, NULL),
(102, 7, 'hihi', '2025-11-29 14:38:33', 'chua_xem', 21, 23, NULL, NULL),
(103, 7, '', '2025-11-29 14:38:43', 'chua_xem', 23, 21, 'img_692aa303801c51764401923.jpg', NULL),
(104, 6, 'Hello', '2025-11-29 14:51:37', 'chua_xem', 21, 22, NULL, NULL),
(105, 6, 'Nice to meet you', '2025-11-29 14:51:41', 'chua_xem', 21, 22, NULL, NULL),
(106, 6, 'ok', '2025-11-29 14:51:54', 'chua_xem', 22, 21, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baiviet`
--
ALTER TABLE `baiviet`
  ADD PRIMARY KEY (`maBV`),
  ADD KEY `maTV` (`maTV`);

--
-- Indexes for table `baocao`
--
ALTER TABLE `baocao`
  ADD PRIMARY KEY (`maBC`),
  ADD KEY `maTV` (`maTV`),
  ADD KEY `maNV` (`maNV`);

--
-- Indexes for table `binhluan`
--
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`maBL`),
  ADD KEY `maBV` (`maBV`),
  ADD KEY `maTV` (`maTV`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`maNV`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `maPB` (`maPB`);

--
-- Indexes for table `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`maPB`);

--
-- Indexes for table `thanhvien`
--
ALTER TABLE `thanhvien`
  ADD PRIMARY KEY (`maTV`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `thanhvien_capdoi`
--
ALTER TABLE `thanhvien_capdoi`
  ADD PRIMARY KEY (`maCapDoi`),
  ADD UNIQUE KEY `unique_match` (`maThanhVien1`,`maThanhVien2`),
  ADD KEY `maThanhVien2` (`maThanhVien2`);

--
-- Indexes for table `thanhvien_cuoctrochuyen`
--
ALTER TABLE `thanhvien_cuoctrochuyen`
  ADD PRIMARY KEY (`maCTC`),
  ADD KEY `maTV1` (`maTV1`),
  ADD KEY `maTV2` (`maTV2`);

--
-- Indexes for table `thanhvien_ghepdoi`
--
ALTER TABLE `thanhvien_ghepdoi`
  ADD PRIMARY KEY (`maGhepDoi`),
  ADD UNIQUE KEY `unique_like` (`maNguoiGui`,`maNguoiNhan`),
  ADD KEY `maNguoiDuocThich` (`maNguoiNhan`);

--
-- Indexes for table `thanhvien_tinnhan`
--
ALTER TABLE `thanhvien_tinnhan`
  ADD PRIMARY KEY (`maTN`),
  ADD KEY `maCTC` (`maCTC`),
  ADD KEY `maTVGui` (`maTVGui`),
  ADD KEY `maTVNhan` (`maTVNhan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `baiviet`
--
ALTER TABLE `baiviet`
  MODIFY `maBV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `baocao`
--
ALTER TABLE `baocao`
  MODIFY `maBC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `maBL` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `maNV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `phongban`
--
ALTER TABLE `phongban`
  MODIFY `maPB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `thanhvien`
--
ALTER TABLE `thanhvien`
  MODIFY `maTV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `thanhvien_capdoi`
--
ALTER TABLE `thanhvien_capdoi`
  MODIFY `maCapDoi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `thanhvien_cuoctrochuyen`
--
ALTER TABLE `thanhvien_cuoctrochuyen`
  MODIFY `maCTC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `thanhvien_ghepdoi`
--
ALTER TABLE `thanhvien_ghepdoi`
  MODIFY `maGhepDoi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `thanhvien_tinnhan`
--
ALTER TABLE `thanhvien_tinnhan`
  MODIFY `maTN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baiviet`
--
ALTER TABLE `baiviet`
  ADD CONSTRAINT `baiviet_ibfk_1` FOREIGN KEY (`maTV`) REFERENCES `thanhvien` (`maTV`);

--
-- Constraints for table `baocao`
--
ALTER TABLE `baocao`
  ADD CONSTRAINT `baocao_ibfk_1` FOREIGN KEY (`maTV`) REFERENCES `thanhvien` (`maTV`),
  ADD CONSTRAINT `baocao_ibfk_2` FOREIGN KEY (`maNV`) REFERENCES `nhanvien` (`maNV`);

--
-- Constraints for table `binhluan`
--
ALTER TABLE `binhluan`
  ADD CONSTRAINT `binhluan_ibfk_1` FOREIGN KEY (`maBV`) REFERENCES `baiviet` (`maBV`),
  ADD CONSTRAINT `binhluan_ibfk_2` FOREIGN KEY (`maTV`) REFERENCES `thanhvien` (`maTV`);

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`maPB`) REFERENCES `phongban` (`maPB`);

--
-- Constraints for table `thanhvien_capdoi`
--
ALTER TABLE `thanhvien_capdoi`
  ADD CONSTRAINT `thanhvien_capdoi_ibfk_1` FOREIGN KEY (`maThanhVien1`) REFERENCES `thanhvien` (`maTV`) ON DELETE CASCADE,
  ADD CONSTRAINT `thanhvien_capdoi_ibfk_2` FOREIGN KEY (`maThanhVien2`) REFERENCES `thanhvien` (`maTV`) ON DELETE CASCADE;

--
-- Constraints for table `thanhvien_cuoctrochuyen`
--
ALTER TABLE `thanhvien_cuoctrochuyen`
  ADD CONSTRAINT `thanhvien_cuoctrochuyen_ibfk_1` FOREIGN KEY (`maTV1`) REFERENCES `thanhvien` (`maTV`),
  ADD CONSTRAINT `thanhvien_cuoctrochuyen_ibfk_2` FOREIGN KEY (`maTV2`) REFERENCES `thanhvien` (`maTV`);

--
-- Constraints for table `thanhvien_ghepdoi`
--
ALTER TABLE `thanhvien_ghepdoi`
  ADD CONSTRAINT `thanhvien_ghepdoi_ibfk_1` FOREIGN KEY (`maNguoiGui`) REFERENCES `thanhvien` (`maTV`) ON DELETE CASCADE,
  ADD CONSTRAINT `thanhvien_ghepdoi_ibfk_2` FOREIGN KEY (`maNguoiNhan`) REFERENCES `thanhvien` (`maTV`) ON DELETE CASCADE;

--
-- Constraints for table `thanhvien_tinnhan`
--
ALTER TABLE `thanhvien_tinnhan`
  ADD CONSTRAINT `thanhvien_tinnhan_ibfk_1` FOREIGN KEY (`maCTC`) REFERENCES `thanhvien_cuoctrochuyen` (`maCTC`),
  ADD CONSTRAINT `thanhvien_tinnhan_ibfk_2` FOREIGN KEY (`maTVGui`) REFERENCES `thanhvien` (`maTV`),
  ADD CONSTRAINT `thanhvien_tinnhan_ibfk_3` FOREIGN KEY (`maTVNhan`) REFERENCES `thanhvien` (`maTV`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
