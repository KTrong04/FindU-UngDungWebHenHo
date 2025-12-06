<?php
$Path = $_SERVER['DOCUMENT_ROOT'] . '/Project-FindU/app/controllers/';
include($Path . 'thanhVienController.php');

$tv = new thanhVienController();
if ($tv->configLogin() === false) {
    header("Location: /project-FindU/app/views/user/");
    exit();
}

?>


<head>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/layout.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/style.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/searchForm.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/thongBao.css">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/nhanTin.css">

</head>