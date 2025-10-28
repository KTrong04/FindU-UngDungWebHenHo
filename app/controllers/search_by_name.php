<?php
require_once __DIR__ . '/../config/dataBaseConnect.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Inputs
$name       = $_GET['hoTen']      ?? $_POST['hoTen']      ?? null;
$gender     = $_GET['gioiTinh']   ?? $_POST['gioiTinh']   ?? null;
$location   = $_GET['viTri']      ?? $_POST['viTri']      ?? null;

// Tuổi: min/max + cờ bật lọc
$ageMinRaw  = $_GET['tuoiMin']    ?? $_POST['tuoiMin']    ?? null;
$ageMaxRaw  = $_GET['tuoiMax']    ?? $_POST['tuoiMax']    ?? null;
$applyAge   = ($_GET['applyAge']  ?? $_POST['applyAge']   ?? '0') === '1';

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
  : $repo->searchPractical($name, $ageMin, $ageMax, ($gender ?: null), $hobbies, ($location ?: null));

// Render view
$pageTitle = "Kết quả tìm kiếm";
$keyword   = $name ?? '';
require __DIR__ . '/../views/user/search_results.php';
