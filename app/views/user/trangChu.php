<?php
include_once __DIR__ . '/../includes/config.php';
unset($_POST['maTV_item']);
?>

<?php
// Kiểm tra nếu có yêu cầu chặn từ link: ?action=block&id_bi_chan=...
if (isset($_GET['action']) && $_GET['action'] == 'block' && isset($_GET['id_bi_chan'])) {
    
    $maTV_dang_nhap = $_SESSION['user_maTV']; // ID của bạn
    $id_bi_chan = $_GET['id_bi_chan'];       // ID người bị chặn (ví dụ: Ánh Dương)

    // Khởi tạo Repository và gọi hàm blockUser bạn vừa viết
    $repository = new thanhVienRepository();
    $result = $repository->blockUser($maTV_dang_nhap, $id_bi_chan);

    if ($result) {
        // Chặn xong thì dùng Javascript để thông báo và chuyển hướng trang cho sạch đường link
        echo "<script>
                alert('Đã chặn thành công người dùng này.');
                window.location.href = 'trangChu.php'; 
              </script>";
        exit();
    } else {
        echo "<script>alert('Có lỗi xảy ra, vui lòng thử lại.');</script>";
    }
}

//Kiểm tra nếu có yêu cầu bỏ chặn từ link: ?action=unblock&id_bo_chan=...
if (isset($_GET['action']) && $_GET['action'] == 'unblock' && isset($_GET['id_bo_chan'])) {
    $maTV_dang_nhap = $_SESSION['user_maTV'];
    $id_bi_chan = $_GET['id_bo_chan'];

    $repository = new thanhVienRepository();
    if ($repository->unblockUser($maTV_dang_nhap, $id_bi_chan)) {
        echo "<script>alert('Đã bỏ chặn người dùng này.'); window.location.href='danhSachChan.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FindU - Trang Chủ</title>
</head>

<body>
  <div class="main">
    <div class="main-left">
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>
    </div>
    <div class="main-right">
      <?php include_once __DIR__ . '/../includes/header.php'; ?>
      <div class="content" id="content">
        <?php //include_once __DIR__ . '/../includes/dangBaiViet.php'; 
        ?>
        <!-- <div class="bai-viet-list">
        <?php //$tv->hienThiBaiViet(); 
        ?>
      </div> -->

        <?php
        if (empty($_SESSION['user_soThich']) || empty($_SESSION['user_diaChi'])) {
          include('../includes/thietLap_info.php');
        } else {
          echo '<div class="stage">
                    <div class="cards" id="cards">
                      <!-- Card template: put multiple cards stacked. data-img should be file path relative to this html -->';
          $tv->list_goiY_ghepDoi($_SESSION['user_maTV'], $_SESSION['user_gioiTinh'],  $_SESSION['user_tuoi'], $_SESSION['user_soThich']);
          echo '</div>';
          echo <<<HTML
                    <div class="controls" style="width:100%;">
                      <button class="btn" id="undoBtn" title="Hoàn tác">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" focusable="false" role="img">
                            <defs>
                              <linearGradient id="gradBack" x1="10%" y1="20%" x2="80%" y2="100%">
                                  <stop offset="0%" stop-color="#fef001" />
                                  <stop offset="100%" stop-color="#eb7100" />
                              </linearGradient>
                            </defs>    
                            <title>Undo</title>
                              <g fill="url(#gradBack)">
                                  <path fill-rule="evenodd" d="M19.229 4.83C15.587 1.17 9.583.804 5.499 3.972a9.92 9.92 0 0 0-1.153 1.052v-2.09c0-.785-.634-1.423-1.418-1.428h-.01A1.428 1.428 0 0 0 1.5 2.934v5.664c0 .788.64 1.427 1.428 1.427h5.654a1.428 1.428 0 0 0 0-2.855H6.27c.094-.11.192-.216.294-.319a7.474 7.474 0 0 1 5.316-2.197 7.65 7.65 0 0 1 5.318 2.199 7.455 7.455 0 0 1 2.207 5.316c0 2.335-1.057 4.505-2.947 5.975-2.543 1.969-6.175 2.069-8.818.246a7.543 7.543 0 0 1-2.637-3.148c-.304-.674-1.055-1.037-1.77-.784a1.431 1.431 0 0 0-.845 1.92v.001a10.352 10.352 0 0 0 2.144 3.12 10.315 10.315 0 0 0 7.339 3.041c2.77 0 5.379-1.082 7.338-3.042 4.046-4.045 4.056-10.62.02-14.667" clip-rule="evenodd"></path>
                              </g>
                          </svg>
                      </button>

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

                      <!-- <button class="btn" id="msgBtn" title="Message">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                      </button> -->
                    </div>
                  </div> 
                  HTML;
        }
        ?>
      </div>
    </div>
  </div>
</body>

</html>
<script src="/project-FindU/public/assets/js/ghepDoi.js"></script>
<script src="/project-FindU/public/assets/js/postBaiViet.js"></script>

<?php include_once __DIR__ . '/../includes/js.php'; ?>