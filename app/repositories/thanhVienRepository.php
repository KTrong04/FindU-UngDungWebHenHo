<?php
require_once __DIR__ . '/../config/dataBaseConnect.php';
require_once __DIR__ . '/../models/thanhVienModel.php';

class thanhVienRepository
{
    private $conn;

    public function __construct()
    {
        $db = new dataBaseConnect();
        $this->conn = $db->connect();
    }

    //  ThanhVien

    public function find_password_now($maTV)
    {
        $sql = "SELECT password FROM thanhvien WHERE maTV = :maTV";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateNewPassword($maTV, $passwordnew)
    {
        $sql = "UPDATE thanhvien 
                SET password = :password
                WHERE maTV = :maTV";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->bindParam(':password', $passwordnew);
        return $stmt->execute();
    }
    // Create
    public function create($hoTen, $tuoi, $gioiTinh, $email, $password)
    {
        $thanhVien = new thanhVienModel($hoTen, $tuoi, $gioiTinh, $email, $password);

        // Kiểm tra email đã tồn tại chưa
        $checkSql = "SELECT COUNT(*) FROM thanhvien WHERE email = :email";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindParam(':email', $thanhVien->email);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            // Email đã tồn tại -> không thêm nữa
            return false;
        }

        // Nếu email chưa tồn tại, tiến hành thêm mới
        $sql = "INSERT INTO thanhvien (hoTen, tuoi, gioiTinh, email, password)
                VALUES (:hoTen, :tuoi, :gioiTinh, :email, :password)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':hoTen', $thanhVien->hoTen);
        $stmt->bindParam(':tuoi', $thanhVien->tuoi);
        $stmt->bindParam(':gioiTinh', $thanhVien->gioiTinh);
        $stmt->bindParam(':email', $thanhVien->email);
        $stmt->bindParam(':password', $thanhVien->password);

