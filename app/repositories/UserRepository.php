<?php
class UserRepository
{
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    /**
     * Tìm kiếm ưu tiên thực tế:
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

        $sql = "SELECT maTV AS id, hoTen, tuoi, gioiTinh, diaChi, soThich
                FROM thanhvien
                " . (empty($where) ? "" : "WHERE " . implode(' AND ', $where)) . "
                ORDER BY " . implode(', ', $orderBy) . "
                LIMIT 100";

        $stm = $this->db->prepare($sql);
        $stm->execute($params);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
