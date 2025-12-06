# 🩷FindU - ỨNG DỤNG WEB HẸN HÒ


## 1. Cấu trúc thư mục dự án

````markdown
FINDU/
│
├── app/ # Thư mục chính của ứng dụng
│ ├── config/ # Cấu hình hệ thống (database, constants, routes,...)
│ ├── controllers/ # Xử lý logic giữa model và view
│ ├── helpers/ # Các hàm tiện ích dùng chung (format, redirect,...)
| |── repositories # Tương tác với CSDL
│ ├── models/ # Định nghĩa các đối tượng (Oject)
│ └── views/ # Giao diện hiển thị cho người dùng
│ ├── admin/ # Giao diện dành cho quản trị viên
│ ├── includes/ # Các phần giao diện dùng chung (header, footer,...)
│ └── user/ # Giao diện cho người dùng
│
├── public/ # Tài nguyên tĩnh (public access)
│ ├── assets/ # CSS, JS, hình ảnh, font,...
│ │ ├── css/
│ │ ├── fonts/
│ │ ├── img/
│ │ ├── js/
│ │ └── video/
│ └── uploads/ # File người dùng tải lên (ảnh đại diện,...)
|   ├── avatars/
|   ├── images/
|   ├── videos/
│
└── index.php # File khởi động ứng dụng (Gateway)
````
### 💡 Ghi chú
- Cấu trúc theo mô hình **MVC (Model - View - Controller)**.
- Mục tiêu: tách biệt rõ ràng giữa **xử lý logic**, **giao diện**, và **dữ liệu**.

## 2. CSDL (code SQL)
````markdown
-- 1. Bảng Phòng Ban
CREATE TABLE phongban (
    maPB INT AUTO_INCREMENT PRIMARY KEY,
    tenPB VARCHAR(100)
);

-- 2. Bảng Nhân Viên
CREATE TABLE nhanvien (
    maNV INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    hoTen VARCHAR(100),
    ngaySinh DATE,
    gioiTinh CHAR(1),
    soDienThoai VARCHAR(20),
    email VARCHAR(100),
    chucVu ENUM('nhanvien', 'quanly'),
    maPB INT,
    diaChi VARCHAR(255),
    FOREIGN KEY (maPB) REFERENCES phongban(maPB)
);

-- 3. Bảng Thành Viên (Cập nhật thêm cột hocVan, hinh, bio)
CREATE TABLE thanhvien (
    maTV INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    anhDaiDien VARCHAR(255) DEFAULT 'avatar-default.svg',
    hoTen VARCHAR(100),
    gioiTinh CHAR(1),
    tuoi INT,
    diaChi VARCHAR(255),
    soThich VARCHAR(255),
    trangThai ENUM('hoatdong', 'khoa') DEFAULT 'hoatdong',
    moTa TEXT,
    ngayKhoa DATETIME,
    ngayMoKhoa DATETIME,
    hocVan VARCHAR(100),
    hinh VARCHAR(500),
    bio VARCHAR(250)
);

-- 4. Bảng Bài Viết
CREATE TABLE baiviet (
    maBV INT AUTO_INCREMENT PRIMARY KEY,
    noiDung TEXT,
    hinhAnh VARCHAR(255),
    video VARCHAR(255),
    quyenXem ENUM('cong_khai', 'ban_be', 'rieng_tu'),
    theTag VARCHAR(255),
    trangThai ENUM('da_duyet', 'cho_duyet'),
    thoiGianDang DATETIME,
    moTa TEXT,
    maTV INT,
    FOREIGN KEY (maTV) REFERENCES thanhvien(maTV)
);

-- 5. Bảng Bình Luận
CREATE TABLE binhluan (
    maBL INT AUTO_INCREMENT PRIMARY KEY,
    noiDung TEXT,
    thoiGianDang DATETIME,
    moTa TEXT,
    maBV INT,
    maTV INT,
    FOREIGN KEY (maBV) REFERENCES baiviet(maBV),
    FOREIGN KEY (maTV) REFERENCES thanhvien(maTV)
);

