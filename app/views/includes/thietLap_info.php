<head>
    <link rel="stylesheet" href="/project-findU/public/assets/css/thietLap_info.css">
</head>

<div class="box-thiet-lap-info">
    <form class="box-info" enctype="multipart/form-data" method="post">
        <h2>Thêm thông tin</h2>
        <div class="info-sothich">
            <label for="">Sở thích</label><br>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Du lịch"> Du lịch</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Cà phê"> Cà phê</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Đọc sách"> Đọc sách</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Âm nhạc"> Âm nhạc</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Thể thao"> Thể thao</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Nấu ăn"> Nấu ăn</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Anime"> Anime</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Xem phim"> Xem phim</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Game"> Game</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Nghệ thuật"> Nghệ thuật</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Điện ảnh"> Điện ảnh</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Chụp ảnh"> Chụp ảnh</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Thiền"> Thiền</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Yoga"> Yoga</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Thể hình"> Thể hình</label>
            <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Coffee"> Coffee</label>
        </div>
        <div class="info-box">
            <div class="frm_thietLap_info">
                <label for="file_avatar">Thêm ảnh đại diện</label>
                <div class="box-avatar">
                    <img src="/project-FindU/public/uploads/avatars/avatar-default.svg"
                        alt="Profile avatar for social media post">
                </div>
                <div class="info-avatar">
                    <input type="file" name="file_avatar">
                </div>
                <div class="info-diachi">
                    <label for="txt_diachi">Thêm địa chỉ <input type="text" name="txt_diachi" id="" placeholder="Nhập địa chỉ"></label>
                </div>
                <button type="submit" name="btn_thietLap_info" value="Lưu">Lưu</button>
            </div>
        </div>
    </form>
    <?php
    if(isset($_POST['btn_thietLap_info']))
    {
        $soThich = $_POST['soThich'] ?? [];
        $list_soThich = implode(', ', $soThich);
        $avatar = $_FILES['file_avatar'];
        $diaChi = $_POST['txt_diachi'];

        $tv->ThemThongTinTV($_SESSION['user_maTV'], $list_soThich, $avatar, $diaChi);
    }
    ?>
</div>