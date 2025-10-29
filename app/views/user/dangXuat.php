<?php
// app/views/user/dangXuat.php
require_once '../../controllers/thanhVienController.php';

$controller = new thanhVienController();
$controller->dangXuat(); // gọi logout trong controller