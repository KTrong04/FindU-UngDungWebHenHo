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

                <?php
                // 1. Khởi tạo biến mã thành viên cần tìm
                $maTV_can_tim = null;

                // TRƯỜNG HỢP 1: Người dùng nhập tay và bấm nút Tìm kiếm
                if (isset($_POST['btn_searchTV'])) {
                    $maTV_can_tim = $_POST['txt_searchTV'];
                }
                // TRƯỜNG HỢP 2: Được chuyển hướng từ trang Báo cáo (qua URL)
                elseif (isset($_GET['maTV_xl'])) {
                    $maTV_can_tim = $_GET['maTV_xl'];
                }

                // 2. Nếu có mã thành viên thì thực hiện tìm kiếm
                if (!empty($maTV_can_tim)) {
                    // Gọi hàm tìm kiếm từ Model
                    $userData = $nv->searchThanhVien($maTV_can_tim);
    
                    if (isset($userData) && !empty($userData)) {
                        // QUAN TRỌNG: Lưu vào Session để dùng cho form xử lý khóa bên dưới
                        $_SESSION['maTV_Khoa'] = $userData['maTV'];

                        // Hiển thị bảng thông tin
                        echo '<div class="alert-msg alert-success">Đã tìm thấy thông tin thành viên cần xử lý.</div>';
                        echo '<table class="box-search-tv">';
                        echo '<thead><tr>
                                <th>Mã TV</th>
                                <th>Họ tên</th>
                                <th>Giới tính</th>
                                <th>Tuổi</th>
                                <th>Trạng thái hiện tại</th>
                            </tr></thead>';
                                        echo '<tbody><tr>
                                <td>' . $userData['maTV'] . '</td>
                                <td>' . $userData['hoTen'] . '</td>
                                <td>' . $userData['gioiTinh'] . '</td>
                                <td>' . $userData['tuoi'] . '</td>
                                <td><span style="font-weight:bold; color:' . ($userData['trangThai'] == 'Bị khóa' ? 'red' : 'green') . '">' . $userData['trangThai'] . '</span></td>
                            </tr></tbody>';
                        echo '</table>';
                    } else {
                        echo '<p class="alert-msg alert-error">Không tìm thấy thành viên có mã: ' . $maTV_can_tim . '</p>';
                    }
                }
                ?>
            </div>

            <?php if (isset($_SESSION['maTV_Khoa']) || isset($userData)): ?>
                <div class="card-box box-form-khoa">
                    <h3 class="card-title">Cấu hình khóa tài khoản</h3>

                    <form action="" method="post">

                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="rd_khoa" id="rd_khoaVV" value="Khóa vĩnh viễn" onchange="toggleDateInputs(false)">
                                <label for="rd_khoaVV">🔒 Khóa vĩnh viễn</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="rd_khoa" id="rd_khoaTH" value="Khóa có thời hạn" onchange="toggleDateInputs(true)">
                                <label for="rd_khoaTH">⏳ Khóa có thời hạn</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="txt_mota">Lý do khóa / Mô tả</label>
                            <input type="text" name="txt_mota" id="txt_mota" class="form-control" placeholder="Ví dụ: Vi phạm chính sách cộng đồng..." required>
                        </div>

                        <div class="form-group row-date" id="dateSection">
                            <div>
                                <label for="txt_ngayKhoa">Ngày bắt đầu</label>
                                <input type="date" name="txt_ngayKhoa" id="txt_ngayKhoa" class="form-control">
                            </div>
                            <div>
                                <label for="txt_ngayMoKhoa">Ngày mở khóa</label>
                                <input type="date" name="txt_ngayMoKhoa" id="txt_ngayMoKhoa" class="form-control">
                            </div>
                        </div>

                        <div style="overflow: hidden; margin-top: 20px;">
                            <button type="submit" name="sb_apdungkhoa" class="btn-submit">Xác nhận khóa</button>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['sb_apdungkhoa'])) {
                        if (isset($_POST['rd_khoa'])) {
                            $loaiKhoa = $_POST['rd_khoa'];
                            $moTa = $_POST['txt_mota'];

                            // Xử lý logic validate
                            $flag = false;

                            if ($loaiKhoa == "Khóa vĩnh viễn" && !empty($moTa)) {
                                $ngayKhoa = date('Y-m-d'); // Mặc định ngày hiện tại
                                $ngayMoKhoa = '9999-12-31'; // Mặc định xa tít tắp
                                $flag = true;
                            } elseif ($loaiKhoa == "Khóa có thời hạn" && !empty($moTa) && !empty($_POST['txt_ngayKhoa']) && !empty($_POST['txt_ngayMoKhoa'])) {
                                $ngayKhoa = $_POST['txt_ngayKhoa'];
                                $ngayMoKhoa = $_POST['txt_ngayMoKhoa'];
                                $flag = true;
                            }

                            if ($flag && isset($_SESSION['maTV_Khoa'])) {
                                // Gọi hàm trong Model
                                $nv->khoaThanhVien($_SESSION['maTV_Khoa'], $moTa, $ngayKhoa, $ngayMoKhoa, $loaiKhoa);
                                echo '<div class="alert-msg alert-success">Đã khóa thành công thành viên ' . $_SESSION['maTV_Khoa'] . '!</div>';

                                // cập nhật trạng thái của tất cả yêu cầu báo cáo thành da_duyet
                                if (!empty($maTV_can_tim) && isset($_GET['maBC_xl']))
                                {
                                    $nv->ignore_baoCao($_GET['maBC_xl']);
                                }
                                
                            } else {
                                echo '<div class="alert-msg alert-error">Vui lòng nhập đầy đủ thông tin (Ngày bắt đầu & kết thúc nếu khóa có thời hạn)!</div>';
                            }
                        } else {
                            echo '<div class="alert-msg alert-error">Vui lòng chọn loại khóa!</div>';
                        }
                    }
                    ?>
                <?php endif; ?>
                <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
                </div>
        </div>
    </div>

    <script>
        function toggleDateInputs(show) {
            const dateSection = document.getElementById('dateSection');
            const inputs = dateSection.querySelectorAll('input');

            if (show) {
                dateSection.style.display = 'grid'; // Hiện dạng lưới 2 cột
                inputs.forEach(input => input.required = true); // Bắt buộc nhập ngày
            } else {
                dateSection.style.display = 'none'; // Ẩn đi
                inputs.forEach(input => input.required = false); // Không bắt buộc nhập
                inputs.forEach(input => input.value = ''); // Reset giá trị
            }
        }
    </script>
</body>

</html>