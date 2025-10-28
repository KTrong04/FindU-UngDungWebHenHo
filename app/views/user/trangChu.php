<?php
require_once __DIR__ . '/../../controllers/thanhVienController.php';
$tv = new thanhVienController();
// if ($tv->configLogin() === false) {
//     header("Location: /project-FindU/app/views/user/");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Trang Chủ</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/style.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/searchForm.css">
</head>

<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>
    <div class="content">
        <?php include_once __DIR__ . '/../includes/dangBaiViet.php'; ?>
        <div class="bai-viet-list">
            <?php $tv->hienThiBaiViet(); ?>
        </div>
    </div>

     <!-- Modal Lọc -->
  <div id="filter-modal" class="filter-modal-overlay" aria-hidden="true">
    <div class="filter-modal-content" role="dialog" aria-modal="true" aria-labelledby="filter-title">
      <button type="button" class="modal-close" id="btn-close-filter" aria-label="Đóng bộ lọc">×</button>

      <form action="/project-FindU/app/controllers/search_by_name.php" method="get" class="form-filter" id="filterForm">
        <h2 id="filter-title">Bộ Lọc Tìm Kiếm</h2>

        <!-- Họ tên -->
        <div class="filter-group">
          <label for="fullName">Họ và tên</label>
          <input type="text" id="fullName" name="hoTen" class="input-text"  placeholder="Nguyễn Văn A" /> 
        </div>

        <!-- Tuổi -->
        <div class="filter-group">
          <label for="age-slider">Tuổi</label>
          <div class="age-control">
            <input type="range" id="age-slider" name="tuoi" min="18" max="60" value="25" />
            <output id="age-value" for="age-slider">25</output>
          </div>
        </div>

        <!-- Giới tính -->
        <div class="filter-group">
          <label>Giới Tính</label>
          <div class="radio-options">
            <label class="radio-pill"><input type="radio" name="gioiTinh" value="M" /> Nam</label>
            <label class="radio-pill"><input type="radio" name="gioiTinh" value="F" checked /> Nữ</label>
            <label class="radio-pill"><input type="radio" name="gioiTinh" value="O" /> Khác</label>
          </div>
        </div>

        <!-- Sở thích -->
        <div class="filter-group">
            <label for="hobbyField">Sở thích</label>
            <div class="multiselect" id="hobbyWrap">
                <button type="button" class="ms-field" id="hobbyField"
                    aria-haspopup="listbox" aria-expanded="false">
                    Chọn sở thích...
                </button>

                <div class="ms-panel" id="hobbyPanel" role="listbox" tabindex="-1">
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Du lịch"> Du lịch</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Cà phê"> Cà phê</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Đọc sách"> Đọc sách</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Âm nhạc"> Âm nhạc</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Thể thao"> Thể thao</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Nấu ăn"> Nấu ăn</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Anime"> Anime</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Xem phim"> Xem phim</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Game"> Game</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Nghệ thuật"> Nghệ thuật</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Điện ảnh"> Điện ảnh</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Chụp ảnh"> Chụp ảnh</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Thiền"> Thiền</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Yoga"> Yoga</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Thể hình"> Thể hình</label>
                    <label class="ms-option"><input type="checkbox" name="soThich[]" value="Coffee"> Coffee</label>
                  </div>
              </div>
          </div>




        <!-- Vị trí -->
        <div class="filter-group">
                <label for="locationSelect">Vị trí</label>
                <select id="locationSelect" name="viTri" class="input-select">
                    <option value="" disabled selected>Chọn Tỉnh/Thành phố</option>
                    <option value="Hà Nội">Hà Nội</option>
                    <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                    <option value="Đà Nẵng">Đà Nẵng</option>
                    <option value="Hải Phòng">Hải Phòng</option>
                    <option value="Cần Thơ">Cần Thơ</option>
                    <option value="An Giang">An Giang</option>
                    <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                    <option value="Bắc Giang">Bắc Giang</option>
                    <option value="Bắc Kạn">Bắc Kạn</option>
                    <option value="Bạc Liêu">Bạc Liêu</option>
                    <option value="Bắc Ninh">Bắc Ninh</option>
                    <option value="Bến Tre">Bến Tre</option>
                    <option value="Bình Định">Bình Định</option>
                    <option value="Bình Dương">Bình Dương</option>
                    <option value="Bình Phước">Bình Phước</option>
                    <option value="Bình Thuận">Bình Thuận</option>
                    <option value="Cà Mau">Cà Mau</option>
                    <option value="Cao Bằng">Cao Bằng</option>
                    <option value="Đắk Lắk">Đắk Lắk</option>
                    <option value="Đắk Nông">Đắk Nông</option>
                    <option value="Điện Biên">Điện Biên</option>
                    <option value="Đồng Nai">Đồng Nai</option>
                    <option value="Đồng Tháp">Đồng Tháp</option>
                    <option value="Gia Lai">Gia Lai</option>
                    <option value="Hà Giang">Hà Giang</option>
                    <option value="Hà Nam">Hà Nam</option>
                    <option value="Hà Tĩnh">Hà Tĩnh</option>
                    <option value="Hải Dương">Hải Dương</option>
                    <option value="Hậu Giang">Hậu Giang</option>
                    <option value="Hòa Bình">Hòa Bình</option>
                    <option value="Hưng Yên">Hưng Yên</option>
                    <option value="Khánh Hòa">Khánh Hòa</option>
                    <option value="Kiên Giang">Kiên Giang</option>
                    <option value="Kon Tum">Kon Tum</option>
                    <option value="Lai Châu">Lai Châu</option>
                    <option value="Lâm Đồng">Lâm Đồng</option>
                    <option value="Lạng Sơn">Lạng Sơn</option>
                    <option value="Lào Cai">Lào Cai</option>
                    <option value="Long An">Long An</option>
                    <option value="Nam Định">Nam Định</option>
                    <option value="Nghệ An">Nghệ An</option>
                    <option value="Ninh Bình">Ninh Bình</option>
                    <option value="Ninh Thuận">Ninh Thuận</option>
                    <option value="Phú Thọ">Phú Thọ</option>
                    <option value="Phú Yên">Phú Yên</option>
                    <option value="Quảng Bình">Quảng Bình</option>
                    <option value="Quảng Nam">Quảng Nam</option>
                    <option value="Quảng Ngãi">Quảng Ngãi</option>
                    <option value="Quảng Ninh">Quảng Ninh</option>
                    <option value="Quảng Trị">Quảng Trị</option>
                    <option value="Sóc Trăng">Sóc Trăng</option>
                    <option value="Sơn La">Sơn La</option>
                    <option value="Tây Ninh">Tây Ninh</option>
                    <option value="Thái Bình">Thái Bình</option>
                    <option value="Thái Nguyên">Thái Nguyên</option>
                    <option value="Thanh Hóa">Thanh Hóa</option>
                    <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                    <option value="Tiền Giang">Tiền Giang</option>
                    <option value="Trà Vinh">Trà Vinh</option>
                    <option value="Tuyên Quang">Tuyên Quang</option>
                    <option value="Vĩnh Long">Vĩnh Long</option>
                    <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                    <option value="Yên Bái">Yên Bái</option>
                </select>
          </div>
        <button type="submit" class="btn-submit-filter">🔎 TÌM KIẾM</button>
      </form>
    </div>
  </div>
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>

<script src="/project-FindU/public/assets/js/postBaiViet.js"></script>
<script src="/project-FindU/public/assets/js/searchForm.js"></script>