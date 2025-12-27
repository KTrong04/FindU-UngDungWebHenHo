<?php
require_once __DIR__ . '/../repositories/thanhVienRepository.php';
require_once __DIR__ . '/../helpers/thanhVienHelper.php';
require_once __DIR__ . '/../helpers/securityChat.php';

class thanhVienController
{
    private $repo;
    public $helper;
    private $helperChat;

    public function __construct()
    {
        $this->repo = new thanhVienRepository();
        $this->helper = new thanhVienHelper();
        $this->helperChat = new SecurityHelper();
    }

    // acount
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function dangKy($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword)
    {
        $log_validateInput = $this->helper->validateInput($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword);
        if ($log_validateInput !== true) {
            echo $log_validateInput;
        } else {
            $password = $this->hashPassword($password);
            if ($this->repo->create($hoTen, $tuoi, $gioiTinh, $email, $password) == false) {
                echo $this->helper->message('error', 'Email này đã được sử dụng. Vui lòng chọn email khác!');
                return;
            }

            echo $this->helper->message('success', 'Đăng ký thành công! Chuyển hướng đến trang đăng nhập...');
            header("refresh: 2, url=/project-FindU/app/views/user/dangNhap.php");
        }
    }


    public function dangNhap($email, $password)
    {
        $log_validateInput = $this->helper->validateLoginInput($email, $password);
        if ($log_validateInput !== true) {
            echo $log_validateInput;
        } else {
            $user = $this->repo->Read_One($email);
            if ($user && password_verify($password, $user['password'])) {
                // Khởi tạo session
                if (empty(session_id())) {
                    session_start();
                }
                $_SESSION['user_maTV'] = $user['maTV'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['hoTen'];
                $_SESSION['user_avatar'] = $user['anhDaiDien'];
                $_SESSION['user_soThich'] = $user['soThich'];
                $_SESSION['user_gioiTinh'] = $user['gioiTinh'];
                $_SESSION['user_tuoi'] = $user['tuoi'];
                $_SESSION['user_diaChi'] = $user['diaChi'];
                $_SESSION['user_trangThai'] = $user['trangThai'];

                if ($user['trangThai'] == 'khoa') {
                    echo $this->helper->message('error', 'Tài khoản đang bị khóa');
                    return;
                }

                echo $this->helper->message('success', 'Đăng nhập thành công! Chuyển hướng đến trang chủ...');
                header("Location: /project-FindU/app/views/user/trangChu.php");
            } else {
                echo $this->helper->message('error', 'Email hoặc mật khẩu không đúng!');
            }
        }
    }

    public function refreshUserSession($maTV)
    {
        // Lấy thông tin mới nhất từ database
        $user = $this->repo->getThanhVienById($maTV);

        if ($user) {
            // Cập nhật lại các session hiện có
            $_SESSION['user_maTV'] = $user['maTV'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['hoTen'];
            $_SESSION['user_avatar'] = $user['anhDaiDien'];
            $_SESSION['user_soThich'] = $user['soThich'];
            $_SESSION['user_diaChi'] = $user['diaChi'];

            $_SESSION['user_trangThai'] = $user['trangThai'];

            return true;
        }

        return false;
    }

    public function configLogin()
    {
        if (empty(session_id())) {
            session_start();
        }
        if (!isset($_SESSION['user_maTV']) || !isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || $_SESSION['user_maTV'] == "" || $_SESSION['user_email'] == "" || $_SESSION['user_name'] == "") {
            return false;
        } else {
            $user = $this->repo->Read_One($_SESSION['user_email']);
            if (!$user) {
                return false;
            } elseif ($user['maTV'] != $_SESSION['user_maTV'] || $user['email'] != $_SESSION['user_email'] || $user['hoTen'] != $_SESSION['user_name']) {
                return false;
            }
            return true;
        }
    }

    public function dangXuat()
    {
        session_destroy();
        header("Location: /project-FindU/app/views/user/");
    }

    public function checkPasswordNow($password, $maTV)
    {
        if ($password != "") {
            $pass_db = $this->repo->find_password_now($maTV);
            if (!password_verify($password, $pass_db['password'])) {
                echo $this->helper->message('error', 'Mật khẩu không chính xác!');
                return;
            } else {
                return true;
            }
        } else {
            echo $this->helper->message('error', 'Vui lòng nhập mật khẩu hiện tại!');
            return;
        }
    }

    public function doiMatKhau($maTV, $passwordnew, $repasswordnew)
    {
        // 1. Kiểm tra rỗng
        if ($passwordnew == "" || $repasswordnew == "") {
            echo $this->helper->message('error', 'Vui lòng nhập đầy đủ thông tin');
            return;
        }

        // 2. Kiểm tra khớp
        if ($passwordnew != $repasswordnew) {
            echo $this->helper->message('error', 'Mật khẩu nhập lại không khớp');
            return;
        }

        // 3. KIỂM TRA ĐỘ MẠNH (REGEX) - Server side validation
        // Ít nhất 8 ký tự, 1 hoa, 1 thường, 1 số, 1 ký tự đặc biệt
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/';

        if (!preg_match($pattern, $passwordnew)) {
            echo $this->helper->message('error', 'Mật khẩu không đủ mạnh (Cần 8 ký tự, bao gồm chữ hoa, thường, số và ký tự đặc biệt)');
            return;
        }

        // 4. Mã hóa và lưu
        $passwordnewHash = $this->hashPassword($passwordnew);

        if ($this->repo->updateNewPassword($maTV, $passwordnewHash)) {
            echo $this->helper->message('success', 'Đổi mật khẩu thành công');
            return;
        } else {
            echo $this->helper->message('error', 'Đổi mật khẩu thất bại');
            return;
        }
    }

    // person
    public function dangBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $files)
    {
        $log_validateInput = $this->helper->validateInputDangBaiViet($noiDung, $hashtag, $quyenXem, $files);
        if ($log_validateInput !== true) {
            echo $log_validateInput;
        } else {
            $filesCheck = $this->handleUploadFiles($files);
            if ($filesCheck === false) {
                echo $this->helper->message('error', 'Tệp tin tải lên không hợp lệ. Vui lòng chọn tệp tin hình ảnh hoặc video.');
                return;
            }

            $bv = $this->repo->createBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $filesCheck);
            if ($bv !== true) {
                echo $this->helper->message('error', 'Đăng bài viết thất bại! Vui lòng thử lại.');
            } else {
                echo $this->helper->message('success', 'Đăng bài viết thành công');
            }
        }
    }

    public function handleUploadFiles($files)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $videoExtensions = ['mp4', 'avi', 'mov'];

        $images = [];
        $videos = [];
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/Project-FindU/public/uploads';

