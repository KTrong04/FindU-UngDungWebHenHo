<?php
$Path = $_SERVER['DOCUMENT_ROOT'] . '/Project-FindU/app/controllers/';
include($Path . 'nhanVienController.php');
include($Path . 'nhanVienQLController.php');
$nv = new nhanVienController();
$nvql = new nhanVienQLController();

if ($nv->configLogin() === false) {
    header("Location: /project-FindU/app/views/admin/dangNhap.php");
    exit();
}
?>

<head>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_layout.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/admin_style.css">
</head>
