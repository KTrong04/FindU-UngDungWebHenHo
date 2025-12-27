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
            <div class="box-search-nv card-box">
                <h3 class="card-title">Tìm kiếm nhân viên</h3>
                <div class="box-search">
                    <form method="post">
                        <input type="text" name="txt_searchNV" placeholder="Nhập mã nhân viên cần tìm..." autocomplete="off" />

                        <button type="submit" name="btn_searchNV" value="Tìm kiếm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                            </svg>
                            Tìm kiếm
                        </button>
                    </form>
                </div>
                <table class="table-info-nv box-search-tv">
                    <tr>
                        <th>Mã NV</th>
                        <th>Họ tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Chức vụ</th>
                        <th>Phòng ban</th>
                    </tr>
                    <?php
                    if (isset($_POST['btn_searchNV'])) {
                        $maNV = $_POST['txt_searchNV'];
                        if ($maNV != "") {
                            $nv = $nvql->search_nhanVienQL($maNV);
                            if ($nv) {
                                echo '<tr>';
                                echo  '<td>' . $nv['maNV'] . '</td>';
                                echo  '<td>' . $nv['hoTen'] . '</td>';
                                echo  '<td>' . $nv['ngaySinh'] . '</td>';
                                echo  '<td>' . $nv['gioiTinh'] . '</td>';
                                echo  '<td>' . $nv['soDienThoai'] . '</td>';
                                echo  '<td>' . $nv['email'] . '</td>';
                                echo  '<td>' . $nv['diaChi'] . '</td>';
                                echo  '<td>' . $nv['chucVu'] . '</td>';
                                echo  '<td>' . $nv['tenPB'] . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<div class="alert-msg alert-error">Vui lòng nhập đầy đủ thông tin!</div>';
                        }
                    }
                    ?>
                </table>


                <?php
                if (isset($_GET['update_sidebar']) || isset($_GET['managerment_sidebar'])) {
                    echo    '<form action="capNhatThongTinNV.php" method="get" style="overflow: hidden; margin-top: 20px;">
                                <button type="submit" name="btn_updateNV" value="updateNV" class="btn-submit">Cập nhật thông tin</button>
                            </form>';
                }

                if (isset($_SESSION['maNV_search']) && $_SESSION['maNV_search'] != $_SESSION['admin_maNV']) {
                    if (isset($_GET['delete_sidebar']) || isset($_GET['managerment_sidebar'])) {
                        echo    '<form action="" method="post" style="overflow: hidden; margin-top: 20px;">
                                <button type="submit" name="btn_deleteNV" value="deleteNV" class="btn-submit">Xóa nhân viên</button>
                            </form>';
                    }
                }

                ?>

            </div>
            <?php
            if (isset($_POST['btn_deleteNV'])) {
                $nvql->xoaNhanVien($_SESSION['maNV_search']);
            }
            ?>
            <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
        </div>
    </div>
</body>

</html>