-- 6. Bảng Cuộc Trò Chuyện (Đổi tên từ CuocTroChuyen -> thanhvien_cuoctrochuyen)
CREATE TABLE thanhvien_cuoctrochuyen (
    maCTC INT AUTO_INCREMENT PRIMARY KEY,
    maTV1 INT NOT NULL,
    maTV2 INT NOT NULL,
    ngayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maTV1) REFERENCES thanhvien(maTV),
    FOREIGN KEY (maTV2) REFERENCES thanhvien(maTV)
);

-- 7. Bảng Tin Nhắn (Đổi tên từ TinNhan -> thanhvien_tinnhan, thêm cột hinh, video)
CREATE TABLE thanhvien_tinnhan (
    maTN INT AUTO_INCREMENT PRIMARY KEY,
    maCTC INT NOT NULL,
    noiDung TEXT,
    ngayGui DATETIME DEFAULT CURRENT_TIMESTAMP,
    trangThai ENUM('da_xem', 'chua_xem') DEFAULT 'chua_xem',
    maTVGui INT,
    maTVNhan INT,
    hinh VARCHAR(255),
    video VARCHAR(255),
    FOREIGN KEY (maCTC) REFERENCES thanhvien_cuoctrochuyen(maCTC),
    FOREIGN KEY (maTVGui) REFERENCES thanhvien(maTV),
    FOREIGN KEY (maTVNhan) REFERENCES thanhvien(maTV)
);

-- 8. Bảng Ghép Đôi (MỚI: Chức năng Like/Nope giống Tinder)
CREATE TABLE thanhvien_ghepdoi (
    maGhepDoi INT AUTO_INCREMENT PRIMARY KEY,
    maNguoiGui INT NOT NULL,
    maNguoiNhan INT NOT NULL,
    ngayGui DATETIME DEFAULT CURRENT_TIMESTAMP,
    trangThai ENUM('nope', 'like', 'superlike') NOT NULL,
    UNIQUE KEY unique_like (maNguoiGui, maNguoiNhan),
    FOREIGN KEY (maNguoiGui) REFERENCES thanhvien(maTV) ON DELETE CASCADE,
    FOREIGN KEY (maNguoiNhan) REFERENCES thanhvien(maTV) ON DELETE CASCADE
);

-- 9. Bảng Cặp Đôi (MỚI: Lưu các cặp đã match thành công)
CREATE TABLE thanhvien_capdoi (
    maCapDoi INT AUTO_INCREMENT PRIMARY KEY,
    maThanhVien1 INT NOT NULL,
    maThanhVien2 INT NOT NULL,
    ngayGhepDoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_match (maThanhVien1, maThanhVien2),
    FOREIGN KEY (maThanhVien1) REFERENCES thanhvien(maTV) ON DELETE CASCADE,
    FOREIGN KEY (maThanhVien2) REFERENCES thanhvien(maTV) ON DELETE CASCADE
);

-- 10. Bảng Báo Cáo
CREATE TABLE baocao (
    maBC INT AUTO_INCREMENT PRIMARY KEY,
    loaiViPham ENUM('baiviet', 'binhluan', 'thanhvien'),
    moTa TEXT,
    trangThai ENUM('da_duyet', 'cho_duyet'),
    thoiGianXL DATETIME,
    maTV INT,
    maNV INT,
    FOREIGN KEY (maTV) REFERENCES thanhvien(maTV),
    FOREIGN KEY (maNV) REFERENCES nhanvien(maNV)
);
````

### 💡 Ghi chú: Cách tạo CSDL MySQL trong xampp
- Tạo db với tên là 'findu_db' xong import file findu_db.sql
# LƯU Ý: Quy trình code chung

## Tạo nhánh mới từ develop:
````markdown
git checkout develop
git pull origin develop
git checkout -b feature/<ten-chuc-nang>
````

## Code & commit:
````markdown
git add .
git commit -m "Mô tả ngắn gọn thay đổi"
````

## Push lên GitHub:
````markdown
git push origin feature/<ten-chuc-nang>

# Nếu muốn code tiếp
## Chuyển sang nhánh main
git pull origin main

## Kéo code mới nhất từ nhánh main về máy
git pull origin main
````


# Nếu muốn code tiếp
## Chuyển sang nhánh main
````markdown
git pull origin main
````
## Kéo code mới nhất từ nhánh main về máy
````markdown
git pull origin main
````
