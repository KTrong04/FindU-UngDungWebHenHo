<?php include_once __DIR__ . '/../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="/project-FindU/public/assets/css/style.css">
  <link rel="stylesheet" href="/project-FindU/public/assets/css/hoSo.css">
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
        if (empty($_SESSION['user_soThich']) || empty($_SESSION['user_diaChi'])) {
          include('../includes/thietLap_info.php');
        } else {
          if (isset($_POST['btnUpdateInfo'])) {
            $anhDaiDien = $_FILES['uploadAvatarProfile'];
            $hinh = $_FILES['photos'] ?? [];
            $hoTen = $_POST['profile_hoTen'];
            $tuoi = $_POST['profile_tuoi'];
            $hocVan = $_POST['profile_hocVan'];
            $diaChi = $_POST['profile_diaChi'];
            $bio = $_POST['profile_bio'];
            $soThich = $_POST['profile_soThich'] ?? [];

            $tv->capNhatHoSo($_SESSION['user_maTV'], $anhDaiDien, $hinh, $hoTen, $hocVan, $diaChi, $bio, $soThich, $tuoi);
          }
          echo '<div class="stage stage-hoSo">
                    <div class="cards" id="cards">';
          $ma = !empty($_GET['id_profile']) ? $_GET['id_profile'] : $_SESSION['user_maTV'];
          $tv->profile($ma);
          echo '</div>';
          if (!isset($_GET['sidebar']) || $ma != $_SESSION['user_maTV']) {
            $ck_capDoi = $tv->check_capDoi($_SESSION['user_maTV'], $ma);
            if ($ck_capDoi == false) {
              echo <<<HTML
                    <div class="controls" style="width:100%;">
                      <button class="btn" id="noBtn" title="Nope">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" focusable="false" role="img">
                            <defs>
                              <linearGradient id="gradNope" x1="0%" y1="20%" x2="70%" y2="100%">
                                  <stop offset="0%" stop-color="#ff1bf8" />
                                  <stop offset="100%" stop-color="#ff2732" />
                              </linearGradient>
                            </defs>  
                            <title>Nope</title>
                              <g fill="url(#gradNope)">
                                  <path d="M21.974 4.171 19.97 2.17a.94.94 0 0 0-1.331 0L12 8.809l-6.64-6.64a.94.94 0 0 0-1.331 0L2.026 4.17a.94.94 0 0 0 0 1.332l6.64 6.64-6.64 6.63a.94.94 0 0 0 0 1.332l2.003 2.002a.94.94 0 0 0 1.331 0l6.64-6.64 6.64 6.64a.94.94 0 0 0 1.331 0l2.003-2.002a.94.94 0 0 0 0-1.332l-6.64-6.63 6.64-6.64a.94.94 0 0 0 0-1.332"></path>
                              </g>
                          </svg>
                      </button>

                      <button class="btn" id="starBtn" title="Super Like">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" focusable="false" role="img">
                          <defs>
                              <linearGradient id="gradSuperLike" x1="10%" y1="20%" x2="80%" y2="100%">
                                  <stop offset="0%" stop-color="#00daff" />
                                  <stop offset="100%" stop-color="#0077ff" />
                              </linearGradient>
                          </defs>
                          <title>Super Like</title>
                              <g fill="url(#gradSuperLike)">
                                  <path d="M16.296 8.04a.995.995 0 0 1-.89-.65 40.694 40.694 0 0 0-2.99-6.15.505.505 0 0 0-.86 0 40.73 40.73 0 0 0-3 6.16c-.14.37-.49.63-.89.65-3 .16-5.55.66-6.78.94-.37.08-.51.53-.26.81.83.95 2.59 2.86 4.93 4.75.31.25.44.66.34 1.05-.78 2.9-1.08 5.48-1.2 6.74-.03.37.35.65.69.5 1.16-.5 3.52-1.58 6.05-3.22a1 1 0 0 1 1.1 0c2.52 1.64 4.88 2.72 6.04 3.22.35.15.73-.13.69-.5-.11-1.26-.42-3.84-1.2-6.75-.1-.38.03-.8.34-1.05 2.34-1.89 4.1-3.8 4.93-4.75.25-.28.1-.73-.26-.81 0 0-3.77-.79-6.78-.94"></path>
                              </g>
                          </svg>
                      </button>

                      <button class="btn" id="likeBtn" title="Like">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon"viewBox="0 0 24 24" focusable="false" role="img">
                              <title>Like</title>
                              <defs>
                                  <linearGradient id="gradLike" x1="10%" y1="20%" x2="80%" y2="100%">
                                      <stop offset="0%" stop-color="#e9f208" />
                                      <stop offset="100%" stop-color="#35b951" />
                                  </linearGradient>
                              </defs>
                              <g fill="url(#gradLike)">
                                  <path d="M17.506 2c-.556 0-1.122.075-1.7.225-1.636.438-3.015 1.625-3.795 3.132-.78-1.518-2.16-2.705-3.796-3.132A6.757 6.757 0 0 0 6.515 2C3.062 2 .25 4.833.25 8.33c0 .138 0 .299.021.47.129 1.454.642 2.822 1.39 4.063 1.273 2.085 4.149 6.04 9.601 10.092.214.16.481.246.738.246s.524-.075.738-.246c5.452-4.052 8.328-8.007 9.6-10.092.76-1.24 1.273-2.62 1.39-4.063.011-.171.022-.332.022-.47 0-3.497-2.801-6.33-6.265-6.33z"></path>
                              </g>
                          </svg>
                      </button>
                    </div>
                  </div> 
                  HTML;
            }
          } else {
            echo '<div class="controls" style="width:100%; position:absolute; bottom:20px; display:flex; justify-content:center; z-index:10;">
                    <button class="btn" id="editBtn" onclick="openEditModal()" title="Chỉnh sửa Profile" style="background:#fff; padding:15px; border-radius:50%; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3273dc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                  </div>';
          }
        }
        ?>
      </div>
    </div>
  </div>
</body>

</html>

<script src="/project-FindU/public/assets/js/hoSo.js"></script>
<script src="/project-FindU/public/assets/js/ghepDoi.js"></script>

<?php include_once __DIR__ . '/../includes/js.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