        return $stmt->execute();
    }

    // Read
    public function Read_One($email)
    {
        $sql = "SELECT * FROM thanhvien WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getThanhVienById($maTV)
    {
        $sql = "SELECT * FROM thanhvien WHERE maTV = :maTV LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Read all
    public function Read_All()
    {
        $sql = "SELECT * FROM thanhvien";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function update($id, $hoTen, $tuoi, $gioiTinh, $email)
    {
        $sql = "UPDATE thanhvien 
                SET hoTen = :hoTen, tuoi = :tuoi, gioiTinh = :gioiTinh, email = :email 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':hoTen', $hoTen);
        $stmt->bindParam(':tuoi', $tuoi);
        $stmt->bindParam(':gioiTinh', $gioiTinh);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Delete
    public function delete($id)
    {
        $sql = "DELETE FROM thanhvien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function findOne_ghepDoi($soThich, $age_min, $age_max, $gioiTinh)
    {
        // Tạo phần điều kiện LIKE cho từng sở thích
        $conditions = [];
        foreach ($soThich as $index => $s) {
            $conditions[] = "(soThich LIKE :st$index)";
        }

        // Tạo câu SQL tính điểm trùng khớp
        // Mỗi LIKE đúng được tính là 1 điểm (1 nếu có, 0 nếu không)
        $scoreExpr = [];
        foreach ($soThich as $index => $s) {
            $scoreExpr[] = "CASE WHEN soThich LIKE :sc$index THEN 1 ELSE 0 END";
        }

        $sql = "
        SELECT *, 
               (" . implode(' + ', $scoreExpr) . ") AS soThichTrung
        FROM thanhvien
        WHERE gioiTinh = :gioiTinh
          AND tuoi BETWEEN :age_min AND :age_max
          AND (" . implode(' OR ', $conditions) . ")
        ORDER BY soThichTrung DESC, RAND()
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);

        // Gán giá trị cho LIKE và COUNT
        foreach ($soThich as $index => $s) {
            $likeValue = "%$s%";
            $stmt->bindValue(":st$index", $likeValue);
            $stmt->bindValue(":sc$index", $likeValue);
        }

        $stmt->bindParam(':gioiTinh', $gioiTinh);
        $stmt->bindParam(':age_min', $age_min);
        $stmt->bindParam(':age_max', $age_max);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateThongTinTV($maTV, $soThich, $avatar, $diaChi)
    {
        $avatar = !empty($avatar['images']) ? implode(',', $avatar['images']) : null;
        $sql = "UPDATE thanhvien 
                SET soThich = :soThich, anhDaiDien = :avatar, diaChi = :diaChi
                WHERE maTV = :maTV";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':soThich', $soThich);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':diaChi', $diaChi);
        $stmt->bindParam(':maTV', $maTV);

        return $stmt->execute();
    }

    public function update_profile($maTV, $thanhVien)
    {
        $sql = "UPDATE thanhvien 
                SET hoTen = :hoTen,  
                    anhDaiDien = :avatar, 
                    diaChi = :diaChi,
                    soThich = :soThich,
                    hocVan = :hocVan,
                    hinh = :hinh,
                    bio = :bio
                WHERE maTV = :maTV";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':hoTen', $thanhVien->hoTen);
        $stmt->bindParam(':avatar', $thanhVien->anhDaiDien);
        $stmt->bindParam(':diaChi', $thanhVien->diaChi);
        $stmt->bindParam(':soThich', $thanhVien->soThich);
        $stmt->bindParam(':hocVan', $thanhVien->hocVan);
        $stmt->bindParam(':hinh', $thanhVien->hinh);
        $stmt->bindParam(':bio', $thanhVien->bio);
        $stmt->bindParam(':maTV', $maTV);

        return $stmt->execute();
    }

    // list gợi ý ghép đôi
    public function find_goiY_ghepDoi($maTV, $gioiTinh, $tuoi, $soThich)
    {
        // Tách sở thích thành mảng
        $arrSoThich = array_filter(array_map('trim', explode(',', $soThich)));

        $likeSQL = "";
        if (!empty($arrSoThich)) {
            $likeConditions = [];
            foreach ($arrSoThich as $index => $st) {
                $likeConditions[] = "tv.soThich LIKE :st{$index}";
            }
            $likeSQL = " AND (" . implode(' OR ', $likeConditions) . ") ";
        }

        // SQL chuẩn
        $sql = "SELECT tv.*
                FROM thanhvien tv

                -- 1. Kiểm tra đã tương tác like/dislike chưa
                LEFT JOIN thanhvien_ghepdoi gd
                    ON (gd.maNguoiGui = :maTV AND gd.maNguoiNhan = tv.maTV)
                    

                -- 2. Kiểm tra đã là cặp đôi chưa
                LEFT JOIN thanhvien_capdoi cd
                    ON (
                        (cd.maThanhVien1 = :maTV AND cd.maThanhVien2 = tv.maTV)
                        OR
                        (cd.maThanhVien1 = tv.maTV AND cd.maThanhVien2 = :maTV)
                    )

                WHERE 
                    gd.maNguoiGui IS NULL
                    AND gd.maNguoiNhan IS NULL
                    AND cd.maThanhVien1 IS NULL
                    AND cd.maThanhVien2 IS NULL

                    AND tv.maTV != :maTV
                    AND tv.gioiTinh != :gioiTinh
                    AND tv.tuoi BETWEEN :minTuoi AND :maxTuoi
                    $likeSQL
            ";


        $stmt = $this->conn->prepare($sql);

        // Gán params
        $stmt->bindParam(':maTV', $maTV);
        $stmt->bindParam(':gioiTinh', $gioiTinh);

        $minTuoi = $tuoi - 12;
        $maxTuoi = $tuoi + 12;

        $stmt->bindParam(':minTuoi', $minTuoi);
        $stmt->bindParam(':maxTuoi', $maxTuoi);

        // Gán sở thích nếu có
        if (!empty($arrSoThich)) {
            foreach ($arrSoThich as $index => $st) {
                $stmt->bindValue(":st{$index}", "%$st%");
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // thanhvien_ghepdoi
    public function find_moighepDoi_ganNhat($maTV, $trangThai)
    {
        $sql = "SELECT gd.*, tv_gui.maTV, tv_gui.hoTen, tv_gui.anhDaiDien
            FROM thanhvien_ghepdoi gd
            JOIN thanhVien tv_gui ON gd.maNguoiGui = tv_gui.maTV
            WHERE gd.maNguoiNhan = :maTV
              AND gd.trangThai = :trangThai
            ORDER BY gd.ngayGui DESC
            LIMIT 1"; // chỉ lấy 1 bản ghi mới nhất

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->bindParam(':trangThai', $trangThai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // fetch 1 bản ghi
    }

    private function get_existing_interaction($gui, $nhan)
    {
        $sql = "SELECT * FROM thanhvien_ghepdoi
                WHERE (maNguoiGui = :gui AND maNguoiNhan = :nhan)
                   OR (maNguoiGui = :nhan AND maNguoiNhan = :gui)
                ORDER BY ngayGui DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':gui' => $gui, ':nhan' => $nhan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function add_guiGhepDoi($maTV_gui, $maTV_nhan, $trangThai)
    {
        // 1. Kiểm tra đã là cặp đôi chưa → nếu có thì không làm gì
        if ($this->check_capDoi($maTV_gui, $maTV_nhan)) {
            return ['success' => true, 'matched' => false, 'message' => 'Already matched'];
        }

        // 2. Lấy tương tác cũ (nếu có)
        $existing = $this->get_existing_interaction($maTV_gui, $maTV_nhan);

        // 3. Nếu có tương tác cũ và là 'nope' → không cho like/superlike
        if ($existing && $existing['trangThai'] === 'nope') {
            if (in_array($trangThai, ['like', 'superlike'])) {
                return ['success' => false, 'error' => 'Previously rejected'];
            }
            // Nếu nope lại thì có thể cập nhật, nhưng thường Tinder không cho nope lại
            return false;
        }

        // 4. Nếu có tương tác cũ là 'like' hoặc 'superlike' từ phía kia → và mình like/superlike = MATCH!
        if (
            $existing && in_array($existing['trangThai'], ['like', 'superlike']) &&
            $existing['maNguoiGui'] == $maTV_nhan &&  // Phía kia gửi trước
            in_array($trangThai, ['like', 'superlike'])
        ) {
            $this->add_capDoi($maTV_gui, $maTV_nhan);
            if ($this->check_cuocTroChuyen($maTV_gui, $maTV_nhan) != true) {
                $this->add_CuocTroChuyen($maTV_gui, $maTV_nhan);
            }

            return ['success' => true, 'matched' => true];
        }

        // 5. Nếu chưa có tương tác hoặc tương tác cũ không match → thêm mới
        $sql = "INSERT INTO thanhvien_ghepdoi (maNguoiGui, maNguoiNhan, ngayGui, trangThai)
                VALUES (:maNguoiGui, :maNguoiNhan, NOW(), :trangThai)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maNguoiGui', $maTV_gui, PDO::PARAM_INT);
        $stmt->bindParam(':maNguoiNhan', $maTV_nhan, PDO::PARAM_INT);
        $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_STR);

        $executed = $stmt->execute();
        return $executed ? ['success' => true, 'matched' => false] : ['success' => false, 'error' => 'Database error'];
    }

    public function check_capDoi($maTV1, $maTV2)
    {
        $sql_check = "SELECT * FROM thanhvien_capdoi 
                      WHERE (maThanhVien1 = :tv1 AND maThanhVien2 = :tv2)
                         OR (maThanhVien1 = :tv2 AND maThanhVien2 = :tv1)
                      LIMIT 1";
        $stmt = $this->conn->prepare($sql_check);
        $stmt->execute([':tv1' => $maTV1, ':tv2' => $maTV2]);
        return (bool) $stmt->fetch();
    }

    public function add_capDoi($maTV1, $maTV2)
    {
        if ($this->check_capDoi($maTV1, $maTV2)) {
            return false;
        }

        $sql = "INSERT INTO thanhvien_capdoi (maThanhVien1, maThanhVien2, ngayGhepDoi)
                VALUES (:maThanhVien1, :maThanhVien2, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maThanhVien1', $maTV1, PDO::PARAM_INT);
        $stmt->bindParam(':maThanhVien2', $maTV2, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findAll_CapDoi($maTV)
    {
        $sql = "SELECT tv.maTV, tv.hoTen, tv.anhDaiDien
            FROM thanhvien tv
            JOIN thanhvien_capdoi cd 
                ON (cd.maThanhVien1 = tv.maTV OR cd.maThanhVien2 = tv.maTV)
            WHERE :user_maTV IN (cd.maThanhVien1, cd.maThanhVien2)
              AND tv.maTV <> :user_maTV";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_maTV', $maTV, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteGhepDoi($maTV_huy, $maTV_muonHuy)
    {
        $sql = "DELETE FROM thanhvien_ghepdoi WHERE (maNguoiGui = :maTV_huy AND maNguoiNhan = :maTV_muonHuy) OR (maNguoiGui = :maTV_muonHuy AND maNguoiNhan = :maTV_huy)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':maTV_huy', $maTV_huy, PDO::PARAM_INT);
        $stmt->bindValue(':maTV_muonHuy', $maTV_muonHuy, PDO::PARAM_INT);

        return $stmt->execute();;
    }

    public function deleteCapDoi($maTV_huy, $maTV_muonHuy)
    {
        $sql = "DELETE FROM thanhvien_capdoi WHERE (maThanhVien1 = :maTV_huy AND maThanhVien2 = :maTV_muonHuy) OR (maThanhVien1 = :maTV_muonHuy AND maThanhVien2 = :maTV_huy)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':maTV_huy', $maTV_huy, PDO::PARAM_INT);
        $stmt->bindValue(':maTV_muonHuy', $maTV_muonHuy, PDO::PARAM_INT);

        return $stmt->execute();;
    }

    public function findAll_tuongHop_ById($maTV, $trangThai)
    {
        $sql = "SELECT gd.*, tv.maTV, tv.hoTen, tv.anhDaiDien
                FROM thanhvien_ghepdoi gd
                JOIN thanhvien tv ON gd.maNguoiGui = tv.maTV
                WHERE gd.maNguoiGui = tv.maTV AND gd.maNguoiNhan = :maTV AND gd.trangThai = :trangThai
                ORDER BY ngayGui DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':maTV', $maTV, PDO::PARAM_INT);
        $stmt->bindValue(':trangThai', $trangThai, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // thanhvien_cuoctrochuyen
    public function check_cuocTroChuyen($maTV1, $maTV2)
    {
        $sql_check = "SELECT * FROM thanhvien_cuoctrochuyen
                      WHERE (maTV1 = :tv1 AND maTV2 = :tv2)
                         OR (maTV1 = :tv2 AND maTV2 = :tv1)
                      LIMIT 1";
        $stmt = $this->conn->prepare($sql_check);
        $stmt->execute([':tv1' => $maTV1, ':tv2' => $maTV2]);
        return (bool) $stmt->fetch();
    }

    public function add_CuocTroChuyen($maTV1, $maTV2)
    {
        $sql = "INSERT INTO thanhvien_cuoctrochuyen (maTV1, maTV2, ngayTao)
                VALUES (:maThanhVien1, :maThanhVien2, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maThanhVien1', $maTV1, PDO::PARAM_INT);
        $stmt->bindParam(':maThanhVien2', $maTV2, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findAll_cuocTroChuyen($maTV)
    {
        $sql = "SELECT ctc.*, tv.maTV, tv.hoTen, tv.anhDaiDien
                FROM thanhvien_cuoctrochuyen ctc
                JOIN thanhvien tv ON (ctc.maTV1 = :user_maTV AND ctc.maTV2 = tv.maTV) OR (ctc.maTV1 = tv.maTV AND ctc.maTV2 = :user_maTV)
                WHERE maTV1 = :user_maTV || maTV2 = :user_maTV";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_maTV', $maTV, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne_cuocTroChuyen($maCTC)
    {
        $sql = "SELECT *
                FROM thanhvien_cuoctrochuyen
                WHERE maCTC = :maCTC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':maCTC', $maCTC, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //thanhvien_tinhan
    public function addMessage($maCTC, $user_maTV, $maTV_chat, $message, $files)
    {
        // Lấy danh sách ảnh và video (mảng trả về từ handleUploadFiles)
        $images = !empty($files['images']) ? implode(',', $files['images']) : null;
        $videos = !empty($files['videos']) ? implode(',', $files['videos']) : null;
        $sql = "INSERT INTO thanhvien_tinnhan (maCTC, maTVGui , maTVNhan, noiDung, ngayGui, hinh, video)
                VALUES (:maCTC, :user_maTV, :maTV_chat, :noiDung, NOW(), :hinh, :video)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maCTC', $maCTC, PDO::PARAM_INT);
        $stmt->bindParam(':user_maTV', $user_maTV, PDO::PARAM_INT);
        $stmt->bindParam(':maTV_chat', $maTV_chat, PDO::PARAM_INT);
        $stmt->bindParam(':noiDung', $message, PDO::PARAM_STR);
        $stmt->bindParam(':hinh', $images, PDO::PARAM_STR);
        $stmt->bindParam(':video', $videos, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function findAll_message($maCTC)
    {
        $sql = "SELECT *
                FROM thanhvien_tinnhan
                WHERE maCTC = :maCTC
                ORDER BY ngayGui ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':maCTC', $maCTC, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // BaiViet
    // Create
    public function createBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $files)
    {
        // Lấy danh sách ảnh và video (mảng trả về từ handleUploadFiles)
        $images = !empty($files['images']) ? implode(',', $files['images']) : null;
        $videos = !empty($files['videos']) ? implode(',', $files['videos']) : null;

        $sql = "INSERT INTO baiviet (
                maTV, noiDung, theTag, quyenXem, thoiGianDang, hinhAnh, video, trangThai, moTa
            ) VALUES (
                :maTV, :noiDung, :theTag, :quyenXem, NOW(), :hinhAnh, :video, 'cho_duyet', NULL
            )";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV);
        $stmt->bindParam(':noiDung', $noiDung);
        $stmt->bindParam(':theTag', $hashtag);
        $stmt->bindParam(':quyenXem', $quyenXem);
        $stmt->bindParam(':hinhAnh', $images);
        $stmt->bindParam(':video', $videos);

        return $stmt->execute();
    }

    // Read all Bai Viet
    public function readAll_BaiViet($maTV)
    {
        $sql = "SELECT b.*, p.anhDaiDien, p.hoTen
            FROM baiviet b
            JOIN thanhvien p ON b.maTV = p.maTV
            WHERE b.maTV = :maTV
            AND b.trangThai = :trangThai
            ORDER BY b.thoiGianDang DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maTV', $maTV, PDO::PARAM_INT);
        $stmt->bindValue(':trangThai', 'da_duyet', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
