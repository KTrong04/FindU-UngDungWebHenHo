# 🩷 FindU - ỨNG DỤNG WEB HẸN HÒ

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
│
└── index.php # File khởi động ứng dụng (Gateway)
````
### 💡 Ghi chú
- Cấu trúc theo mô hình **MVC (Model - View - Controller)**.
- Mục tiêu: tách biệt rõ ràng giữa **xử lý logic**, **giao diện**, và **dữ liệu**.

## 2. CSDL (code SQL)
CREATE TABLE PhongBan (
    maPB INT AUTO_INCREMENT PRIMARY KEY,
    tenPB VARCHAR(100)
);

CREATE TABLE NhanVien (
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
    FOREIGN KEY (maPB) REFERENCES PhongBan(maPB)
);

CREATE TABLE ThanhVien (
    maTV INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    anhDaiDien VARCHAR(255),
    hoTen VARCHAR(100),
    gioiTinh CHAR(1),
    tuoi INT,
    diaChi VARCHAR(255),
    soThich VARCHAR(255),
    trangThai ENUM('hoatdong', 'khoa'),
    moTa TEXT
);

CREATE TABLE BaiViet (
    maBV INT AUTO_INCREMENT PRIMARY KEY,
    noiDung TEXT,
    hinhAnh VARCHAR(255),
    video VARCHAR(255),
    quyenXem ENUM('cong_khai', 'ban_be', 'rieng_tu'),
    theTag VARCHAR(255),
    trangThai BOOLEAN,
    thoiGianDang DATETIME,
    moTa TEXT,
    maTV INT,
    FOREIGN KEY (maTV) REFERENCES ThanhVien(maTV)
);

CREATE TABLE BinhLuan (
    maBL INT AUTO_INCREMENT PRIMARY KEY,
    noiDung TEXT,
    thoiGianDang DATETIME,
    moTa TEXT,
    maBV INT,
    maTV INT,
    FOREIGN KEY (maBV) REFERENCES BaiViet(maBV),
    FOREIGN KEY (maTV) REFERENCES ThanhVien(maTV)
);

CREATE TABLE TinNhan (
    maTN INT AUTO_INCREMENT PRIMARY KEY,
    noiDung TEXT,
    ngayGui DATETIME,
    trangThai ENUM('da_xem', 'chua_xem'),
    maTVGui INT,
    maTVNhan INT,
    FOREIGN KEY (maTVGui) REFERENCES ThanhVien(maTV),
    FOREIGN KEY (maTVNhan) REFERENCES ThanhVien(maTV)
);

CREATE TABLE BaoCao (
    maBC INT AUTO_INCREMENT PRIMARY KEY,
    loaiViPham ENUM('baiviet', 'binhluan', 'thanhvien'),
    moTa TEXT,
    trangThai ENUM('da_duyet', 'cho_duyet'),
    thoiGianXL DATETIME,
    maTV INT,
    maNV INT,
    FOREIGN KEY (maTV) REFERENCES ThanhVien(maTV),
    FOREIGN KEY (maNV) REFERENCES NhanVien(maNV)
);

### 💡 Ghi chú: Cách tạo CSDL MySQL trong xampp
- B1: Tạo CSDL với tên là: findu_db
- B2: Vào database findu_db, chọn SQL
- B3: Copy code SQL trên và chạy Go

# LƯU Ý: Quy trình code chung
## Tạo nhánh mới từ develop:

git checkout develop
git pull origin develop
git checkout -b feature/<ten-chuc-nang>


## Code & commit:

git add .
git commit -m "Mô tả ngắn gọn thay đổi"


## Push lên GitHub:

git push origin feature/<ten-chuc-nang>
