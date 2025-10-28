<article class="bai-viet">
    <h2>
    <p class="ngay-dang">Ngày đăng: <?php echo htmlspecialchars($bv['thoiGianDang']); ?></p>
    <p class="noi-dung"><?php echo nl2br(htmlspecialchars($bv['noiDung'])); ?></p>
    <?php
    // Hiển thị hình ảnh nếu có
    if (!empty($bv['hinhAnh'])) {
        $images = explode(',', $bv['hinhAnh']);
        foreach ($images as $image) {
            echo '<img src="/project-FindU/public/uploads/images/' . htmlspecialchars($image) . '" alt="Hình ảnh bài viết" class="bai-viet-image">';
        }
    }
    // Hiển thị video nếu có
    if (!empty($bv['video'])) {
        $videos = explode(',', $bv['video']);
        foreach ($videos as $video) {
            echo '<video controls class="bai-viet-video">
                    <source src="/project-FindU/public/uploads/' . htmlspecialchars($video) . '" type="video/mp4">
                    Your browser does not support the video tag.
                  </video>';
        }
    }
    ?>
    <p class="the-tag">Thẻ tag: <?php echo htmlspecialchars($bv['theTag']); ?></p>
    <p class="quyen-xem">Quyền xem: <?php echo htmlspecialchars($bv['quyenXem']); ?></p>
</article>