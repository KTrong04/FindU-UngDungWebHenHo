<?php
require_once __DIR__ . '/../../controllers/thanhVienController.php';
$tv = new thanhVienController();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindU - Signup</title>
    <link rel="stylesheet" href="/project-FindU/public/assets/css/acount.css">

</head>

<body>
    <?php include('../includes/header_acount.php'); ?>
    <div class="content">
        <div class="box-acount">
            <form action="dangKy.php" method="post" id="frm-acount" onsubmit="return validateForm()">
                <h1 class="hd-frm-acount">Đăng ký</h1>
                <p>
                    <label for="fullname">Họ và tên</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên">
                </p>

                <nav>
                    <div class="box-nav-left">
                        <label for="age">Tuổi</label>
                        <input type="number" min=18 id="age" name="age" placeholder="Nhập số tuổi">
                    </div>
                    <div class="box-nav-right">
                        <label for="">Giới tính</label>
                        <section>
                            <div class="box-radio">
                                <label for="Nam">Nam</label>
                                <input type="radio" id="Nam" name="sex" value="M">
                            </div>
                            <div class="box-radio">
                                <label for="Nu">Nữ</label>
                                <input type="radio" id="Nu" name="sex" value="F">
                            </div>
                        </section>
                    </div>
                </nav>
                <p>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Nhập email">
                </p>
                <p>
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
                </p>
                <p>
                    <label for="confirm-password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Xác nhận mật khẩu">
                </p>
                <p class="box-btn-acount">
                    <button type="submit" value="Đăng ký" name="btn_dangKy" class="btn-acount">Đăng ký</button>
                </p>
            </form>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['btn_dangKy'] === "Đăng ký") {
            $hoTen = $_POST['fullname'] ?? '';
            $tuoi = $_POST['age'] ?? '';
            $gioiTinh = $_POST['sex'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $repassword = $_POST['confirm-password'] ?? '';

            $tv->dangKy($hoTen, $tuoi, $gioiTinh, $email, $password, $repassword);
        }
    }
    ?>

    <?php include('../includes/footer.php'); ?>
</body>

</html>

<script src="/project-FindU/public/assets/js/thongBao.js"></script>
<script>
    // Hàm tiện ích: Hiển thị lỗi và focus vào ô input
    function showError(message, id) {
        alert(message);
        const element = document.getElementById(id);
        if (element) {
            element.focus();
            if (id === 'confirm-password') element.value = ""; // Xóa nếu là ô xác nhận
        }
        return false;
    }

    function validateForm() {
        // 1. Lấy giá trị (Dùng const/let thay vì var cho chuẩn ES6)
        const fullname = document.getElementById("fullname").value.trim();
        const age = document.getElementById("age").value;
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        const sex = document.querySelector('input[name="sex"]:checked');

        // --- VALIDATE HỌ TÊN ---
        if (fullname === "") return showError("Vui lòng nhập họ và tên.", "fullname");

        // --- VALIDATE TUỔI ---
        if (age === "" || isNaN(age)) return showError("Vui lòng nhập tuổi hợp lệ.", "age");
        if (parseInt(age) < 18) return showError("Bạn phải từ 18 tuổi trở lên để đăng ký.", "age");

        // --- VALIDATE GIỚI TÍNH ---
        if (!sex) {
            alert("Vui lòng chọn giới tính.");
            return false;
        }

        // --- VALIDATE EMAIL ---
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (email === "") return showError("Vui lòng nhập email.", "email");
        if (!emailPattern.test(email)) return showError("Email không hợp lệ. Vui lòng kiểm tra lại.", "email");

        // --- VALIDATE MẬT KHẨU (Chi tiết & Thông minh) ---
        if (password === "") return showError("Vui lòng nhập mật khẩu.", "password");

        // Check độ dài
        if (password.length < 8) return showError("Mật khẩu phải có ít nhất 8 ký tự.", "password");

        // Check chữ in HOA
        if (!/[A-Z]/.test(password)) return showError("Mật khẩu phải chứa ít nhất một chữ IN HOA.", "password");

        // Check chữ thường
        if (!/[a-z]/.test(password)) return showError("Mật khẩu phải chứa ít nhất một chữ in thường.", "password");

        // Check số (Nên có)
        if (!/[0-9]/.test(password)) return showError("Mật khẩu phải chứa ít nhất một chữ số.", "password");

        // Check KÝ TỰ ĐẶC BIỆT (Mới thêm)
        // Các ký tự: ! @ # $ % ^ & * ( ) , . ? " : { } | < >
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            return showError("Mật khẩu phải chứa ít nhất một ký tự đặc biệt (VD: @, #, !, ...).", "password");
        }

        // --- VALIDATE XÁC NHẬN MẬT KHẨU ---
        // Chỉ cần check không rỗng và phải khớp với password
        if (confirmPassword === "") return showError("Vui lòng xác nhận mật khẩu.", "confirm-password");

        if (password !== confirmPassword) {
            return showError("Mật khẩu xác nhận không khớp. Vui lòng nhập lại.", "confirm-password");
        }

        // Nếu tất cả OK
        return true;
    }
</script>