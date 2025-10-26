document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("frm-acount");

    // Lấy tất cả input cần kiểm tra
    const fields = {
        fullname: {
            validate: (v) => v.trim().length >= 3,
            message: "Họ tên phải có ít nhất 3 ký tự."
        },
        age: {
            validate: (v) => v.trim() !== "" && !isNaN(v) && Number(v) >= 18,
            message: "Tuổi không hợp lệ (phải >= 18)."
        },
        email: {
            validate: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
            message: "Email không hợp lệ."
        },
        password: {
            validate: (v) =>
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/.test(v.trim()),
            message:
                "Mật khẩu phải ≥ 12 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt."
        },
        "confirm-password": {
            validate: (v) => v.trim() === document.getElementById("password").value.trim(),
            message: "Mật khẩu nhập lại không khớp."
        }
    };

    // Kiểm tra giới tính riêng
    const sexInputs = document.getElementsByName("sex");
    const checkSex = () => Array.from(sexInputs).some((input) => input.checked);

    // Xóa lỗi cũ khi người dùng bắt đầu gõ lại
    Object.keys(fields).forEach((id) => {
        const input = document.getElementById(id);
        input.addEventListener("input", () => validateField(id, false));
    });
    sexInputs.forEach((input) => input.addEventListener("change", () => validateSex(false)));

    // Khi submit form
    form.addEventListener("submit", (e) => {
        let valid = true;

        // Kiểm tra từng trường
        for (const id in fields) {
            if (!validateField(id, true)) valid = false;
        }

        // Kiểm tra giới tính
        if (!validateSex(true)) valid = false;

        if (!valid) e.preventDefault(); // Ngăn form gửi đi nếu có lỗi
    });

    // ======== Hàm kiểm tra 1 trường ========
    function validateField(id, showInstant = true) {
        const input = document.getElementById(id);
        const value = input.value.trim();
        const { validate, message } = fields[id];

        const isValid = value !== "" && validate(value);
        const span = getMessageSpan(input);

        if (value === "") {
            showError(input, span, "Trường này là bắt buộc.", showInstant);
            return false;
        } else if (!isValid) {
            showError(input, span, message, showInstant);
            return false;
        } else {
            showSuccess(input, span);
            return true;
        }
    }

    // ======== Kiểm tra giới tính ========
    function validateSex(showInstant = true) {
        const container = sexInputs[0].closest(".box-nav-right");
        let span = container.querySelector(".error-msg");
        if (!checkSex()) {
            if (!span) {
                span = document.createElement("span");
                span.classList.add("error-msg");
                container.appendChild(span);
            }
            if (showInstant) span.textContent = "Vui lòng chọn giới tính.";
            container.classList.add("error");
            return false;
        } else {
            if (span) span.remove();
            container.classList.remove("error");
            return true;
        }
    }

    // ======== Hiển thị lỗi / thành công ========
    function showError(input, span, msg, showInstant) {
        if (showInstant) span.textContent = msg;
        input.classList.remove("valid");
        span.classList.add("show");
        input.classList.add("invalid");
        input.classList.remove("valid");
    }

    function showSuccess(input, span) {
        span.textContent = "✔";
        span.classList.remove("error-msg");
        span.classList.add("success-msg");
        input.classList.remove("invalid");
        input.classList.add("valid");
    }

    // ======== Lấy hoặc tạo span báo lỗi ========
    function getMessageSpan(input) {
        let span = input.parentNode.querySelector(".error-msg, .success-msg");
        if (!span) {
            span = document.createElement("span");
            span.classList.add("error-msg");
            input.parentNode.appendChild(span);
        }
        return span;
    }
});
