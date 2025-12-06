<?php
// app/views/user/search_results.php
// Có sẵn: $pageTitle, $keyword, $results, $ageMin, $ageMax, $gender, $location, $hobbies, hàm e()
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= e($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project-FindU/public/assets/css/search_results.css">
</head>
<body>
    
    <h1><?= e($pageTitle) ?></h1>

    <form method="get" action="/project-FindU/app/controllers/search_by_name.php">
        <input type="text" name="hoTen" placeholder="Nhập họ tên..." value="<?= e($keyword) ?>">
        
        <?php if ($ageMin !== null): ?>
            <input type="hidden" name="tuoiMin" value="<?= e($ageMin) ?>">
        <?php endif; ?>
        <?php if ($ageMax !== null): ?>
            <input type="hidden" name="tuoiMax" value="<?= e($ageMax) ?>">
        <?php endif; ?>
        <?php if ($gender !== null): ?>
            <input type="hidden" name="gioiTinh" value="<?= e($gender) ?>">
        <?php endif; ?>
        <?php if ($location !== null): ?>
            <input type="hidden" name="viTri" value="<?= e($location) ?>">
        <?php endif; ?>
        
        <?php 
        // Sở thích là mảng, cần lặp qua để gửi từng phần tử
        foreach ($hobbies as $h): 
            if (trim($h) !== ''):
        ?>
            <input type="hidden" name="soThich[]" value="<?= e($h) ?>">
        <?php 
            endif; 
        endforeach; 
        ?>
        <button type="submit">Tìm kiếm</button>
    </form>

    <?php if ($keyword === '' && empty($results)): ?>
        <p class="no-result">Nhập họ tên để tìm kiếm hoặc sử dụng Bộ lọc nâng cao.</p>
    <?php elseif (empty($results)): ?>
        <p class="no-result">Không tìm thấy kết quả.</p>
    <?php else: ?>
        <div class="result-container">
            <?php foreach ($results as $row): ?>
            <div class="card">
                <div class="avatar">👤</div>
                <div class="info">
                    <h3><?= e($row['hoTen']) ?></h3>
                    <p><strong>Giới tính:</strong> <?= e($row['gioiTinh'] ?? 'N/A') ?> | <strong>Tuổi:</strong> <?= e($row['tuoi'] ?? 'N/A') ?></p>
                    <p><strong>Địa chỉ:</strong> <?= e($row['diaChi'] ?? 'N/A') ?></p>
                    <p><strong>Sở thích:</strong> <?= e($row['soThich'] ?? 'N/A') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="back-link">
        <a href="/project-FindU/app/views/user/trangChu.php">← Quay lại Trang Chủ</a>
    </div>
</body>
</html>