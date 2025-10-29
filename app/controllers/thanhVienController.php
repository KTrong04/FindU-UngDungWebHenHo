<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../repositories/thanhVienRepository.php';
require_once __DIR__ . '/../helpers/thanhVienHelper.php';

class thanhVienController
{
    private $repo;
    private $helper;

    public function __construct()
    {
        $this->repo = new thanhVienRepository();
        $this->helper = new thanhVienHelper();
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
                echo "Email này đã được sử dụng. Vui lòng chọn email khác!";
                return;
            }
            echo "Đăng ký thành công! Chuyển hướng đến trang đăng nhập...";
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
                session_start();
                $_SESSION['user_maTV'] = $user['maTV'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['hoTen'];
                echo "Đăng nhập thành công! Chuyển hướng đến trang chủ...";
                header("refresh: 2, url=/project-FindU/app/views/user/trangChu.php");
            } else {
                echo "Email hoặc mật khẩu không đúng!";
            }
        }
    }

    public function configLogin()
    {
        session_start();
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

    // person
    public function dangBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $files)
    {
        $log_validateInput = $this->helper->validateInputDangBaiViet($noiDung, $hashtag, $quyenXem, $files);
        if ($log_validateInput !== true) {
            echo $log_validateInput;
        } else {
            $filesCheck = $this->handleUploadFiles($files);
            if ($filesCheck === false) {
                echo "Tệp tin tải lên không hợp lệ. Vui lòng chọn tệp tin hình ảnh hoặc video.";
                return;
            }

            $bv = $this->repo->createBaiViet($maTV, $noiDung, $hashtag, $quyenXem, $filesCheck);
            if ($bv !== true) {
                echo "Đăng bài viết thất bại! Vui lòng thử lại.";
            } else {
                echo "Đăng bài viết thành công! Bài viết đang chờ duyệt.";
                header("Location: /project-FindU/app/views/user/");
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

        foreach ($files['name'] as $key => $name) {
            $tmpName = $files['tmp_name'][$key];
            $error = $files['error'][$key];

            if ($error === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                // Kiểm tra loại file
                if (in_array($ext, $imageExtensions)) {
                    $newName = 'img_' . uniqid(). time() . '.' . $ext;
                    move_uploaded_file($tmpName, $uploadPath . '/images/' . $newName);
                    $images[] = $newName;
                } elseif (in_array($ext, $videoExtensions)) {
                    $newName = 'video_' . uniqid() . time() . '.' . $ext;
                    
                    move_uploaded_file($tmpName,$uploadPath . '/videos/' . $newName);
                    $videos[] = $newName;
                } else {
                    // Nếu có file không hợp lệ => hủy tất cả
                    return false;
                }
            }
        }

        return [
            'images' => $images,
            'videos' => $videos
        ];
    }

    // Hiển thị bài viết
    public function hienThiBaiViet()
    {
        
        $user_maTV = $_SESSION['user_maTV'];
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/Project-FindU/app/views/includes/baiViet.php';
        foreach ($this->repo->ReadAll_BaiViet($user_maTV) as $bv) {
            
            include $uploadPath;
        }
    }
    //doi mat khau 
    public function doiMatKhau($matKhauCu, $matKhauMoi, $nhapLai)
    {
    // Bắt đầu session an toàn
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // ✅ Kiểm tra người dùng đã đăng nhập chưa
    if (!isset($_SESSION['user_maTV'])) {
        return ['success' => false, 'message' => "Vui lòng đăng nhập lại."];
    }

    $maTV = $_SESSION['user_maTV'];

    // ✅ Gọi model
    require_once __DIR__ . '/../models/thanhVienModel.php';
    $model = new thanhVienModel();

    // ✅ Lấy mật khẩu hiện tại từ DB
    $matKhauHienTai = $model->layMatKhau($maTV);
    if (!$matKhauHienTai) {
        return ['success' => false, 'message' => "Không tìm thấy thông tin thành viên."];
    }

    // ✅ Kiểm tra mật khẩu cũ có đúng không
    if (!password_verify($matKhauCu, $matKhauHienTai)) {
        return ['success' => false, 'message' => "Mật khẩu cũ không đúng."];
    }

    // ✅ Nếu mật khẩu mới giống mật khẩu cũ → không cho phép
    if (password_verify($matKhauMoi, $matKhauHienTai)) {
        return ['success' => false, 'message' => "Mật khẩu mới không được trùng với mật khẩu cũ."];
    }

    // ✅ Kiểm tra độ dài và độ mạnh của mật khẩu mới
    if (strlen($matKhauMoi) < 8) {
        return ['success' => false, 'message' => "Mật khẩu mới phải có ít nhất 8 ký tự."];
    }

    // ✅ Kiểm tra bắt buộc có chữ hoa, số, ký tự đặc biệt
    $pattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/';
    if (!preg_match($pattern, $matKhauMoi)) {
        return ['success' => false, 'message' => "Mật khẩu mới phải bao gồm ít nhất 1 chữ hoa, 1 số và 1 ký tự đặc biệt."];
    }

    // ✅ Kiểm tra xác nhận mật khẩu
    if ($matKhauMoi !== $nhapLai) {
        return ['success' => false, 'message' => "Xác nhận mật khẩu không khớp."];
    }

    // ✅ Hash mật khẩu mới và cập nhật DB
    $matKhauMoiHash = password_hash($matKhauMoi, PASSWORD_BCRYPT);
    $ketQua = $model->capNhatMatKhau($maTV, $matKhauMoiHash);

    if ($ketQua) {
        return ['success' => true, 'message' => "Đổi mật khẩu thành công!"];
    } else {
        return ['success' => false, 'message' => "Có lỗi khi cập nhật mật khẩu."];
        }
    }

    public function dangXuat()
    {
        // Bắt đầu session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nếu tồn tại session đăng nhập thì xóa
        if (isset($_SESSION['user_maTV'])) {
            // Xóa tất cả biến trong session
            $_SESSION = [];

            // Xóa cookie lưu session (nếu có)
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            // Hủy session
            session_destroy();
        }

        // Chuyển hướng về trang đăng nhập
        header("Location: /project-FindU/app/views/user/");
        exit;
    }



}