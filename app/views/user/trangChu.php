<?php
require_once __DIR__ . '/../../controllers/thanhVienController.php';
$tv = new thanhVienController();
if ($tv->configLogin() === false) {
    header("Location: /project-FindU/app/views/user/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Trang Chủ</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/style.css">
</head>

<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>
    <div class="content">
        <section class="box-post-baiViet">
            <p>
                <textarea type="text" name="txt_noiDungBV" placeholder="Bạn đang nghĩ gì?" class="textarea-post-baiViet"></textarea>
            </p>

            <nav class="nav-post-baiViet">
                <form action="" method="post">
                    <p><input type="text" name="txt_hashTag" placeholder="Nhập thẻ hashtag" class="input-hashtag-post-baiViet"></p>
                    <p>
                        <label for="sel_quyenXem">Chọn quyền xem</label>
                        <select name="sel_quyenXem" id="sel_quyenXem">
                            <option value="congKhai" default>Công khai</option>
                            <option value="banBe">Bạn bè</option>
                            <option value="riengTu">Riêng tư</option>
                        </select>
                    </p>
                    <p>
                        <input type="file" name="file[]" multiple class="input-file-post-baiViet">
                    </p>
                    <button type="submit" class="btn-post-baiViet" name="btn_dangBaiViet">Đăng</button>
                </form>
                <?php
                if (isset($_POST['btn_dangBaiViet'])) {
                    $maTV = $_SESSION['user_maTV'];
                    $noiDung = trim($_POST['txt_noiDungBV']);
                    $hashtag = trim($_POST['txt_hashTag']);
                    $quyenXem = $_POST['sel_quyenXem'];
                    $files = $_FILES['file'];

                    $tv->dangBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $files);
                }
                ?>
            </nav>
        </section>
    </div>
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>

<script src="/project-FindU/public/assets/js/postBaiViet.js"></script>