<?php
require_once __DIR__ . '/../../../controllers/nhanVienController.php';
$nv = new nhanVienController();
?>

<div class="box-search">
    <form method="post">
        <input type="text" name="txt_searchTV" placeholder="Tìm kiếm thành viên(mã thành viên)..." />
        <button type="submit" name="btn_searchTV" value="Tìm kiếm">Tìm kiếm</button>
    </form>
</div>