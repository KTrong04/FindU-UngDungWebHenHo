<?php
// app/views/user/search_results.php
// Có sẵn: $pageTitle, $keyword, $results, $ageMin, $ageMax, $gender, $location, $hobbies, hàm e()
include_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title><?= e($pageTitle) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS riêng cho trang search (đã cô lập để tránh trùng CSS trangChu) -->
  <link rel="stylesheet" href="/project-FindU/public/assets/css/search_results.css">
</head>

<body>
  <div class="main">
    <!-- SIDEBAR giống trangChu -->
    <div class="main-left">
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>
    </div>

    <!-- MAIN RIGHT giống trangChu -->
    <div class="main-right">
      <?php include_once __DIR__ . '/../includes/header.php'; ?>

      <div class="content" id="content">
        <!-- SCOPE RIÊNG: toàn bộ UI search nằm trong .search-page -->
        <div class="search-page">

          <h1 class="search-title"><?= e($pageTitle) ?></h1>

          <!-- Bộ lọc nâng cao -->
          <div class="filter-section">
            <div class="filter-title">Bộ Lọc Nâng Cao</div>

            <form method="get" action="/project-FindU/app/controllers/search_by_name.php" id="filterForm">
              <div class="filter-grid">
                <!-- Tên / Email / Mô tả -->
                <div class="filter-group">
                  <label for="filterName">Tên / Mô tả/ Bio/ Học vấn/ Vị trí/</label>
                  <input type="text" id="filterName" name="hoTen" placeholder="Tìm kiếm..." value="<?= e($keyword) ?>">
                </div>

                <!-- Tuổi Min -->
                <div class="filter-group">
                  <label for="filterAgeMin">Tuổi từ</label>
                  <input type="number" id="filterAgeMin" name="tuoiMin" min="18" max="100"
                    value="<?= $ageMin !== null ? e((string)$ageMin) : '' ?>" placeholder="18">
                </div>

                <!-- Tuổi Max -->
                <div class="filter-group">
                  <label for="filterAgeMax">đến</label>
                  <input type="number" id="filterAgeMax" name="tuoiMax" min="18" max="100"
                    value="<?= $ageMax !== null ? e((string)$ageMax) : '' ?>" placeholder="60">
                </div>

                <!-- Giới tính -->
                <div class="filter-group">
                  <label for="filterGender">Giới tính</label>
                  <select id="filterGender" name="gioiTinh">
                    <option value="">-- Tất cả --</option>
                    <option value="M" <?= $gender === 'M' ? 'selected' : '' ?>>Nam</option>
                    <option value="F" <?= $gender === 'F' ? 'selected' : '' ?>>Nữ</option>
                    <option value="O" <?= $gender === 'O' ? 'selected' : '' ?>>Khác</option>
                  </select>
                </div>

                <!-- Vị trí -->
                <div class="filter-group">
                  <label for="filterLocation">Vị trí</label>
                  <select id="filterLocation" name="viTri">
                    <option value="">-- Tất cả --</option>
                    <option value="Hà Nội" <?= $location === 'Hà Nội' ? 'selected' : '' ?>>Hà Nội</option>
                    <option value="Hồ Chí Minh" <?= $location === 'Hồ Chí Minh' ? 'selected' : '' ?>>Hồ Chí Minh</option>
                    <option value="Đà Nẵng" <?= $location === 'Đà Nẵng' ? 'selected' : '' ?>>Đà Nẵng</option>
                    <option value="Hải Phòng" <?= $location === 'Hải Phòng' ? 'selected' : '' ?>>Hải Phòng</option>
                    <option value="Cần Thơ" <?= $location === 'Cần Thơ' ? 'selected' : '' ?>>Cần Thơ</option>
                    <option value="An Giang" <?= $location === 'An Giang' ? 'selected' : '' ?>>An Giang</option>
                    <option value="Bà Rịa - Vũng Tàu" <?= $location === 'Bà Rịa - Vũng Tàu' ? 'selected' : '' ?>>Bà Rịa - Vũng Tàu</option>
                    <option value="Bắc Giang" <?= $location === 'Bắc Giang' ? 'selected' : '' ?>>Bắc Giang</option>
                    <option value="Bắc Kạn" <?= $location === 'Bắc Kạn' ? 'selected' : '' ?>>Bắc Kạn</option>
                    <option value="Bạc Liêu" <?= $location === 'Bạc Liêu' ? 'selected' : '' ?>>Bạc Liêu</option>
                    <option value="Bắc Ninh" <?= $location === 'Bắc Ninh' ? 'selected' : '' ?>>Bắc Ninh</option>
                    <option value="Bến Tre" <?= $location === 'Bến Tre' ? 'selected' : '' ?>>Bến Tre</option>
                    <option value="Bình Định" <?= $location === 'Bình Định' ? 'selected' : '' ?>>Bình Định</option>
                    <option value="Bình Dương" <?= $location === 'Bình Dương' ? 'selected' : '' ?>>Bình Dương</option>
                    <option value="Bình Phước" <?= $location === 'Bình Phước' ? 'selected' : '' ?>>Bình Phước</option>
                    <option value="Bình Thuận" <?= $location === 'Bình Thuận' ? 'selected' : '' ?>>Bình Thuận</option>
                    <option value="Cà Mau" <?= $location === 'Cà Mau' ? 'selected' : '' ?>>Cà Mau</option>
                    <option value="Cao Bằng" <?= $location === 'Cao Bằng' ? 'selected' : '' ?>>Cao Bằng</option>
                    <option value="Đắk Lắk" <?= $location === 'Đắk Lắk' ? 'selected' : '' ?>>Đắk Lắk</option>
                    <option value="Đắk Nông" <?= $location === 'Đắk Nông' ? 'selected' : '' ?>>Đắk Nông</option>
                    <option value="Điện Biên" <?= $location === 'Điện Biên' ? 'selected' : '' ?>>Điện Biên</option>
                    <option value="Đồng Nai" <?= $location === 'Đồng Nai' ? 'selected' : '' ?>>Đồng Nai</option>
                    <option value="Đồng Tháp" <?= $location === 'Đồng Tháp' ? 'selected' : '' ?>>Đồng Tháp</option>
                    <option value="Gia Lai" <?= $location === 'Gia Lai' ? 'selected' : '' ?>>Gia Lai</option>
                    <option value="Hà Giang" <?= $location === 'Hà Giang' ? 'selected' : '' ?>>Hà Giang</option>
                    <option value="Hà Nam" <?= $location === 'Hà Nam' ? 'selected' : '' ?>>Hà Nam</option>
                    <option value="Hà Tĩnh" <?= $location === 'Hà Tĩnh' ? 'selected' : '' ?>>Hà Tĩnh</option>
                    <option value="Hải Dương" <?= $location === 'Hải Dương' ? 'selected' : '' ?>>Hải Dương</option>
                    <option value="Hậu Giang" <?= $location === 'Hậu Giang' ? 'selected' : '' ?>>Hậu Giang</option>
                    <option value="Hòa Bình" <?= $location === 'Hòa Bình' ? 'selected' : '' ?>>Hòa Bình</option>
                    <option value="Hưng Yên" <?= $location === 'Hưng Yên' ? 'selected' : '' ?>>Hưng Yên</option>
                    <option value="Khánh Hòa" <?= $location === 'Khánh Hòa' ? 'selected' : '' ?>>Khánh Hòa</option>
                    <option value="Kiên Giang" <?= $location === 'Kiên Giang' ? 'selected' : '' ?>>Kiên Giang</option>
                    <option value="Kon Tum" <?= $location === 'Kon Tum' ? 'selected' : '' ?>>Kon Tum</option>
                    <option value="Lai Châu" <?= $location === 'Lai Châu' ? 'selected' : '' ?>>Lai Châu</option>
                    <option value="Lâm Đồng" <?= $location === 'Lâm Đồng' ? 'selected' : '' ?>>Lâm Đồng</option>
                    <option value="Lạng Sơn" <?= $location === 'Lạng Sơn' ? 'selected' : '' ?>>Lạng Sơn</option>
                    <option value="Lào Cai" <?= $location === 'Lào Cai' ? 'selected' : '' ?>>Lào Cai</option>
                    <option value="Long An" <?= $location === 'Long An' ? 'selected' : '' ?>>Long An</option>
                    <option value="Nam Định" <?= $location === 'Nam Định' ? 'selected' : '' ?>>Nam Định</option>
                    <option value="Nghệ An" <?= $location === 'Nghệ An' ? 'selected' : '' ?>>Nghệ An</option>
                    <option value="Ninh Bình" <?= $location === 'Ninh Bình' ? 'selected' : '' ?>>Ninh Bình</option>
                    <option value="Ninh Thuận" <?= $location === 'Ninh Thuận' ? 'selected' : '' ?>>Ninh Thuận</option>
                    <option value="Phú Thọ" <?= $location === 'Phú Thọ' ? 'selected' : '' ?>>Phú Thọ</option>
                    <option value="Phú Yên" <?= $location === 'Phú Yên' ? 'selected' : '' ?>>Phú Yên</option>
                    <option value="Quảng Bình" <?= $location === 'Quảng Bình' ? 'selected' : '' ?>>Quảng Bình</option>
                    <option value="Quảng Nam" <?= $location === 'Quảng Nam' ? 'selected' : '' ?>>Quảng Nam</option>
                    <option value="Quảng Ngãi" <?= $location === 'Quảng Ngãi' ? 'selected' : '' ?>>Quảng Ngãi</option>
                    <option value="Quảng Ninh" <?= $location === 'Quảng Ninh' ? 'selected' : '' ?>>Quảng Ninh</option>
                    <option value="Quảng Trị" <?= $location === 'Quảng Trị' ? 'selected' : '' ?>>Quảng Trị</option>
                    <option value="Sóc Trăng" <?= $location === 'Sóc Trăng' ? 'selected' : '' ?>>Sóc Trăng</option>
                    <option value="Sơn La" <?= $location === 'Sơn La' ? 'selected' : '' ?>>Sơn La</option>
                    <option value="Tây Ninh" <?= $location === 'Tây Ninh' ? 'selected' : '' ?>>Tây Ninh</option>
                    <option value="Thái Bình" <?= $location === 'Thái Bình' ? 'selected' : '' ?>>Thái Bình</option>
                    <option value="Thái Nguyên" <?= $location === 'Thái Nguyên' ? 'selected' : '' ?>>Thái Nguyên</option>
                    <option value="Thanh Hóa" <?= $location === 'Thanh Hóa' ? 'selected' : '' ?>>Thanh Hóa</option>
                    <option value="Thừa Thiên Huế" <?= $location === 'Thừa Thiên Huế' ? 'selected' : '' ?>>Thừa Thiên Huế</option>
                    <option value="Tiền Giang" <?= $location === 'Tiền Giang' ? 'selected' : '' ?>>Tiền Giang</option>
                    <option value="Trà Vinh" <?= $location === 'Trà Vinh' ? 'selected' : '' ?>>Trà Vinh</option>
                    <option value="Tuyên Quang" <?= $location === 'Tuyên Quang' ? 'selected' : '' ?>>Tuyên Quang</option>
                    <option value="Vĩnh Long" <?= $location === 'Vĩnh Long' ? 'selected' : '' ?>>Vĩnh Long</option>
                    <option value="Vĩnh Phúc" <?= $location === 'Vĩnh Phúc' ? 'selected' : '' ?>>Vĩnh Phúc</option>
                    <option value="Yên Bái" <?= $location === 'Yên Bái' ? 'selected' : '' ?>>Yên Bái</option>

                  </select>
                </div>
              </div>

              <!-- Sở thích -->
              <div class="filter-group filter-hobbies">
                <label>Sở thích</label>
                <div class="checkbox-group">
                  <?php
                  $allHobbies = [
                    'Du lịch',
                    'Cà phê',
                    'Đọc sách',
                    'Âm nhạc',
                    'Thể thao',
                    'Nấu ăn',
                    'Anime',
                    'Xem phim',
                    'Game',
                    'Nghệ thuật',
                    'Điện ảnh',
                    'Chụp ảnh',
                    'Thiền',
                    'Yoga',
                    'Thể hình',
                    'Lập trình',
                    'Cắm trại',
                    'Leo núi',
                    'Đạp xe',
                    'Lướt web',
                    'Hội họa',
                    'Thiết kế',
                    'Sưu tầm',
                    'Nuôi thú cưng',
                    'Làm vườn',
                    'Karaoke',
                    'Nhảy',
                    'Chơi nhạc cụ',
                    'Bơi lội',
                    'Chạy bộ',
                    'Viết blog',
                    'DIY',
                    'Board game',
                    'Cờ vua',
                    'Câu cá',
                    'Lướt sóng',
                    'Trượt patin'
                  ];
                  foreach ($allHobbies as $hobby):
                  ?>
                    <div class="checkbox-item">
                      <input type="checkbox"
                        id="hobby_<?= str_replace(' ', '_', $hobby) ?>"
                        name="soThich[]"
                        value="<?= e($hobby) ?>"
                        <?= in_array($hobby, $hobbies) ? 'checked' : '' ?>>
                      <label for="hobby_<?= str_replace(' ', '_', $hobby) ?>"><?= e($hobby) ?></label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="filter-actions">
                <button type="submit" class="btn-filter btn-apply">🔎 Lọc kết quả</button>
                <button type="reset" class="btn-filter btn-clear">↺ Xóa bộ lọc</button>
              </div>
            </form>
          </div>

          <!-- Kết quả tìm kiếm -->
          <?php if ($keyword === '' && empty($results)): ?>
            <p class="result-header">Nhập từ khóa để tìm kiếm hoặc điều chỉnh bộ lọc trên.</p>

          <?php elseif (empty($results)): ?>
            <p class="result-header">❌ Không tìm thấy kết quả khớp với tiêu chí của bạn.</p>

          <?php else: ?>
            <div class="result-header">✅ Tìm thấy <?= count($results) ?> kết quả</div>

            <div class="result-container">
              <?php foreach ($results as $row): ?>
                <?php
                $avatarFile = !empty($row['anhDaiDien']) ? $row['anhDaiDien'] : 'avatar-default.svg';
                // normalize path casing to match project links
                $avatarPath = "/project-FindU/public/uploads/avatars/" . $avatarFile;
                $defaultAvatar = "/project-FindU/public/uploads/avatars/avatar-default.svg";
                $profileHref = "/project-FindU/app/views/user/hoSo.php?id_profile=" . e($row['id']);
                ?>

                <!-- ĐỔI class để không trùng .card của trangChu -->
                <div class="result-card">
                  <a class="result-avatar-link" href="<?= $profileHref ?>" title="Xem hồ sơ <?= e($row['hoTen']) ?>">
                    <img class="result-avatar"
                      src="<?= htmlspecialchars($avatarPath, ENT_QUOTES, 'UTF-8'); ?>"
                      alt="Avatar của <?= e($row['hoTen']) ?>"
                      onerror="this.onerror=null;this.src='<?= htmlspecialchars($defaultAvatar, ENT_QUOTES, 'UTF-8'); ?>';" />
                  </a>

                  <div class="result-info">
                    <h3 class="result-name"><a class="result-name-link" href="<?= $profileHref ?>"><?= e($row['hoTen']) ?></a></h3>

                    <p class="result-line">
                      <strong>Tuổi:</strong> <?= e($row['tuoi'] ?? 'N/A') ?>
                      <span class="dot">•</span>
                      <strong>Giới tính:</strong>
                      <?= e($row['gioiTinh'] === 'M' ? 'Nam' : ($row['gioiTinh'] === 'F' ? 'Nữ' : ($row['gioiTinh'] ?? 'N/A'))) ?>
                    </p>

                    <p class="result-line"><strong>Địa chỉ:</strong> <?= e($row['diaChi'] ?? 'N/A') ?></p>
                    <p class="result-line"><strong>Email:</strong> <?= e($row['email'] ?? 'N/A') ?></p>

                    <?php if (!empty($row['bio'])): ?>
                      <p class="result-line"><strong>Bio:</strong> <?= e(strlen($row['bio']) > 80 ? substr($row['bio'], 0, 80) . '...' : $row['bio']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($row['soThich'])): ?>
                      <p class="result-line"><strong>Sở thích:</strong> <?= e(strlen($row['soThich']) > 80 ? substr($row['soThich'], 0, 80) . '...' : $row['soThich']) ?></p>
                    <?php endif; ?>


                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- Nút quay lại (không cố định) -->
          <div class="back-center-wrap">
            <button type="button" class="btn-back-love" onclick="history.back()">
              <span class="icon">←</span>
              <span>Quay lại</span>
            </button>
          </div>

        </div><!-- /search-page -->
      </div><!-- /content -->
    </div><!-- /main-right -->
  </div><!-- /main -->

  <?php include_once __DIR__ . '/../includes/js.php'; ?>
</body>

</html>