<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include_once __DIR__ . '/../admin/includes/config.php'; ?>
    <div class="container">
        <?php include_once __DIR__ . '/../admin/includes/sidebar.php'; ?>
        <div class="content">
            <?php include_once __DIR__ . '/../admin/includes/header.php'; ?>
            <div class="card-box">
                <h3 class="card-title">Tìm kiếm thành viên</h3>
                <?php include_once __DIR__ . '/../admin/includes/search_thanhVien.php'; ?>
            </div>
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
            <div class="box-managerment-tv">
                <form action="" method="post">
                    <?php
                    if (isset($_GET['all'])) {
                        echo '<button type="button" class="btn_khoaTV">Khóa thành viên</button>';
                    }
                    ?>
                    <div style="overflow: hidden; margin: 20px 0px;">
                        <button type="submit" class="btn_moKhoaTV btn-submit" value="Mở khóa" name="btn_moKhoaTV">Mở khóa thành viên</button>
                    </div>
                </form>

                <?php
                if (isset($_POST['btn_moKhoaTV']) == "Mở khóa") {
                    if (isset($_SESSION['maTV_Khoa'])) {
                        $nv->moKhoaThanhVien($_SESSION['maTV_Khoa']);
                        unset($_SESSION['maTV_Khoa']);
                    } else {
                        echo '<div class="alert-msg alert-error">Vui lòng tìm tài khoản thành viên cần mở khóa!</div>';
                    }
                }
                ?>

            </div>
            <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
        </div>
    </div>
</body>

</html>