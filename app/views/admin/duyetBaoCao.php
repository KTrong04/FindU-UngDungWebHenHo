<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt báo cáo</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_duyetBaoCao.css">
</head>

<body>
    <?php include_once __DIR__ . '/../admin/includes/config.php'; ?>

    <?php
    if (isset($_POST['btnProcessReport'])) {
        if ($_POST['btnProcessReport'] == 'ignore') {
            $nv->ignore_baoCao($_POST['maBC_xuly']);
            echo '<script>alert("Báo cáo đã được bỏ qua."); window.location.href = "/project-FindU/app/views/admin/duyetBaoCao.php";</script>';
        }
    }
    ?>

    <div class="container">
        <?php include_once __DIR__ . '/../admin/includes/sidebar.php'; ?>

        <div class="content">
            <?php include_once __DIR__ . '/../admin/includes/header.php'; ?>

            <div class="stats-container">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?php echo $nv->sum_list_baoCao('cho_duyet'); ?></div>
                        <div class="stat-label">Chờ xử lý</div>
                    </div>
                    <div style="color: #ffa502; font-size: 24px;">⚠️</div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?php echo $nv->sum_list_baoCao('da_duyet'); ?></div>
                        <div class="stat-label">Đã giải quyết</div>
                    </div>
                    <div style="color: #2ed573; font-size: 24px;">✅</div>
                </div>
            </div>

            <div class="card-box">
                <h3 style="margin-bottom: 20px; color: #333;">Danh sách yêu cầu báo cáo</h3>

                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người báo cáo</th>
                            <th>Người bị báo cáo</th>
                            <th>Lý do</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query lấy dữ liệu (JOIN bảng thanhvien để lấy tên)
                        // Giả sử $conn là kết nối DB của bạn
                        // SELECT b.*, t1.hoTen as tenNguoiBao, t2.hoTen as tenBiBao 
                        // FROM baocao b
                        // JOIN thanhvien t1 ON b.maTV = t1.maTV
                        // JOIN thanhvien t2 ON b.maTV_bi_bao_cao = t2.maTV
                        // ORDER BY b.thoiGianBaoCao DESC


                        $reports = $nv->list_baoCao();

                        foreach ($reports as $row) {
                            $statusBadge = $row['status'] == 'cho_duyet'
                                ? '<span class="badge badge-pending">Chờ xử lý</span>'
                                : '<span class="badge badge-done">Đã duyệt</span>';

                            // Map lý do sang tiếng Việt đẹp hơn
                            $reasons = ['quayroi' => 'Quấy rối', 'giamao' => 'Giả mạo', 'nhaycam' => 'Nhạy cảm', 'spam' => 'Spam'];
                            $reasonText = isset($reasons[$row['reason']]) ? $reasons[$row['reason']] : $row['reason'];

                            echo "<tr>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td><div class='user-info'><div class='avatar-circle'>" . substr($row['reporter'], 0, 1) . "</div> {$row['reporter']}</div></td>";
                            echo "<td><div class='user-info'><div class='avatar-circle' style='background:#ffeaa7'>" . substr($row['accused'], 0, 1) . "</div> {$row['accused']}</div></td>";
                            echo "<td>{$reasonText}</td>";
                            echo "<td>{$row['time']}</td>";
                            echo "<td>{$statusBadge}</td>";
                            echo "<td>";
                            if ($row['status'] == 'cho_duyet') {
                                // Truyền dữ liệu vào nút để JS bắt lấy
                                echo "<button class='btn-action-view' onclick='openAdminProcessModal(" . json_encode($row) . ")'>Xử lý</button>";
                            } else {
                                echo "<span style='color:#aaa;'>Hoàn tất</span>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="adminProcessModal" class="modal-overlay" style="display:none;">
                <div class="admin-modal-content">
                    <div class="admin-modal-header">
                        <h3 style="margin:0;">Xử lý vi phạm</h3>
                        <span onclick="closeAdminProcessModal()" style="cursor:pointer; font-size: 24px;">&times;</span>
                    </div>
                    <form method="POST" action="">
                        <div class="admin-modal-body">
                            <input type="hidden" name="maBC_xuly" id="modal_maBC">

                            <div class="info-row">
                                <span class="info-label">Nội dung tố cáo:</span>
                                <div class="info-value" id="modal_desc">...</div>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Quyết định của Nhân viên:</span>
                                <div class="action-group">
                                    <a id="btnLinkBan" href="#" class="btn-process btn-ban">
                                        🚫 Khóa tài khoản
                                    </a>
                                    <button type="submit" name="btnProcessReport" value="ignore" class="btn-process btn-ignore" onclick="document.getElementById('action_type').value='ignore'">
                                        👁️ Bỏ qua
                                    </button>
                                </div>
                                <input type="hidden" name="action_type" id="action_type" value="">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
        </div>
    </div>

    <script>
        function openAdminProcessModal(data) {
            // 1. Hiển thị Modal
            document.getElementById('adminProcessModal').style.display = 'flex';

            // 2. Điền thông tin cũ (giữ nguyên)
            document.getElementById('modal_maBC').value = data.id;
            document.getElementById('modal_desc').innerText =
                "Người báo cáo: " + data.reporter + "\n" +
                "Đối tượng: " + data.accused + "\n" +
                "Lý do: " + data.reason + "\n" +
                "Chi tiết: " + data.desc;

            // 3. --- XỬ LÝ LINK KHÓA TÀI KHOẢN ---
            // Lấy thẻ a thông qua ID vừa đặt
            var linkBan = document.getElementById('btnLinkBan');

            // Gán href mới chứa ID người bị báo cáo (data.accused_id lấy từ SQL ở Bước 1)
            linkBan.href = "/project-FindU/app/views/admin/khoaTaiKhoanTV.php?maTV_xl=" + data.accused_id + "&maBC_xl=" + data.id;
        }

        function closeAdminProcessModal() {
            document.getElementById('adminProcessModal').style.display = 'none';
        }

        // Đóng khi click ra ngoài
        window.onclick = function(event) {
            var modal = document.getElementById('adminProcessModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>