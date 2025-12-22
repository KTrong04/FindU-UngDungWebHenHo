<?php
// session_start();
include_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../../repositories/thanhVienRepository.php';

// 1. Khởi tạo Repository và lấy dữ liệu
$repository = new thanhVienRepository();

// Kiểm tra session để tránh lỗi khi chưa đăng nhập
$maTV_hien_tai = $_SESSION['user_maTV'] ?? 0;
$blockedUsers = $repository->findAll_Blocked($maTV_hien_tai);

// 2) ĐƯỜNG DẪN ẢNH (theo cây thư mục bạn gửi: public/uploads/avatars/)
$projectRoot = "/Project-FindU"; // đúng tên thư mục trong htdocs
$avatarBase  = $projectRoot . "/public/uploads/avatars/";
$defaultAvatar = $avatarBase . "avatar-default.svg";

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Danh sách đã chặn</title>

  <!-- Nếu trangChu.php có link CSS chung (assets/css/...) thì bạn copy y nguyên vào đây.
       Trường hợp trangChu đã include CSS trong header.php/config.php thì không cần thêm. -->

  <style>
    .blocked-wrap {
      max-width: 980px;
      margin: 24px auto;
      padding: 0 16px;
      font-family: sans-serif;
    }
    .blocked-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 24px;
    }
    .blocked-title {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
      color: #111827;
    }
    .blocked-sub {
      margin: 6px 0 0;
      color: #6b7280;
      font-size: 14px;
    }
    .blocked-count {
      font-size: 13px;
      color: #374151;
      background: #f3f4f6;
      padding: 8px 14px;
      border-radius: 999px;
      font-weight: 600;
      height: fit-content;
      white-space: nowrap;
    }
    .blocked-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .blocked-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px;
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      background: #fff;
      transition: box-shadow 0.2s;
    }
    .blocked-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .blocked-left {
      display: flex;
      align-items: center;
      gap: 16px;
      min-width: 0;
    }
    .blocked-avatar {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      object-fit: cover;
      border: 1px solid #f3f4f6;
      background: #f9fafb;
      flex: 0 0 auto;
    }
    .blocked-meta { min-width: 0; }
    .blocked-name {
      margin: 0;
      font-weight: 700;
      font-size: 16px;
      color: #111827;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      max-width: 520px;
    }
    .blocked-note {
      margin: 2px 0 0;
      font-size: 13px;
      color: #ef4444;
      font-weight: 500;
    }
    .btn-unblock {
      padding: 8px 16px;
      border-radius: 10px;
      border: 1px solid #e5e7eb;
      color: #374151;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s;
      background: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-unblock:hover {
      background: #f9fafb;
      border-color: #d1d5db;
      color: #ef4444;
    }
    .empty-state {
      padding: 48px;
      border: 2px dashed #e5e7eb;
      border-radius: 20px;
      text-align: center;
      color: #9ca3af;
      background: #fff;
    }

    @media (max-width: 640px) {
      .blocked-header { flex-direction: column; }
      .blocked-card { flex-direction: column; align-items: flex-start; gap: 12px; }
      .blocked-name { max-width: 260px; }
    }

    .back-center-wrap{
      display:flex;
      justify-content:center;
      margin: 28px 0 8px;
    }

    .btn-back-love{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding: 12px 28px;
      border-radius: 999px;
      border: none;
      cursor: pointer;

      /* MÀU SẮC PHÙ HỢP WEB HẸN HÒ */
      background: linear-gradient(135deg, #f43f5e, #ec4899);
      color: #ffffff;

      font-size: 15px;
      font-weight: 700;
      letter-spacing: .2px;

      box-shadow: 0 10px 24px rgba(236,72,153,.25);
      transition: all .25s ease;
    }

    .btn-back-love:hover{
      transform: translateY(-2px);
      box-shadow: 0 14px 32px rgba(236,72,153,.35);
    }

    .btn-back-love:active{
      transform: translateY(0);
      box-shadow: 0 6px 16px rgba(236,72,153,.25);
    }

    .btn-back-love .icon{
      font-size: 18px;
      line-height: 1;
    }

  </style>
</head>

<body>
  <!-- LAYOUT GIỐNG trangChu.php -->
  <div class="main">
    <div class="main-left">
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>
    </div>

    <div class="main-right">
      <?php include_once __DIR__ . '/../includes/header.php'; ?>

      <div class="content" id="content">
        <!-- NỘI DUNG TRANG DANH SÁCH CHẶN (GIỮ NGUYÊN LOGIC) -->
        <div class="blocked-wrap">
          <div class="blocked-header">
            <div>
              <h2 class="blocked-title">Danh sách đã chặn</h2>
              <p class="blocked-sub">Những người này sẽ không thấy bạn và ngược lại.</p>
            </div>
            <div class="blocked-count">
              <?= is_array($blockedUsers) ? count($blockedUsers) : 0; ?> người
            </div>
          </div>

          <?php if (!empty($blockedUsers) && is_array($blockedUsers)): ?>
            <div class="blocked-list">
              <?php foreach ($blockedUsers as $user): ?>
                <?php
                  $maTV = (int)($user['maTV'] ?? 0);
                  $hoTen = htmlspecialchars($user['hoTen'] ?? 'Không rõ', ENT_QUOTES, 'UTF-8');
                  $avatarFile = !empty($user['anhDaiDien']) ? $user['anhDaiDien'] : 'avatar-default.svg';
                  $avatarPath = $avatarBase . $avatarFile;
                ?>
                <div class="blocked-card">
                  <div class="blocked-left">
                    <img class="blocked-avatar"
                      src="<?= htmlspecialchars($avatarPath, ENT_QUOTES, 'UTF-8'); ?>"
                      alt="Avatar"
                      onerror="this.onerror=null;this.src='<?= htmlspecialchars($defaultAvatar, ENT_QUOTES, 'UTF-8'); ?>';" />

                    <div class="blocked-meta">
                      <p class="blocked-name"><?= $hoTen; ?></p>
                      <p class="blocked-note">Đã chặn</p>
                    </div>
                  </div>

                  <div class="blocked-actions">
                    <a href="trangChu.php?action=unblock&id_bo_chan=<?= $maTV; ?>"
                      class="btn-unblock"
                      onclick="return confirm('Bạn có muốn bỏ chặn <?= $hoTen; ?> không?')">
                      Bỏ chặn
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <p>Danh sách chặn hiện đang trống.</p>
            </div>
          <?php endif; ?>
            <div class="back-center-wrap">
            <button type="button" class="btn-back-love" onclick="history.back()">
              <span class="icon">←</span>
              <span>Quay lại</span>
            </button>
          </div>
        </div>
        
        <!-- /blocked-wrap -->
      </div>
      <!-- /content -->
    </div>
    <!-- /main-right -->
  </div>
  <!-- /main -->

  <!-- JS GIỐNG trangChu.php -->
  <?php include_once __DIR__ . '/../includes/js.php'; ?>

  <!-- Nếu trangChu.php có thêm 2 file js này và bạn muốn GIỮ Y CHANG thì bỏ comment -->
  <!--
  <script src="/project-FindU/public/assets/js/ghepDoi.js"></script>
  <script src="/project-FindU/public/assets/js/postBaiViet.js"></script>
  -->
</body>
</html>
