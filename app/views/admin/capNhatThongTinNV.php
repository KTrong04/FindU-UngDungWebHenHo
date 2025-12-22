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
            <?php include_once __DIR__ . '/../admin/includes/header.php';
            $nvql->run_confignChucVu(); ?>
        </div>
        <?php
        if (isset($_GET['btn_updateNV'])) {
            $nvql->infoNV_update();
        }

        if (isset($_POST['btn_update_info_nv']) && isset($_SESSION['maNV_search'])) {
            $hoTen = $_POST['txt_hoTen'];
            $ngaySinh = $_POST['txt_ngaySinh'];
            $gioiTinh = isset($_POST['rd_gioiTinh']) ? $_POST['rd_gioiTinh'] : "";
            $sdt = $_POST['txt_sdt'];
            $email = $_POST['txt_email'];
            $diaChi = $_POST['txt_diaChi'];
            $chucVu = $_POST['se_chucVu'];
            $phongBan = $_POST['se_phongban'];
            $nvql->capNhatThongTinNV($_SESSION['maNV_search'], $hoTen, $ngaySinh, $gioiTinh, $sdt, $email, $diaChi, $chucVu, $phongBan);
        }
        ?>
    </div>
    <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
</body>

</html>