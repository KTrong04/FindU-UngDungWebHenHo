
// --- 1. Xử lý Đóng/Mở Modal ---

function openEditModal() {
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.style.display = 'flex';
        // Animation nhẹ (nếu muốn JS hỗ trợ thêm CSS)
        modal.querySelector('.modal-content').style.animation = 'slideDown 0.3s ease';
    }
}

function closeEditModal() {
    document.getElementById('editProfileModal').style.display = 'none';
}

// Đóng khi click ra vùng đen bên ngoài
window.onclick = function (event) {
    var modal = document.getElementById('editProfileModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// --- 2. Preview Avatar Chính (Dùng cho nút Cây bút) ---

function previewImage(input, imgId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById(imgId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// --- 3. Preview Grid Ảnh Phụ (Logic giống Tinder) ---

document.addEventListener("DOMContentLoaded", function () {
    // Lấy tất cả các ô input trong grid ảnh phụ
    const subPhotoInputs = document.querySelectorAll('.media-box.sub-photo .file-overlay');

    subPhotoInputs.forEach(input => {
        input.addEventListener('change', function (e) {
            if (this.files && this.files[0]) {
                let parentBox = this.parentElement;
                let reader = new FileReader();

                reader.onload = function (e) {
                    // a. Ẩn dấu cộng placeholder
                    let placeholder = parentBox.querySelector('.placeholder-img');
                    if (placeholder) placeholder.style.display = 'none';

                    // b. Kiểm tra xem đã có ảnh chưa
                    let existingImg = parentBox.querySelector('img.img-cover');

                    if (existingImg) {
                        // Nếu có rồi thì chỉ thay src
                        existingImg.src = e.target.result;
                    } else {
                        // Nếu chưa thì tạo thẻ img mới
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-cover'); // Class CSS fit ảnh

                        // Chèn ảnh vào trước thẻ input (để input vẫn nằm trên cùng bắt sự kiện click)
                        parentBox.insertBefore(img, input);
                    }

                    // (Tuỳ chọn) Thêm class để đổi border style nếu muốn
                    parentBox.classList.add('has-image');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});
