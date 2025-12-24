<?php
require_once __DIR__ . '/../config/dataBaseConnect.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

// Khởi tạo session nếu chưa active (không gọi nếu đã active để tránh notice)
if(empty(session_id())) {
    session_start();
}

$selfId = $_SESSION['user_maTV'] ?? null;
if ($selfId !== null) $selfId = (int)$selfId;


function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Inputs
$name       = $_GET['hoTen']      ?? $_POST['hoTen']      ?? null;
$gender     = $_GET['gioiTinh']   ?? $_POST['gioiTinh']   ?? null;
$location   = $_GET['viTri']      ?? $_POST['viTri']      ?? null;

// Tuổi: hỗ trợ nhiều kiểu tham số (tuoi, tuoiMin/tuoiMax) + cờ bật lọc
$ageMinRaw  = $_GET['tuoiMin']    ?? $_POST['tuoiMin']    ?? null;
$ageMaxRaw  = $_GET['tuoiMax']    ?? $_POST['tuoiMax']    ?? null;
// Một số form gửi `tuoi` (range) thay vì min/max -> dùng làm cả min và max
$ageSingle  = $_GET['tuoi']       ?? $_POST['tuoi']       ?? null;
$applyAge   = false;
// bật lọc tuổi nếu có cờ applyAge hoặc có bất kỳ param tuổi nào hợp lệ
if ((isset($_GET['applyAge']) && ($_GET['applyAge'] === '1')) || (isset($_POST['applyAge']) && ($_POST['applyAge'] === '1'))) {
    $applyAge = true;
} elseif ($ageMinRaw !== null || $ageMaxRaw !== null || $ageSingle !== null) {
    $applyAge = true;
}
// Nếu có `tuoi` đơn, map nó thành cả min và max nếu min/max chưa có
if ($ageSingle !== null && ($ageMinRaw === null && $ageMaxRaw === null)) {
    $ageMinRaw = $ageSingle;
    $ageMaxRaw = $ageSingle;
}

// Sở thích (array)
$hobbies    = $_GET['soThich']    ?? $_POST['soThich']    ?? [];
if (!is_array($hobbies)) $hobbies = [];

// Chuẩn hóa
$name = ($name !== null && trim($name) !== '') ? trim($name) : null;

$ageMin = ($applyAge && is_numeric($ageMinRaw)) ? max(0, (int)$ageMinRaw) : null;
$ageMax = ($applyAge && is_numeric($ageMaxRaw)) ? max(0, (int)$ageMaxRaw) : null;
if ($ageMin !== null && $ageMax !== null && $ageMin > $ageMax) {
    // hoán đổi nếu user nhập ngược
    [$ageMin, $ageMax] = [$ageMax, $ageMin];
}

$db = (new DataBaseConnect())->connect();
if ($db === null) { http_response_code(500); echo "Không thể kết nối cơ sở dữ liệu."; exit; }

$repo = new UserRepository($db);

// Không có tiêu chí nào -> không trả kết quả
$noFilters = ($name === null)
          && ($gender === null || $gender === '')
          && ($location === null || $location === '')
          && empty($hobbies)
          && ($ageMin === null && $ageMax === null);

$results = $noFilters
  ? []
  : $repo->searchFullText($name, $ageMin, $ageMax, ($gender ?: null), $hobbies, ($location ?: null), $selfId);

// Render view
$pageTitle = "Kết quả tìm kiếm";
$keyword   = $name ?? '';
require __DIR__ . '/../views/user/search_results.php';
