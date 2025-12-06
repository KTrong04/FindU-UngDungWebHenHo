<?php
include_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Cặp đôi</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/capDoi.css">
</head>

<body>
    <div class="main">
        <div class="main-left">
            <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>
        </div>
        <div class="main-right">
            <?php include_once __DIR__ . '/../includes/header.php'; ?>
            <div class="content" id="content">
                <?php
                if (isset($_POST['btn-nope-tuonghop'])) {
                    $tv->gui_ghepDoi($_SESSION['user_maTV'], $_POST['btn-nope-tuonghop'], 'nope');
                }

                if (isset($_POST['btn-superlike-tuonghop'])) {
                    $tv->gui_ghepDoi($_SESSION['user_maTV'], $_POST['btn-superlike-tuonghop'], 'superlike');
                }

                if (isset($_POST['btn-like-tuonghop'])) {
                    $tv->gui_ghepDoi($_SESSION['user_maTV'], $_POST['btn-like-tuonghop'], 'like');
                }

                if (empty($_SESSION['user_soThich']) || empty($_SESSION['user_diaChi'])) {
                    include('../includes/thietLap_info.php');
                } else {
                    if (isset($_POST['btn_huyGhepDoi'])) {
                        $tv->huyGhepDoi($_SESSION['user_maTV'], $_POST['btn_huyGhepDoi']);
                    }
                    echo '<div class="list-capDoi">';
                    if (isset($_GET['list_LoiMoi'])) {
                        if ($_GET['list_LoiMoi'] == 'like') {
                            echo '<header class="title-list-capDoi">Danh sách người thích bạn</header>
                                    <div class="list-capDoi-box">';
                            $tv->list_LoiMoiById($_SESSION['user_maTV'], 'like');
                        } else if ($_GET['list_LoiMoi'] == 'superLike') {
                            echo '<header class="title-list-capDoi">Danh sách người rất thích bạn</header>
                                    <div class="list-capDoi-box">';
                            $tv->list_LoiMoiById($_SESSION['user_maTV'], 'superlike');
                        }
                    } else {
                        echo '<header class="title-list-capDoi">Danh sách cặp đôi</header>
                                <div class="list-capDoi-box">';
                        $tv->list_capDoi($_SESSION['user_maTV']);
                    }

                    echo        '</div>
                        </div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>

<script src="/project-FindU/public/assets/js/capDoi.js"></script>
<script src="/project-FindU/public/assets/js/ghepDoi.js"></script>

<?php include_once __DIR__ . '/../includes/js.php'; ?>