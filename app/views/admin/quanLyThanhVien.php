<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include_once __DIR__ . '/../admin/includes/header.php'; ?>
    <div class="container">
        <?php include_once __DIR__ . '/../admin/includes/sidebar.php'; ?>
        <div class="content">
            <?php include_once __DIR__ . '/../admin/includes/search_thanhVien.php'; ?>
            <?php
            if (isset($_POST['btn_searchTV'])) {
                $maTV = $_POST['txt_searchTV'];
                echo '<table class="box-search-tv">';
                echo '<th>Mã</th>' . '<th>Họ tên</th>' . '<th>Giới tính</th>' . '<th>Tuổi</th>' . '<th>Trạng thái</th>';
                $userData = $nv->searchThanhVien($maTV);
                if (isset($userData)) {
                    echo '<tr><td>' . $userData['maTV'] . '</td>' . '<td>' . $userData['hoTen'] . '</td>' . '<td>' . $userData['gioiTinh'] . '</td>' . '<td>' . $userData['tuoi'] . '</td>' . '<td>' . $userData['trangThai'] . '</td></tr>';
                }
                echo '</table>';
            }
            ?>
            <form action="" method="post">
                <button type="button" class="btn_khoaTV">Khóa thành viên</button>
                <button type="submit" class="btn_moKhoaTV" value="Mở khóa" name="btn_moKhoaTV">Mở khóa thành viên</button>


                <div class="box-form-khoa">
                    <label for="rd_khoaVV">Khóa vĩnh viễn</label>
                    <input type="radio" name="rd_khoa" id="rd_khoaVV" class="rd_khoaVV" value="Khóa vĩnh viễn">
                    <label for="rd_khoaTH">Khóa có thời hạn</label>
                    <input type="radio" name="rd_khoa" id="rd_khoaTH" class="rd_khoaTH" value="Khóa có thời hạn">

                    <p class="mota">
                        <label for="txt_mota">Mô tả</label>
                        <input type="text" name="txt_mota" id="txt_mota" placeholder="Nhập mô tả tài khoản">
                    </p>

                    <p class="timeKhoa">
                        <label for="txt_ngayKhoa">Ngày khóa</label>
                        <input type="date" name="txt_ngayKhoa" id="txt_ngayKhoa">
                        <label for="txt_ngayMoKhoa">Ngày mở khóa</label>
                        <input type="date" name="txt_ngayMoKhoa" id="txt_ngayMoKhoa">
                    </p>
                    <p>
                        <button type="submit" name="sb_apdungkhoa" class="sb_apdungkhoa" value="sb_apdungkhoa">khóa</button>
                    </p>
                </div>
            </form>

            <?php
            if (isset($_POST['sb_apdungkhoa'])) {
                if (isset($_POST['rd_khoa'])) {
                    session_start();
                    $loaiKhoa = $_POST['rd_khoa'];
                    $moTa = $_POST['txt_mota'];
                    $ngayKhoa = $_POST['txt_ngayKhoa'];
                    $ngayMoKhoa = $_POST['txt_ngayMoKhoa'];
                    if ($loaiKhoa == "Khóa vĩnh viễn" && $moTa != "" || $loaiKhoa == "Khóa có thời hạn" && $moTa != "" && $ngayKhoa != "" && $ngayMoKhoa != "") {
                        $nv->khoaThanhVien($_SESSION['maTV_Khoa'], $moTa, $ngayKhoa, $ngayMoKhoa, $loaiKhoa);
                        unset($_SESSION['maTV_Khoa']);
                    } else {
                        echo "Vui lòng nhập đầy đủ thông tin!";
                    }
                } else {
                    echo "Vui lòng chọn loại khóa!";
                }
            }

            if (isset($_POST['btn_moKhoaTV']) == "Mở khóa") {
                session_start();
                if (isset($_SESSION['maTV_Khoa'])) {
                    $nv->moKhoaThanhVien($_SESSION['maTV_Khoa']);
                    unset($_SESSION['maTV_Khoa']);
                } else {
                    echo 'Vui lòng tìm tài khoản thành viên cần mở khóa!';
                }
            }
            ?>

        </div>
    </div>
    <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
</body>

</html>