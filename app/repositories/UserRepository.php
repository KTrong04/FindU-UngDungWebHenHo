<?php
class UserRepository
{
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    /**
     * Tìm kiếm Full-Text: tên, email, mô tả, bio
     * Hỗ trợ tìm từng từ riêng lẻ (fuzzy-like) và lọc theo các tiêu chí khác
     */
    public function searchFullText(
        ?string $keyword,
        ?int $ageMin,
        ?int $ageMax,
        ?string $gender,
        array $hobbies,
        ?string $location,
        ?int $excludeUserId = null
    ): array
    {
        $where   = [];
        $params  = [];
        $orderBy = [];

        // Loại chính người dùng hiện tại (nếu có)
        if ($excludeUserId !== null && $excludeUserId > 0) {
            $where[] = "maTV != :selfId";
            $params[':selfId'] = $excludeUserId;
        }

        // 1) Location (nếu có)
        if ($location !== null && trim($location) !== '') {
            $where[] = "diaChi = :loc";
            $params[':loc'] = trim($location);
        }

        // 2) Gender (nếu có)
        if ($gender !== null && $gender !== '') {
            $where[] = "gioiTinh = :gender";
            $params[':gender'] = $gender;
        }

        // 3) Age range (nếu có)
        if ($ageMin !== null && $ageMax !== null) {
            $where[] = "tuoi BETWEEN :ageMin AND :ageMax";
            $params[':ageMin'] = $ageMin;
            $params[':ageMax'] = $ageMax;
        } elseif ($ageMin !== null) {
            $where[] = "tuoi >= :ageMin";
            $params[':ageMin'] = $ageMin;
        } elseif ($ageMax !== null) {
            $where[] = "tuoi <= :ageMax";
            $params[':ageMax'] = $ageMax;
        }

        // 4) Hobbies (CSV) — chỉ cần trùng ≥1
        if (!empty($hobbies)) {
            $or = [];
            foreach ($hobbies as $i => $h) {
                $key = ":hb$i";
                $params[$key] = strtolower(str_replace(' ', '', trim($h)));
                $or[] = "FIND_IN_SET($key, LOWER(REPLACE(soThich,' ','')))";
            }
            $where[] = '(' . implode(' OR ', $or) . ')';
        }

        // 5) Full-text keyword search trên: hoTen, email, moTa, bio, diaChi, hocVan
        if ($keyword !== null && trim($keyword) !== '') {
            $kw = trim($keyword);
            // Tách từ để tìm fuzzy matching
            $words = preg_split('/\s+/', $kw, -1, PREG_SPLIT_NO_EMPTY);
            
            if (!empty($words)) {
                $keywordOr = [];
                foreach ($words as $i => $word) {
                    $wordKey = ":kw$i";
                    $params[$wordKey] = "%$word%";
                    $keywordOr[] = "(hoTen LIKE $wordKey OR email LIKE $wordKey OR moTa LIKE $wordKey OR bio LIKE $wordKey OR diaChi LIKE $wordKey OR hocVan LIKE $wordKey)";
                }
                $where[] = '(' . implode(' OR ', $keywordOr) . ')';

                // Sắp xếp ưu tiên theo độ khớp
                $params[':kw_exact'] = $kw;
                $params[':kw_contain'] = "%$kw%";
                $orderBy[] = "CASE
                    WHEN hoTen = :kw_exact THEN 5
                    WHEN hoTen LIKE :kw_contain THEN 4
                    WHEN email LIKE :kw_contain THEN 3
                    WHEN moTa LIKE :kw_contain THEN 2
                    WHEN bio LIKE :kw_contain THEN 1
                    ELSE 0
                 END DESC";
            }
        }

        // Không nhập gì hết + không có bộ lọc khác -> trả rỗng
        $hasKeyword = $keyword !== null && trim($keyword) !== '';
        $hasOtherFilters = !empty($where);
        
        if (!$hasKeyword && !$hasOtherFilters) {
            return [];
        }

        // Sắp xếp phụ
        $orderBy[] = "hoTen ASC";

        $sql = "SELECT maTV AS id, hoTen, tuoi, gioiTinh, diaChi, soThich, email, moTa, bio, hocVan, anhDaiDien
                FROM thanhvien
                WHERE trangThai = 'hoatdong'
                " . (empty($where) ? "" : "AND " . implode(' AND ', $where)) . "
                ORDER BY " . implode(', ', $orderBy) . "
                LIMIT 100";

        $stm = $this->db->prepare($sql);
        $stm->execute($params);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm kiếm ưu tiên thực tế (cũ - giữ lại cho compatibility):
     * - Location, Gender: WHERE khi có
     * - Age range (min/max): WHERE khi bật dùng
     * - Hobbies (CSV): OR các FIND_IN_SET, chỉ cần trùng ≥1
     * - Name:
     *    + nếu chỉ có tên -> WHERE LIKE
     *    + nếu có tiêu chí khác -> chỉ ORDER BY ưu tiên match
     */
    public function searchPractical(
        ?string $name,
        ?int $ageMin,
        ?int $ageMax,
        ?string $gender,
        array $hobbies,
        ?string $location
    ): array
    {
        $where   = [];
        $params  = [];
        $orderBy = [];

        // 1) Location (mạnh nhất)
        if ($location !== null && trim($location) !== '') {
            $where[] = "diaChi = :loc";
            $params[':loc'] = trim($location);
        }

        // 2) Gender
        if ($gender !== null && $gender !== '') {
            $where[] = "gioiTinh = :gender";
            $params[':gender'] = $gender;
        }

        // 2) Age range (nếu có ít nhất 1 đầu mút)
        if ($ageMin !== null && $ageMax !== null) {
            $where[] = "tuoi BETWEEN :ageMin AND :ageMax";
            $params[':ageMin'] = $ageMin;
            $params[':ageMax'] = $ageMax;
        } elseif ($ageMin !== null) {
            $where[] = "tuoi >= :ageMin";
            $params[':ageMin'] = $ageMin;
        } elseif ($ageMax !== null) {
            $where[] = "tuoi <= :ageMax";
            $params[':ageMax'] = $ageMax;
        }

        // 4) Hobbies (CSV) — chỉ cần trùng ≥1
        // DB: "Game/Esports, Âm nhạc, Đọc sách"
        // So sánh: bỏ khoảng trắng + lowercase ở CẢ 2 phía để match bền vững
        if (!empty($hobbies)) {
            $or = [];
            foreach ($hobbies as $i => $h) {
                $key = ":hb$i";
                // Chuẩn hóa param ngay từ PHP (lowercase + bỏ khoảng trắng)
                $params[$key] = strtolower(str_replace(' ', '', trim($h)));
                // So ở DB: LOWER(REPLACE(soThich,' ','')) biến "Game / Esports, Âm nhạc"
                // thành "game/esports,âmnhạc", rồi FIND_IN_SET token đã chuẩn hóa
                $or[] = "FIND_IN_SET($key, LOWER(REPLACE(soThich,' ','')))";
            }
            $where[] = '(' . implode(' OR ', $or) . ')';
        }

        // Có tiêu chí nào khác ngoài tên không?
        $hasOtherFilters = !empty($where);

        // 3) Name
        if ($name !== null && trim($name) !== '') {
            $kw = trim($name);

            if (!$hasOtherFilters) {
                // Chỉ có mỗi tên -> lọc cứng
                $where[] = "hoTen LIKE :kw_like";
                $params[':kw_like'] = "%$kw%";
            }

            // Xếp hạng ưu tiên theo độ khớp tên
            $params[':kw_exact'] = $kw;
            $params[':kw_order'] = "%$kw%";
            $orderBy[] =
                "CASE
                   WHEN hoTen = :kw_exact THEN 3
                   WHEN hoTen LIKE :kw_order THEN 2
                   ELSE 0
                 END DESC";
        }

        // Không nhập gì hết -> trả rỗng để tránh dump toàn bảng
        if (!$hasOtherFilters && ($name === null || $name === '')) {
            return [];
        }

        // Sắp xếp phụ
        $orderBy[] = "hoTen ASC";

        $sql = "SELECT maTV AS id, hoTen, tuoi, gioiTinh, diaChi, soThich, anhDaiDien
                FROM thanhvien
                " . (empty($where) ? "" : "WHERE " . implode(' AND ', $where)) . "
                ORDER BY " . implode(', ', $orderBy) . "
                LIMIT 100";

        $stm = $this->db->prepare($sql);
        $stm->execute($params);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
