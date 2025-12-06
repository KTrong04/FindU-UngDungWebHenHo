<head>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/baiViet.css">
</head>
<article class="bai-viet">
    <div class="bai-viet-header">
        <div class="bv-avatar">
            <img src="<?php echo '/project-FindU/public/uploads/avatars/' . htmlspecialchars($bv['anhDaiDien']); ?>" alt="Ảnh đại diện">
        </div>
        <div class="bv-info">
            <h3 class="bv-name"><?php echo htmlspecialchars($bv['hoTen']); ?></h3>
            <p class="bv-box-info-1">
            <div class="bv-time"><?php echo 'Ngày đăng: ' . date('d/m/Y H:i', strtotime($bv['thoiGianDang'])); ?></div>
            <span class="quyen-xem">
                <?php
                if ($bv['quyenXem'] == 'cong_khai') {
                    $qx = 'Công khai';
                } elseif ($bv['quyenXem'] == 'ban_be') {
                    $qx = 'Bạn bè';
                } else {
                    $qx = 'Riêng tư';
                }

                echo  'Quyền xem: ' . $qx;
                ?>
            </span>
            </p>
        </div>
    </div>

    <div class="bai-viet-content">
        <p><?php echo nl2br(htmlspecialchars($bv['noiDung'])); ?></p>
        <?php
        if (!empty($bv['theTag'])) {
            echo '<p class="the-tag">' . htmlspecialchars($bv['theTag']) . '</p>';
        } ?>

    </div>

    <div class="bai-viet-media">
        <?php
        // Xử lý hình ảnh
        $images = [];
        if (!empty($bv['hinhAnh'])) {
            $images = array_map('trim', explode(',', $bv['hinhAnh']));
        }

        // Xử lý video
        $videos = [];
        if (!empty($bv['video'])) {
            $videos = array_map('trim', explode(',', $bv['video']));
        }

        // Tổng media
        $totalMedia = count($images) + count($videos);
        ?>

        <?php if ($totalMedia > 0): ?>
            <div class="media-grid">
                <?php
                $displayCount = 3; // hiển thị tối đa 4 phần tử đầu
                $displayed = 0;

                // Duyệt ảnh
                foreach ($images as $index => $img):
                    if ($displayed >= $displayCount) break;
                    $displayed++;
                    $remaining = $totalMedia - $displayed;
                ?>
                    <div class="media-item">
                        <?php if ($displayed === $displayCount && $remaining > 0): ?>
                            <div class="overlay">+<?php echo $remaining; ?></div>
                        <?php endif; ?>
                        <img src="/project-FindU/public/uploads/images/<?php echo htmlspecialchars($img); ?>" alt="Ảnh bài viết" onclick="openMediaModal(<?php echo $bv['maBV']; ?>)">
                    </div>
                <?php endforeach; ?>

                <!-- Duyệt video nếu còn chỗ -->
                <?php foreach ($videos as $index => $video):
                    if ($displayed >= $displayCount) break;
                    $displayed++;
                    $remaining = $totalMedia - $displayed;
                ?>
                    <div class="media-item">
                        <?php if ($displayed === $displayCount && $remaining > 0): ?>
                            <div class="overlay">+<?php echo $remaining; ?></div>
                        <?php endif; ?>
                        <video controls onclick="openMediaModal(<?php echo $bv['maBV']; ?>)">
                            <source src="/project-FindU/public/uploads/videos/<?php echo htmlspecialchars($video); ?>" type="video/mp4">
                        </video>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal hiển thị tất cả ảnh/video -->
    <div id="mediaModal-<?php echo $bv['maBV']; ?>" class="media-modal">
        <span class="close" onclick="closeMediaModal(<?php echo $bv['maBV']; ?>)">&times;</span>
        <div class="modal-content">
            <?php foreach ($images as $img): ?>
                <img src="/project-FindU/public/uploads/images/<?php echo htmlspecialchars($img); ?>" alt="Ảnh chi tiết">
            <?php endforeach; ?>
            <?php foreach ($videos as $video): ?>
                <video controls>
                    <source src="/project-FindU/public/uploads/videos/<?php echo htmlspecialchars($video); ?>" type="video/mp4">
                </video>
            <?php endforeach; ?>
        </div>
    </div>


    <div class="bai-viet-footer">
        <form action="" method="post">
            <div class="bv-footer-icons"><button type="submit" class="bv-footer-btn"><img src="/project-FindU/public/assets/img/BV-heart.svg" alt="" class="bv-icons"></button></div>
            <div class="bv-footer-icons"><button type="submit" class="bv-footer-btn"><img src="/project-FindU/public/assets/img/BV-comment.svg" alt="" class="bv-icons"></button></div>
            <div class="bv-footer-icons"><button type="submit" class="bv-footer-btn"><img src="/project-FindU/public/assets/img/BV-share.svg" alt="" class="bv-icons"></button></div>
        </form>
    </div>
</article>

<script>
    function openMediaModal(id) {
        document.getElementById("mediaModal-" + id).style.display = "block";
    }

    function closeMediaModal(id) {
        document.getElementById("mediaModal-" + id).style.display = "none";
    }
</script>