<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/ghepDoi.css">
</head>

<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>
    <div class="container">
        <form action="" method="post">
            <div class="box-sothich">
                <label for="">Sở thích</label><br>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Du lịch"> Du lịch</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Cà phê"> Cà phê</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Đọc sách"> Đọc sách</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Âm nhạc"> Âm nhạc</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Thể thao"> Thể thao</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Nấu ăn"> Nấu ăn</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Anime"> Anime</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Xem phim"> Xem phim</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Game"> Game</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Nghệ thuật"> Nghệ thuật</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Điện ảnh"> Điện ảnh</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Chụp ảnh"> Chụp ảnh</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Thiền"> Thiền</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Yoga"> Yoga</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Thể hình"> Thể hình</label>
                <label class="info-label-sothich"><input type="checkbox" name="soThich[]" value="Coffee"> Coffee</label>
            </div>
            <p>
                <label for="age">Chọn giới hạn độ tuổi:</label>
                <div class="range-container">
                    <input type="range" id="minAge" min="18" max="60" value="25">
                    <input type="range" id="maxAge" min="18" max="60" value="45">
                </div>

                <div class="output">
                    Khoảng tuổi: <span id="ageRange">25 - 45</span>
                </div>

                <!-- Thêm 2 input ẩn để gửi dữ liệu về PHP -->
                <input type="hidden" name="age_min" id="age_min" value="25">
                <input type="hidden" name="age_max" id="age_max" value="45">

                <script>
                    const minAge = document.getElementById('minAge');
                    const maxAge = document.getElementById('maxAge');
                    const ageRange = document.getElementById('ageRange');
                    const ageMinInput = document.getElementById('age_min');
                    const ageMaxInput = document.getElementById('age_max');

                    function updateSlider() {
                        let min = parseInt(minAge.value);
                        let max = parseInt(maxAge.value);

                        if (min > max - 1) {
                            minAge.value = max - 1;
                            min = max - 1;
                        }
                        if (max < min + 1) {
                            maxAge.value = min + 1;
                            max = min + 1;
                        }

                        // Hiển thị khoảng tuổi
                        ageRange.textContent = `${min} - ${max}`;

                        // Cập nhật giá trị để gửi về PHP
                        ageMinInput.value = min;
                        ageMaxInput.value = max;
                    }

                    minAge.addEventListener('input', updateSlider);
                    maxAge.addEventListener('input', updateSlider);

                    updateSlider();
                    // Khi kéo một đầu, đưa nó lên trên
                    [minAge, maxAge].forEach(slider => {
                        slider.addEventListener('input', e => {
                            if (e.target === minAge) {
                                minAge.style.zIndex = 3;
                                maxAge.style.zIndex = 2;
                            } else {
                                maxAge.style.zIndex = 3;
                                minAge.style.zIndex = 2;
                            }
                        });
                    });
                </script>
            </p>
            <p>
                <label for="">Chọn giới tính</label>
                <label for="Nam">Nam</label>
                <input type="radio" id="Nam" name="sex" value="M">
                <label for="Nu">Nữ</label>
                <input type="radio" id="Nu" name="sex" value="F">
            </p>
            <p>
                <button type="submit" name="btn_ghepDoi" value="ghepdoi">Ghép đôi</button>
            </p>
        </form>
        <?php
        if (isset($_POST['btn_ghepDoi'])) {
            $soThich = $_POST['soThich'] ?? [];
            $ageMin = $_POST['age_min'] ?? '';
            $ageMax = $_POST['age_max'] ?? '';
            $list_soThich = implode(', ', $soThich);
            $gioiTinh = $_POST['sex'] ?? '';
            // echo $list_soThich;
            $tv->ghepDoi($soThich, $ageMin, $ageMax, $gioiTinh);
        }

        ?>
    </div>
</body>

</html>