        // Nếu chỉ có 1 file, chuyển sang dạng mảng để xử lý chung
        if (!is_array($files['name'])) {
            $files = [
                'name' => [$files['name']],
                'tmp_name' => [$files['tmp_name']],
                'error' => [$files['error']],
            ];
        }

        foreach ($files['name'] as $key => $name) {
            $tmpName = $files['tmp_name'][$key];
            $error = $files['error'][$key];

            if ($error === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if (in_array($ext, $imageExtensions)) {
                    $newName = 'img_' . uniqid() . time() . '.' . $ext;
                    move_uploaded_file($tmpName, $uploadPath . '/images/' . $newName);
                    $images[] = $newName;
                } elseif (in_array($ext, $videoExtensions)) {
                    $newName = 'video_' . uniqid() . time() . '.' . $ext;
                    move_uploaded_file($tmpName, $uploadPath . '/videos/' . $newName);
                    $videos[] = $newName;
                } else {
                    return false; // File không hợp lệ
                }
            }
        }

        return [
            'images' => $images,
            'videos' => $videos
        ];
    }



    // // Hiển thị bài viết
    // public function hienThiBaiViet()
    // {
    //     $user_maTV = $_SESSION['user_maTV'];
    //     $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/Project-FindU/app/views/includes/baiViet.php';
    //     foreach ($this->repo->ReadAll_BaiViet($user_maTV) as $bv) {
    //         include $uploadPath;
    //     }
    // }

    // public function ghepDoi($soThich, $age_min, $age_max, $gioiTinh)
    // {
    //     // Kiểm tra dữ liệu đầu vào
    //     if (empty($soThich) || empty($age_min) || empty($age_max) || empty($gioiTinh)) {
    //         echo $this->helper->message('error', 'Vui lòng thiết lập đầy đủ thông tin.');
    //         return false;
    //     }

    //     // Tìm người phù hợp nhất
    //     $result = $this->repo->findOne_ghepDoi($soThich, $age_min, $age_max, $gioiTinh);

    //     // Xử lý kết quả
    //     if ($result) {
    //         echo "
    //     <div class='box-result'>
    //         <h3>💘 Người phù hợp nhất</h3>
    //         <p><b>{$result['hoTen']}</b> ({$result['tuoi']} tuổi)</p>
    //         <p><b>Sở thích:</b> {$result['soThich']}</p>
    //     </div>";
    //         return true;
    //     } else {
    //         echo $this->helper->message('error', 'Không tìm thấy người phù hợp.');
    //         return false;
    //     }
    // }

    public function ThemThongTinTV($maTV, $soThich, $avatar, $diaChi)
    {
        if (empty($soThich) || empty($diaChi)) {
            echo $this->helper->message('error', 'Vui lòng chọn sở thích và địa chỉ của bạn');
            return;
        }

        $filesCheck = $this->handleUploadFile_avatar($avatar);
        if ($filesCheck === false) {
            echo $this->helper->message('error', 'Tệp tin tải lên không hợp lệ. Vui lòng chọn tệp tin hình ảnh hoặc video.');
            return;
        }
        if ($this->repo->updateThongTinTV($maTV, $soThich, $filesCheck, $diaChi) == false) {
            echo $this->helper->message('error', 'Thêm thông tin thất bại vui lòng thử lại sau');
            return;
        } else {
            $this->refreshUserSession($maTV);
            echo $this->helper->message('success', 'Thêm thông tin thành công');
            return true;
        }
    }

    public function handleUploadFile_avatar($file)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/Project-FindU/public/uploads/avatars';

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $imageExtensions)) {
            return false;
        }

        // Kiểm tra dung lượng file (giới hạn 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }

        $newName = 'avatar_' . uniqid() . time() . '.' . $ext;

        // Đường dẫn lưu file
        $destination = $uploadPath . '/' . $newName;

        // Di chuyển file upload vào thư mục
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'images' => [$newName]
            ];
        }

        return false;
    }

    public function load_info($maTV)
    {
        return $this->repo->getThanhVienById($maTV);
    }

    // Ghép đôi
    // List gợi ý ghép đôi
    public function list_goiY_ghepDoi($maTV, $gioiTinh, $tuoi, $soThich)
    {
        $result = $this->repo->find_goiY_ghepDoi($maTV, $gioiTinh, $tuoi, $soThich);

        if (empty($result)) {
            echo '<p style="color:black;opacity:0.8;text-align:center;">Không có gợi ý phù hợp 😢</p>';
            return;
        }

        $zindex = count($result);

        foreach ($result as $tv) {

            // Escape mọi dữ liệu xuất ra HTML
            $maTV_item  = htmlspecialchars($tv['maTV'] ?? '', ENT_QUOTES, 'UTF-8');
            $hoTen      = htmlspecialchars($tv['hoTen'] ?? '', ENT_QUOTES, 'UTF-8');
            $tuoi_item  = htmlspecialchars($tv['tuoi'] ?? '', ENT_QUOTES, 'UTF-8');
            $hocVan     = htmlspecialchars($tv['hocVan'] ?? '', ENT_QUOTES, 'UTF-8');

            // Avatar — tránh XSS, fallback nếu trống
            $avatarRaw  = $tv['anhDaiDien'] ?? '';
            $avatarSafe = htmlspecialchars($avatarRaw, ENT_QUOTES, 'UTF-8');

            if ($avatarSafe == '' || !file_exists(__DIR__ . "/../../public/uploads/avatars/" . $avatarRaw)) {
                $avatarUrl = "/project-FindU/public/assets/img/no-avatar.png";
            } else {
                $avatarUrl = "/project-FindU/public/uploads/avatars/" . $avatarSafe;
            }
            $this->outputCard($maTV_item, $hoTen, $tuoi_item, $hocVan, $avatarSafe, $zindex, $avatarUrl, '');
            $zindex--;
        }
    }

    public function profile($maTV)
    {
        $tv = $this->repo->getThanhVienById($maTV);
        // Escape mọi dữ liệu xuất ra HTML
        $maTV_item  = htmlspecialchars($tv['maTV'] ?? '', ENT_QUOTES, 'UTF-8');
        $hoTen      = htmlspecialchars($tv['hoTen'] ?? '', ENT_QUOTES, 'UTF-8');
        $tuoi_item  = htmlspecialchars($tv['tuoi'] ?? '', ENT_QUOTES, 'UTF-8');
        $hocVan     = htmlspecialchars($tv['hocVan'] ?? '', ENT_QUOTES, 'UTF-8');

        // Avatar — tránh XSS, fallback nếu trống
        $avatarRaw  = $tv['anhDaiDien'] ?? '';
        $avatarSafe = htmlspecialchars($avatarRaw, ENT_QUOTES, 'UTF-8');
        if ($avatarSafe == '' || !file_exists(__DIR__ . "/../../public/uploads/avatars/" . $avatarRaw)) {
            $avatarUrl = "/project-FindU/public/uploads/avatars/avatar-default.svg";
        } else {
            $avatarUrl = "/project-FindU/public/uploads/avatars/" . $avatarSafe;
        }
        $this->outputCard($maTV_item, $hoTen, $tuoi_item, $hocVan, $avatarSafe, 1, $avatarUrl, $tv);
    }

    public function outputCard($maTV_item, $hoTen, $tuoi_item, $hocVan, $avatarSafe, $zindex, $avatarUrl, $detail)
    {
        echo '<div class="card"
                data-id="' . $maTV_item . '"
                data-name="' . $hoTen . '"
                data-age="' . $tuoi_item . '"
                data-school="' . $hocVan . '"
                data-img="' . $avatarSafe . '"
                style="
                    z-index: ' . $zindex . ';
                    background-image: url(\'' . $avatarUrl . '\');
                    background-size: cover;
                    background-position: center center;
                    background-repeat: no-repeat;
                    transition: transform 300ms;
                ">

            <img class="indicator nope" src="/project-FindU/public/assets/img/NOPE.svg" alt="NOPE">
            <img class="indicator like" src="/project-FindU/public/assets/img/LIKE.svg" alt="LIKE">
            <img class="indicator super" src="/project-FindU/public/assets/img/SUPERLIKE.svg" alt="SUPERLIKE">

            <div class="info">
                <div class="row">
                    <div class="name">' . $hoTen . '
                        <span style="font-weight:700;color:#fff;margin-left:6px;opacity:0.95">' . $tuoi_item . '</span>
                    </div>
                    <div class="verified" title="Verified">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" width="24" height="24" viewBox="0 0 24 24" focusable="false" role="img" stroke-linecap="round"><title>Verified!</title><path fill="#006cc2" fill-rule="evenodd" d="m3.427 8.732-.548.308a1.241 1.241 0 0 0-.394 1.806l.372.506a1.229 1.229 0 0 1 0 1.431l-.372.529c-.416.594-.219 1.453.416 1.783l.547.308c.46.242.723.77.635 1.277l-.11.617a1.218 1.218 0 0 0 1.161 1.43l.635.023c.526.022.985.374 1.117.88l.175.617a1.234 1.234 0 0 0 1.664.793l.591-.243a1.241 1.241 0 0 1 1.401.308l.416.485c.482.55 1.358.55 1.84-.022l.415-.485c.35-.396.898-.528 1.38-.33l.591.242a1.21 1.21 0 0 0 1.642-.814l.175-.617c.132-.506.591-.88 1.117-.902l.635-.044a1.244 1.244 0 0 0 1.138-1.454l-.11-.616a1.246 1.246 0 0 1 .614-1.299l.547-.308a1.242 1.242 0 0 0 .394-1.806l-.372-.506a1.229 1.229 0 0 1 0-1.431l.372-.529c.416-.594.22-1.453-.416-1.783l-.547-.308a1.23 1.23 0 0 1-.635-1.277l.11-.617a1.217 1.217 0 0 0-1.16-1.43l-.636-.023a1.197 1.197 0 0 1-1.117-.88l-.175-.617a1.234 1.234 0 0 0-1.664-.793l-.656.243a1.241 1.241 0 0 1-1.402-.309l-.416-.484a1.207 1.207 0 0 0-1.817.044l-.416.484c-.35.397-.898.529-1.38.33l-.59-.242a1.209 1.209 0 0 0-1.642.815l-.176.617c-.131.506-.59.88-1.116.902l-.635.044a1.244 1.244 0 0 0-1.139 1.453l.11.617c.131.484-.11 1.013-.57 1.277m12.409-.921c.364 0 .684.131.941.395.257.242.385.593.385.945 0 .351-.15.703-.406.966l-5.114 5.677a1.229 1.229 0 0 1-.92.395c-.342 0-.663-.131-.92-.395l-2.567-3.062a1.295 1.295 0 0 1-.385-.945 1.331 1.331 0 0 1 1.326-1.34c.343 0 .664.132.92.395l1.626 1.457 4.194-4.093c.235-.264.577-.395.92-.395" clip-rule="evenodd"></path><path fill="#fff" d="M15.836 7.81c.363 0 .684.132.941.396.257.242.385.593.385.945 0 .351-.15.703-.406.966l-5.114 5.677a1.229 1.229 0 0 1-.92.395c-.342 0-.663-.131-.92-.395l-2.567-3.062a1.295 1.295 0 0 1-.385-.945 1.331 1.331 0 0 1 1.326-1.34c.343 0 .663.132.92.395l1.626 1.457 4.194-4.093c.235-.264.577-.395.92-.395"></path><path fill="var(--color--white, inherit)" fill-rule="evenodd" d="m1.055 8.448.657-.37c.552-.317.84-.95.683-1.532l-.131-.74c-.158-.872.5-1.69 1.366-1.744l.762-.052a1.461 1.461 0 0 0 1.34-1.084l.21-.74a1.45 1.45 0 0 1 1.97-.977l.71.29c.578.238 1.235.08 1.655-.396l.5-.581a1.448 1.448 0 0 1 2.18-.053l.5.581c.42.476 1.103.608 1.68.37l.789-.29c.815-.344 1.76.105 1.997.95l.21.74c.158.608.71 1.031 1.34 1.057l.762.027a1.46 1.46 0 0 1 1.392 1.717l-.131.74c-.105.608.21 1.242.762 1.532l.657.37c.762.397.998 1.427.499 2.14l-.447.634a1.474 1.474 0 0 0 0 1.718l.447.607a1.49 1.49 0 0 1-.473 2.167l-.657.37c-.552.317-.84.925-.735 1.559l.13.74a1.491 1.491 0 0 1-1.365 1.743l-.762.053a1.461 1.461 0 0 0-1.34 1.083l-.21.74a1.45 1.45 0 0 1-1.97.977l-.71-.29a1.459 1.459 0 0 0-1.655.396l-.5.581a1.458 1.458 0 0 1-2.207.027l-.499-.581c-.42-.476-1.103-.608-1.681-.37l-.71.29c-.814.344-1.76-.105-1.996-.95l-.21-.74a1.436 1.436 0 0 0-1.34-1.057l-.763-.027a1.46 1.46 0 0 1-1.392-1.717l.131-.74a1.475 1.475 0 0 0-.762-1.532l-.656-.37c-.762-.396-.999-1.427-.5-2.14l.447-.634a1.474 1.474 0 0 0 0-1.718l-.447-.607a1.49 1.49 0 0 1 .473-2.167m1.824.592.548-.308c.46-.264.7-.793.569-1.277l-.11-.617a1.244 1.244 0 0 1 1.139-1.453l.635-.044a1.218 1.218 0 0 0 1.116-.902l.176-.617a1.21 1.21 0 0 1 1.642-.815l.59.243c.482.198 1.03.066 1.38-.33l.416-.485a1.207 1.207 0 0 1 1.817-.044l.416.484c.35.397.92.507 1.402.309l.656-.243a1.234 1.234 0 0 1 1.664.793l.175.616c.132.507.592.86 1.117.881l.635.022a1.217 1.217 0 0 1 1.16 1.431l-.109.617a1.23 1.23 0 0 0 .635 1.277l.547.308c.635.33.832 1.189.416 1.783l-.372.529a1.229 1.229 0 0 0 0 1.43l.372.507a1.242 1.242 0 0 1-.394 1.806l-.547.308c-.46.264-.7.77-.613 1.299l.11.616a1.244 1.244 0 0 1-1.14 1.454l-.634.044a1.218 1.218 0 0 0-1.117.902l-.175.617a1.21 1.21 0 0 1-1.642.814l-.591-.242a1.216 1.216 0 0 0-1.38.33l-.415.485a1.215 1.215 0 0 1-1.84.022l-.416-.485a1.241 1.241 0 0 0-1.4-.308l-.592.242a1.234 1.234 0 0 1-1.664-.792l-.175-.617a1.197 1.197 0 0 0-1.117-.88l-.635-.022a1.218 1.218 0 0 1-1.16-1.431l.11-.617a1.23 1.23 0 0 0-.636-1.277l-.547-.308c-.635-.33-.832-1.19-.416-1.783l.372-.529a1.229 1.229 0 0 0 0-1.431l-.372-.507A1.241 1.241 0 0 1 2.88 9.04" clip-rule="evenodd"></path></svg>
                    </div>';
        if (!isset($_GET['sidebar'])) {
            echo '
                    <div class="btn-detailes">
                        <a href="' . (isset($_GET['id_profile']) ? 'trangChu.php' : 'hoSo.php?id_profile= ' . $maTV_item . '') . '">
                            <button type="button" class="btn secondary" style="width:36px;height:36px;border-radius:50%; background:rgba(255,255,255,0.12); color:#fff;border:1px solid rgba(255,255,255,0.06)">
                                ' . (isset($_GET['id_profile'])
                ?
                '<svg xmlns="http://www.w3.org/2000/svg" fill="#fff" width="24" height="24" viewBox="0 0 24 24" focusable="false" role="img" aria-hidden="true">
                <title></title>
                <path transform="rotate(180 12 12)" fill-rule="evenodd" d="M12 0c6.627 0 12 5.373 12 12s-5.373 12-12 12S0 18.627 0 12 5.373 0 12 0m-.827 6.401-1.145 1.34-2.787 3.293c-.458.528-.242.97.47.97h2.215c.05.359.156 1.165.267 2.01.14 1.067.287 2.196.344 2.573.015.37.175.72.446.982s.634.416 1.017.431a1.553 1.553 0 0 0 1.018-.43 1.45 1.45 0 0 0 .446-.983c.056-.377.204-1.506.344-2.572.11-.846.216-1.652.267-2.01h2.201c.726 0 .942-.443.484-.972l-1.133-1.339-1.667-1.953-1.133-1.34a1.024 1.024 0 0 0-.364-.295 1.061 1.061 0 0 0-.926 0c-.143.07-.268.17-.364.295" clip-rule="evenodd"></path>
            </svg>'
                :
                '<svg xmlns="http://www.w3.org/2000/svg" fill="#fff" width="24" height="24" viewBox="0 0 24 24" focusable="false" role="img" aria-hidden="true" ><title></title><path fill-rule="evenodd" d="M12 0c6.627 0 12 5.373 12 12s-5.373 12-12 12S0 18.627 0 12 5.373 0 12 0m-.827 6.401-1.145 1.34-2.787 3.293c-.458.528-.242.97.47.97h2.215c.05.359.156 1.165.267 2.01.14 1.067.287 2.196.344 2.573.015.37.175.72.446.982s.634.416 1.017.431a1.553 1.553 0 0 0 1.018-.43 1.45 1.45 0 0 0 .446-.983c.056-.377.204-1.506.344-2.572.11-.846.216-1.652.267-2.01h2.201c.726 0 .942-.443.484-.972l-1.133-1.339-1.667-1.953-1.133-1.34a1.024 1.024 0 0 0-.364-.295 1.061 1.061 0 0 0-.926 0c-.143.07-.268.17-.364.295" clip-rule="evenodd"></path></svg>') . '
                            </button>
                        </a>
                    </div>';
        }

        echo '</div>
                <div class="school">' . $hocVan . '</div>    
            </div>
        </div>
        <div class="infodetailes" id="infodetailes">';
        if (!empty($detail)) {
            echo '
            <div class="info-box-infodetailes">
                <header class="box-infodetailes-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" focusable="false" role="img"><title></title><g fill="var(--color--gray-50, inherit)"><path d="M7.713 3.826c-.699-.568-1.7-.607-2.388-.027C2.022 6.584 0 10.285 0 14.493c0 4.181 2.75 6.644 5.842 6.644 2.864 0 5.27-2.406 5.27-5.327 0-2.807-2.005-4.697-4.41-4.697-.595 0-1.088-.531-.8-1.051.455-.82 1.109-1.642 1.829-2.36C8.76 6.678 8.918 4.806 7.79 3.89zm12.888 0c-.699-.568-1.7-.607-2.388-.027-3.303 2.785-5.325 6.486-5.325 10.694 0 4.181 2.807 6.644 5.9 6.644 2.806 0 5.212-2.406 5.212-5.327 0-2.807-1.948-4.697-4.41-4.697-.586 0-1.07-.534-.782-1.044.46-.818 1.108-1.639 1.819-2.356 1.023-1.032 1.178-2.908.05-3.824z"></path></g></svg>
                    Mô tả bản thân
                </header>
                <p>' . htmlspecialchars($detail['bio']) . ' </p>
            </div>';

            // <div class="info-box-infodetailes">
            //     <div class="swiper mySwiper">
            //         <div class="swiper-wrapper">';
            // $listImages = [];

            // // Kiểm tra xem có dữ liệu hình không trước khi explode
            // if (!empty($detail['hinh'])) {
            //     $arrHinh = explode(',', $detail['hinh']);

            //     foreach ($arrHinh as $img) {
            //         $listImages[] = "/project-FindU/public/uploads/images/" . htmlspecialchars(trim($img));
            //     }
            // }

            // if (!empty($listImages)) {
            //     foreach ($listImages as $imgUrl) {
            //         echo '<div class="swiper-slide">
            //                 <img src="' . $imgUrl . '" alt="User Image">
            //             </div>';
            //     }
            // }

            // echo '</div>

            //         <div class="swiper-button-next"></div>
            //         <div class="swiper-button-prev"></div>

            //         <div class="swiper-pagination"></div>
            //     </div>
            // </div>

            echo '<div class="info-box-infodetailes">
                <header class="box-infodetailes-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false" role="img">
                        <title></title>
                        <g fill="var(--color--gray-50, inherit)">
                            <path fill-rule="evenodd" d="M1.25 5.25a4 4 0 0 1 4-4h13.5a4 4 0 0 1 4 4v11a4 4 0 0 1-4 4H15.5l-3.146 3.146a.5.5 0 0 1-.708 0L8.5 20.25H5.25a4 4 0 0 1-4-4zm8.827 4.111c.399.391.876.647 1.427.763.323.069.973.048 1.303-.041a2.902 2.902 0 0 0 2.017-2.03c.1-.36.098-1.09-.003-1.447a2.881 2.881 0 0 0-1.489-1.81 2.5 2.5 0 0 0-1.265-.296c-.502 0-.811.072-1.266.297-1.748.863-2.119 3.195-.724 4.564m7.501 6.016a.617.617 0 0 0 .05-.244c0-.823-.455-1.664-1.237-2.355-1.05-.927-2.688-1.593-4.327-1.592-1.64.001-3.277.666-4.326 1.592-.782.692-1.238 1.532-1.238 2.355l.049.24.159.386c.247.6.832.991 1.481.991h7.75c.65 0 1.234-.391 1.482-.991z" clip-rule="evenodd"></path>
                        </g>
                    </svg>
                    Thông tin chính
                </header>
                <div class="box-infodetailes-content">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c1.5-4 4.5-6 8-6s6.5 2 8 6"/>
                    <circle cx="19" cy="5" r="3.2"/>
                    </svg>
                    <span>' . $detail['tuoi'] . ' tuổi</span>
                </div>
                <div class="box-infodetailes-content">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false" role="img" class="Va(tt) Sq(16px)"><title></title><g fill="var(--color--icon-secondary, inherit)"><path fill-rule="evenodd" d="M12 21.994h.034c1.918.033 3.76-.191 5.157-.631 1.492-.47 1.964-1.013 2.056-1.23.004-.026.016-.156-.076-.444-.123-.382-.383-.884-.806-1.472-.846-1.175-2.142-2.411-3.473-3.341l-1.05-.735a1 1 0 0 1-.083-1.574l.968-.84c.684-.594 1.462-1.935 1.462-5.2C16.19 4.042 14.285 2.096 12 2c-2.285.096-4.19 2.042-4.19 4.525 0 3.266.78 4.607 1.463 5.2l.968.84a1 1 0 0 1-.083 1.575l-1.05.735c-1.33.93-2.626 2.166-3.473 3.34-.423.589-.683 1.091-.806 1.473-.092.288-.08.418-.076.444.092.217.564.76 2.056 1.23 1.397.44 3.24.664 5.157.631zm9.118-1.154c-.85 2.205-4.981 3.224-9.118 3.154-4.137.07-8.268-.949-9.118-3.154-.647-1.678 1.179-4.311 3.49-6.35a18.08 18.08 0 0 1 1.59-1.254 5.075 5.075 0 0 1-1.21-1.594c-.596-1.196-.941-2.85-.941-5.116C5.81 2.982 8.566.097 12 0c3.434.097 6.19 2.982 6.19 6.526 0 2.266-.346 3.92-.943 5.116a5.073 5.073 0 0 1-1.209 1.595c.54.377 1.077.8 1.59 1.253 2.311 2.039 4.137 4.672 3.49 6.35" clip-rule="evenodd"></path></g></svg>
                    <span>' . ($detail['gioiTinh'] == 'F' ? 'Nữ' : 'Nam') . '</span>
                </div>
                <div class="box-infodetailes-content">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false" role="img" class="Va(tt) Sq(16px)"><title></title><g fill="var(--color--icon-secondary, inherit)"><path fill-rule="evenodd" d="M11.171 4.182a1.975 1.975 0 0 1 1.658 0l10.578 4.883c.79.365.79 1.505 0 1.87L20 12.507v4.903a2 2 0 0 1-1.02 1.744l-.607.341a13 13 0 0 1-12.746 0l-.608-.341A2 2 0 0 1 4 17.41v-4.902l-1-.462V14a1 1 0 1 1-2 0v-2.877l-.407-.188c-.79-.365-.79-1.505 0-1.87zm1.658 11.636 4.848-2.238H18v3.83l-.607.342a11 11 0 0 1-10.786 0L6 17.41v-3.83h.323l4.848 2.238a1.977 1.977 0 0 0 1.658 0M20.593 10 12 6.033 3.406 10 12 13.967z" clip-rule="evenodd"></path></g></svg>
                    <span>' . $detail['hocVan'] . '</span>
                </div>

                <div class="box-infodetailes-content">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false" role="img" class="Va(tt) Sq(16px)"><title></title><g fill="var(--color--icon-secondary, inherit)"><path fill-rule="evenodd" d="M2.25 10.005H3v10.5a1.5 1.5 0 0 0 1.5 1.5h15a1.5 1.5 0 0 0 1.5-1.5v-10.5h.75c.72 0 1.027-.918.45-1.35L12.9 1.68a1.5 1.5 0 0 0-1.8 0L1.8 8.655c-.577.432-.27 1.35.45 1.35M12 3.499 5.985 8.01h12.03zM4.995 20.01V10.005H19V20.01h-3.002v-5.69c0-.716-.581-1.297-1.298-1.297H9.3c-.717 0-1.297.58-1.297 1.297v5.69zm9.008 0H10l-.002-4.992h4.005z" clip-rule="evenodd"></path></g></svg>
                    <span>Sống tại ' . $detail['diaChi'] . '</span> 
                </div>
            </div>';

            echo '<div class="info-box-infodetailes">
                    <header class="box-infodetailes-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false" role="img"><title></title><g fill="var(--color--gray-50, inherit)"><path d="M1.601 11.204a.75.75 0 0 1-.656-1.114L5.4 2.072a.75.75 0 0 1 1.311 0l4.455 8.018a.75.75 0 0 1-.656 1.114zm4.455 11.458c-1.26 0-2.34-.448-3.237-1.346-.898-.897-1.347-1.976-1.347-3.237 0-1.26.449-2.34 1.347-3.237.897-.898 1.976-1.346 3.237-1.346 1.26 0 2.339.448 3.237 1.346.897.898 1.346 1.977 1.346 3.237s-.449 2.34-1.346 3.237c-.898.898-1.977 1.346-3.237 1.346m7.875 0a1 1 0 0 1-1-1v-7.166a1 1 0 0 1 1-1h7.166a1 1 0 0 1 1 1v7.166a1 1 0 0 1-1 1z"></path><path fill-rule="evenodd" d="M23.263 4.698c0-2.026-1.376-3.496-3.271-3.496-1.016 0-1.563.37-2.442 1.208-.88-.84-1.427-1.21-2.443-1.21-1.896 0-3.272 1.47-3.272 3.495 0 .85.309 1.653.875 2.288l4.587 4.123a.39.39 0 0 0 .506 0l4.39-3.917.095-.1.095-.099c.57-.635.88-1.44.88-2.292" clip-rule="evenodd"></path></g></svg>
                        Sở thích    
                    </header>
                    <div class="list-soThich">';
            $listSoThich = explode(',', $detail['soThich']);
            foreach ($listSoThich as $st) {
                $st = trim($st);
                if (!empty($st)) {
                    echo '<div class="box-soThich-info">' . $st . '</div>';
                }
            }
            echo    '</div>
                </div>';
            if (!isset($_GET['sidebar']) || $detail['maTV'] != $_SESSION['user_maTV']) {
                echo '<div class="info-box-infodetailes">
                        <a href="trangChu.php?action=block&id_bi_chan=' . $maTV_item . '" 
                            onclick="return confirm(\'Bạn có chắc chắn muốn chặn ' . $hoTen . ' không?\')" 
                            class="btn_infodetailes" 
                            style="text-decoration: none; display: inline-block; text-align: center;">
                            Chặn ' . $hoTen . '
                        </a>
                    </div>';
            }
        }
        echo '</div>';

        // 1. Xử lý dữ liệu sở thích để checked đúng ô
        $myHobbies = !empty($detail['soThich']) ? array_map('trim', explode(',', $detail['soThich'])) : [];

        // Danh sách tất cả sở thích (Bạn có thể thêm nhiều hơn)
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

        echo '
                <div id="editProfileModal" class="modal-overlay" style="display:none;">
                    <div class="modal-content tinder-style">
                        <div class="main-modal-content">
                            <div class="modal-header">
                                <h3>Sửa hồ sơ</h3>
                                <span class="close-modal" onclick="closeEditModal()">&times;</span>
                            </div>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="maTV" value="' . $maTV_item . '">
                                
                                <div class="modal-body-scroll">
                                    <div class="section-title">Ảnh hồ sơ</div>
                                    <div class="media-grid">
                                        <div class="media-box main-photo">
                                            <img src="' . $avatarUrl . '" id="previewAvatar" class="img-cover">
                                            <input type="hidden" name="existingAvatar" value="' . $avatarSafe . '">
                                            <label for="uploadAvatarProfile" class="btn-add-photo">
                                                <svg focusable="false" aria-hidden="true" role="presentation" viewBox="0 0 24 24" width="24px" height="24px" class="Sq(16px)"><path class="Fill($c-ds-text-secondary)" d="M17.079 2c-.41 0-.81.158-1.125.463l-2.23 2.229 5.574 5.583 2.229-2.208c.63-.641.63-1.64 0-2.25l-3.334-3.354A1.605 1.605 0 0 0 17.08 2m-4.101 3.438L4.46 13.966l2.691.295.19 2.408 2.397.179.305 2.691 8.518-8.527M3.84 14.944L2 21.98l7.045-1.882-.252-2.272-2.43-.178-.188-2.44"></path></svg>
                                            </label>
                                            <input type="file" name="uploadAvatarProfile" id="uploadAvatarProfile" accept="image/*" hidden onchange="previewImage(this, \'previewAvatar\')">
                                            <span class="photo-tag">Avatar</span>
                                        </div>

                                        <div class="media-box sub-photo">
                                            <div class="placeholder-img">+</div>
                                            <input type="file" name="photos[]" accept="image/*" class="file-overlay">
                                        </div>
                                        <div class="media-box sub-photo">
                                            <div class="placeholder-img">+</div>
                                            <input type="file" name="photos[]" accept="image/*" class="file-overlay">
                                        </div>
                                        <div class="media-box sub-photo">
                                            <div class="placeholder-img">+</div>
                                            <input type="file" name="photos[]" accept="image/*" class="file-overlay">
                                        </div>
                                        <div class="media-box sub-photo">
                                            <div class="placeholder-img">+</div>
                                            <input type="file" name="photos[]" accept="image/*" class="file-overlay">
                                        </div>
                                    </div>

                                    <div class="section-title">Thông tin</div>
                                    <div class="tinder-input-group">
                                        <label>Họ và tên</label>
                                        <input type="text" name="profile_hoTen" value="' . $hoTen . '" required placeholder="Nhập tên của bạn">
                                    </div>

                                    <div class="tinder-input-group">
                                        <label>Tuổi</label>
                                        <input type="number" min="18" name="profile_tuoi" value="' . $tuoi_item . '" required placeholder="Nhập tuổi của bạn">
                                    </div>

                                    <div class="tinder-input-group">
                                        <label>Công việc / Học vấn</label>
                                        <input type="text" name="profile_hocVan" value="' . $hocVan . '" placeholder="Thêm công việc/trường học">
                                    </div>

                                    <div class="tinder-input-group">
                                        <label>Đang sống tại</label>
                                        <input type="text" name="profile_diaChi" value="' . htmlspecialchars($detail['diaChi'] ?? '') . '" placeholder="Thêm thành phố">
                                    </div>
                                    
                                    <div class="tinder-input-group">
                                        <label>Giới thiệu (Bio)</label>
                                        <textarea name="profile_bio" rows="3" placeholder="Hãy viết gì đó thú vị về bạn...">' . htmlspecialchars($detail['bio'] ?? '') . '</textarea>
                                    </div>

                                    <div class="section-title">Sở thích</div>
                                    <p class="sub-text">Chọn sở thích để mọi người hiểu rõ hơn về bạn</p>
                                    
                                    <div class="tags-container">';
        foreach ($allHobbies as $hobby) {
            $checked = in_array($hobby, $myHobbies) ? 'checked' : '';
            echo '
                                            <label class="tag-item">
                                                <input type="checkbox" name="profile_soThich[]" value="' . $hobby . '" ' . $checked . '>
                                                <span class="tag-content">' . $hobby . '</span>
                                            </label>';
        }
        echo '                      </div>
                                </div> 
                                <div class="modal-footer sticky-footer">
                                    <button type="submit" name="btnUpdateInfo" class="btn-tinder-save">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
        echo '
                <div id="reportModal" class="modal-overlay" style="display:none; z-index: 9999;">
                    <div class="modal-content tinder-style" style="max-width: 450px; border-radius: 16px; overflow: hidden;">
                        <div class="main-modal-content">
                            
                            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 15px 20px;">
                                <h3>Báo cáo ' . $hoTen . '</h3>
                                <span class="close-modal" onclick="closeReportModal()" style="font-size: 24px; color: #999;">&times;</span>
                            </div>
                            
                            <form action="" method="POST">
                                <input type="hidden" name="id_bi_bao_cao" value="' . $maTV_item . '">
                                <input type="hidden" name="ten_bi_bao_cao" value="' . $hoTen . '">
                                
                                <div class="modal-body-scroll" style="padding: 20px;">
                                    <p style="color: #666; margin-bottom: 20px; font-size: 15px;">
                                        Hãy chọn lý do bạn muốn báo cáo hồ sơ này. Chúng tôi sẽ giữ bí mật thông tin của bạn.
                                    </p>
                                    
                                    <div class="report-reason-list">
                                        <label class="reason-item">
                                            <input type="radio" name="reason" value="quayroi" required>
                                            <span class="reason-content">Quấy rối / Tin nhắn thô lỗ</span>
                                        </label>
                                        
                                        <label class="reason-item">
                                            <input type="radio" name="reason" value="giamao">
                                            <span class="reason-content">Tài khoản giả mạo / Lừa đảo</span>
                                        </label>
                                        
                                        <label class="reason-item">
                                            <input type="radio" name="reason" value="nhaycam">
                                            <span class="reason-content">Ảnh hoặc nội dung không phù hợp</span>
                                        </label>
                                        
                                        <label class="reason-item">
                                            <input type="radio" name="reason" value="spam">
                                            <span class="reason-content">Spam / Quảng cáo bán hàng</span>
                                        </label>

                                        <label class="reason-item">
                                            <input type="radio" name="reason" value="khac">
                                            <span class="reason-content">Lý do khác</span>
                                        </label>
                                    </div>

                                    <textarea name="additional_info" class="custom-textarea" rows="3" placeholder="Chia sẻ thêm chi tiết (nếu có)..."></textarea>
                                </div>
                                
                                <div class="modal-footer sticky-footer" style="padding: 15px 20px; border-top: 1px solid #f0f0f0;">
                                    <button type="submit" name="btnSendReport" class="btn-tinder-save" 
                                            style="background: linear-gradient(260deg, #ff6b6b 0%, #ff4458 100%); width: 100%; border-radius: 99px; font-weight: 700;">
                                        Gửi báo cáo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
    }

    public function capNhatHoSo($maTV, $anhDaiDien, $hinh, $hoTen, $hocVan, $diaChi, $bio, $soThich, $tuoi)
    {
        $check_avatar = null;
        $check_hinh = null;

        if (!empty($anhDaiDien['name'])) {
            $check_avatar = $this->handleUploadFile_avatar($anhDaiDien);
            if ($check_avatar === false) {
                echo $this->helper->message('error', 'Ảnh đại diện không hợp lệ.');
                return;
            }
        }

        if (!empty($hinh['name'][0])) {
            $check_hinh = $this->handleUploadFiles($hinh);
            if ($check_hinh === false) {
                echo $this->helper->message('error', 'Bộ sưu tập ảnh không hợp lệ.');
                return;
            }
        }

        $avatarString = isset($check_avatar['images'][0]) ? $check_avatar['images'][0] : $_POST['existingAvatar'];
        $listHinh = isset($check_hinh['images']) ? implode(',', $check_hinh['images']) : null;
        $listSoThich = !empty($soThich) ? implode(',', $soThich) : null;

        $thanhvien = new thanhVienModel(
            $hoTen,
            $tuoi,
            null,
            null,
            null,
            $avatarString,
            $diaChi,
            $listSoThich,
            'hoatdong',
            null,
            null,
            null,
            $hocVan,
            $listHinh,
            $bio
        );

        if ($this->repo->update_profile($maTV, $thanhvien)) {
            $this->refreshUserSession($maTV);
            echo $this->helper->message('success', 'Cập nhật hồ sơ thành công');
        } else {
            echo $this->helper->message('error', 'Cập nhật hồ sơ thất bại, vui lòng thử lại sau');
        }
    }



    public function gui_ghepDoi($maTV_gui, $maTV_nhan, $trangThai)
    {
        return $this->repo->add_guiGhepDoi($maTV_gui, $maTV_nhan, $trangThai);
    }

    public function loiMoi_ghepDoi_ganNhat($maTV, $trangThai)
    {
        return $this->repo->find_moighepDoi_ganNhat($maTV, $trangThai);
    }

    public function list_LoiMoiById($maTV, $trangThai)
    {
        $list = $this->repo->findAll_tuongHop_ById($maTV, $trangThai);
        foreach ($list as $tv) {
            if ($this->repo->check_capDoi($maTV, $tv['maTV']) == true) {
                continue; // Bỏ qua nếu là cặp đôi
            }
            echo '<div class="capDoi-card">
                    <div class="capDoi-card-avatar">
                        <a href="/project-FindU/app/views/user/hoSo.php?id_profile=' . $tv['maTV'] . '"><img src="/project-FindU/public/uploads/avatars/' . $tv['anhDaiDien'] . '" alt=""></a>
                    </div>  
                    <div class="capDoi-card-info">
                        <p>' . $tv['hoTen'] . '</p>
                    </div> 
                    <form method="post" class="tuongHop-card-frm">
                        <div class="controls" style="width:100%;">
                            <button class="btn" id="noBtn" title="Nope" name="btn-nope-tuonghop" value="' . $tv['maTV'] . '">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" focusable="false" role="img">
                                    <defs>
                                    <linearGradient id="gradNope" x1="0%" y1="20%" x2="70%" y2="100%">
                                        <stop offset="0%" stop-color="#ff1bf8" />
                                        <stop offset="100%" stop-color="#ff2732" />
                                    </linearGradient>
                                    </defs>  
                                    <title>Nope</title>
                                    <g fill="url(#gradNope)">
                                        <path d="M21.974 4.171 19.97 2.17a.94.94 0 0 0-1.331 0L12 8.809l-6.64-6.64a.94.94 0 0 0-1.331 0L2.026 4.17a.94.94 0 0 0 0 1.332l6.64 6.64-6.64 6.63a.94.94 0 0 0 0 1.332l2.003 2.002a.94.94 0 0 0 1.331 0l6.64-6.64 6.64 6.64a.94.94 0 0 0 1.331 0l2.003-2.002a.94.94 0 0 0 0-1.332l-6.64-6.63 6.64-6.64a.94.94 0 0 0 0-1.332"></path>
                                    </g>
                                </svg>
                            </button>

                            <button class="btn" id="starBtn" title="Super Like" name="btn-superlike-tuonghop" value="' . $tv['maTV'] . '">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" focusable="false" role="img">
                                <defs>
                                    <linearGradient id="gradSuperLike" x1="10%" y1="20%" x2="80%" y2="100%">
                                        <stop offset="0%" stop-color="#00daff" />
                                        <stop offset="100%" stop-color="#0077ff" />
                                    </linearGradient>
                                </defs>
                                <title>Super Like</title>
                                    <g fill="url(#gradSuperLike)">
                                        <path d="M16.296 8.04a.995.995 0 0 1-.89-.65 40.694 40.694 0 0 0-2.99-6.15.505.505 0 0 0-.86 0 40.73 40.73 0 0 0-3 6.16c-.14.37-.49.63-.89.65-3 .16-5.55.66-6.78.94-.37.08-.51.53-.26.81.83.95 2.59 2.86 4.93 4.75.31.25.44.66.34 1.05-.78 2.9-1.08 5.48-1.2 6.74-.03.37.35.65.69.5 1.16-.5 3.52-1.58 6.05-3.22a1 1 0 0 1 1.1 0c2.52 1.64 4.88 2.72 6.04 3.22.35.15.73-.13.69-.5-.11-1.26-.42-3.84-1.2-6.75-.1-.38.03-.8.34-1.05 2.34-1.89 4.1-3.8 4.93-4.75.25-.28.1-.73-.26-.81 0 0-3.77-.79-6.78-.94"></path>
                                    </g>
                                </svg>
                            </button>

                            <button class="btn" id="likeBtn" title="Like" name="btn-like-tuonghop" value="' . $tv['maTV'] . '">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon"viewBox="0 0 24 24" focusable="false" role="img">
                                    <title>Like</title>
                                    <defs>
                                        <linearGradient id="gradLike" x1="10%" y1="20%" x2="80%" y2="100%">
                                            <stop offset="0%" stop-color="#e9f208" />
                                            <stop offset="100%" stop-color="#35b951" />
                                        </linearGradient>
                                    </defs>
                                    <g fill="url(#gradLike)">
                                        <path d="M17.506 2c-.556 0-1.122.075-1.7.225-1.636.438-3.015 1.625-3.795 3.132-.78-1.518-2.16-2.705-3.796-3.132A6.757 6.757 0 0 0 6.515 2C3.062 2 .25 4.833.25 8.33c0 .138 0 .299.021.47.129 1.454.642 2.822 1.39 4.063 1.273 2.085 4.149 6.04 9.601 10.092.214.16.481.246.738.246s.524-.075.738-.246c5.452-4.052 8.328-8.007 9.6-10.092.76-1.24 1.273-2.62 1.39-4.063.011-.171.022-.332.022-.47 0-3.497-2.801-6.33-6.265-6.33z"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>';
        }
    }

    // thanhvien_capDoi

    public function list_capDoi($maTV)
    {
        $list =  $this->repo->findAll_CapDoi($maTV);
        foreach ($list as $tv) {
            echo '<div class="capDoi-card">
                    <div class="capDoi-card-avatar">
                        <a href="/project-FindU/app/views/user/hoSo.php?id_profile=' . $tv['maTV'] . '"><img src="/project-FindU/public/uploads/avatars/' . $tv['anhDaiDien'] . '" alt=""></a>
                    </div>  
                    <div class="capDoi-card-info">
                        <p>' . $tv['hoTen'] . '</p>
                    </div> 
                    <form method="post" class="capDoi-card-frm">
                        <button type="submit" name="btn_huyGhepDoi" value="' . $tv['maTV'] . '">Hủy ghép đôi</button>
                    </form>
                </div>';
        }
    }

    public function huyGhepDoi($maTV_huy, $maTV_muonHuy)
    {
        if ($this->repo->deleteCapDoi($maTV_huy, $maTV_muonHuy) && $this->repo->deleteGhepDoi($maTV_huy, $maTV_muonHuy)) {
            echo $this->helper->message('success', 'Hủy ghép đôi thành công');
        } else {
            echo $this->helper->message('error', 'Hủy ghép đôi thất bại');
        }
    }

    public function check_capDoi($maTV1, $maTV2)
    {
        return $this->repo->check_capDoi($maTV1, $maTV2);
    }

    // thanhvien_cuoctrochuyen
    public function list_cuocTroChuyen($maTV)
    {
        return $this->repo->findAll_cuocTroChuyen($maTV);
    }

    public function box_chat($maCTC)
    {
        return $this->repo->findOne_cuocTroChuyen($maCTC);
    }
    // thanhvien_tinnhan

    public function sendMessage($maCTC, $user_maTV, $maTV_chat, $message, $files)
    {
        $filesCheck = $this->handleUploadFiles($files);
        if ($filesCheck === false) {
            echo $this->helper->message('error', 'Tệp tin tải lên không hợp lệ. Vui lòng chọn tệp tin hình ảnh hoặc video.');
            return;
        }

        $encryptedMessage = !empty($message) ? SecurityHelper::encrypt($message) : $message;
        return $this->repo->addMessage($maCTC, $user_maTV, $maTV_chat, $encryptedMessage, $filesCheck);
    }

    public function listMessage($maCTC)
    {
        return $this->repo->findAll_message($maCTC);
    }

    public function check_Chat($maTV1_user, $maTV_chat)
    {
        return $this->repo->check_capDoi($maTV1_user, $maTV_chat);
    }

    public function baoCaoThanhVien($nguoi_bao_cao_id, $nguoi_bi_bao_cao_id, $ten_nguoi_bi_bao_cao, $ly_do, $chi_tiet)
    {
        // 1. Validate dữ liệu cơ bản
        if (empty($nguoi_bi_bao_cao_id) || empty($ly_do)) {
            echo $this->helper->message('error', 'Dữ liệu không hợp lệ.');
            return;
        }

        // 2. Chuẩn bị nội dung mô tả
        // Vì bảng DB chỉ có cột 'moTa', ta gộp tên và chi tiết vào đây cho dễ đọc
        $noi_dung_day_du = "Tên người bị báo cáo: " . $ten_nguoi_bi_bao_cao . ". \n";
        $noi_dung_day_du .= "Chi tiết: " . $chi_tiet;

        // 3. Gọi Repository để lưu vào DB
        // Truyền: Người báo cáo, Người bị báo cáo, Lý do (ENUM), Mô tả chi tiết
        $result = $this->repo->add_baoCaoThanhVien($nguoi_bao_cao_id, $nguoi_bi_bao_cao_id, $ly_do, $noi_dung_day_du);

        // 4. Phản hồi ra giao diện
        if ($result) {
            // Có thể redirect hoặc reload lại trang để tránh gửi lại form khi F5
            echo $this->helper->message('success', 'Báo cáo đã được gửi thành công. Cảm ơn bạn đã giúp chúng tôi giữ cộng đồng an toàn.');
            // header("Refresh:2"); // Tự động reload sau 2s nếu cần
        } else {
            echo $this->helper->message('error', 'Gửi báo cáo thất bại, vui lòng thử lại sau.');
        }
    }
}
