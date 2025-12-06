<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<head>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/baiViet.css">
</head>
<section class="box-post-baiViet">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="box-block">
            <div class="box-info-acount">
                <div class="box-info-avatar">
                    <img src="/project-FindU/public/uploads/avatars/<?php echo htmlspecialchars($_SESSION['user_avatar']); ?>"
                        alt="Profile avatar for social media post">
                </div>
                <div class="box-info-name">
                    <h3 class="bv-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
                </div>
            </div>
        </div>
        <div class="bv-noidung">
            <textarea type="text" name="txt_noiDungBV" placeholder="Bạn đang nghĩ gì?" class="textarea-post-baiViet"></textarea>
        </div>
        <nav class="nav-post-baiViet">
            <input type="text" name="txt_hashTag" placeholder="Nhập thẻ hashtag" class="input-hashtag-post-baiViet">
            <div class="nav-post-bv-box">
                <div class="box-left">
                    <label for="sel_quyenXem">Chọn quyền xem</label>&nbsp;
                    <select name="sel_quyenXem" id="sel_quyenXem">
                        <option value="cong_khai" default>Công khai</option>
                        <option value="ban_be">Bạn bè</option>
                        <option value="rieng_tu">Riêng tư</option>
                    </select>
                </div>
                <div class="box-right">
                    <input type="file" name="file[]" multiple class="input-file-post-baiViet">
                </div>
            </div>
            <button type="submit" class="btn-post-baiViet" name="btn_dangBaiViet">Đăng</button>
        </nav>
    </form>
</section>
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