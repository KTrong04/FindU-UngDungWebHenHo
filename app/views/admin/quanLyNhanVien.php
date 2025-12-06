<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include_once __DIR__ . '/../admin/includes/header.php'; $nvql->run_confignChucVu();?>
    <div class="container">
        <?php include_once __DIR__ . '/../admin/includes/sidebar.php'; ?>
        <div class="content">
            <div class="box-search-nv">
                <form action="" method="post">
                    <label for="txt_searchNV">Tìm kiếm nhân viên</label>
                    <input type="text" name="txt_searchNV" placeholder="Nhập mã nhân viên">
                    <button type="submit" name="btn_searchNV" value="Tìm kiếm">Tiềm kiếm</button>
                </form>
                <table class="table-info-nv">
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
                            echo 'Vui lòng nhập đầy đủ thông tin!';
                        }
                    }
                    ?>
                </table>


                <?php
                if (isset($_GET['update_sidebar']) || isset($_GET['managerment_sidebar'])) {
                    echo    '<form action="capNhatThongTinNV.php" method="get">
                                <button type="submit" name="btn_updateNV" value="updateNV">Cập nhật thông tin</button>
                            </form>';
                }

                if (isset($_GET['delete_sidebar']) || isset($_GET['managerment_sidebar'])) {
                    echo    '<form action="" method="post">
                                <button type="submit" name="btn_deleteNV" value="deleteNV">Xóa nhân viên</button>
                            </form>';
                }
                ?>

            </div>
            <?php
            if (isset($_POST['btn_deleteNV'])) {
                $nvql->xoaNhanVien($_SESSION['maNV_search']);
            }
            ?>
        </div>
    </div>
    <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
</body>

</html>