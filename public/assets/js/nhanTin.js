// Biến toàn cục lưu ID tin nhắn cuối cùng
var lastMessageID = 0;
var maCTC_global; // Lưu lại để dùng cho interval

// Load toàn bộ khung chat lần đầu
function loadChat(maCTC, maTV_chat) {
    // Reset lại ID khi chuyển box chat khác
    lastMessageID = 0;

    fetch(`/project-FindU/app/controllers/thanhVien_AJAX.php?maCTC=${maCTC}&maTV_chat=${maTV_chat}`)
        .then(res => res.text())
        .then(html => {
            window.maCTC_global = maCTC;
            document.getElementById('content').innerHTML = html;

            // Sau khi load xong, cập nhật ngay ID tin nhắn cuối cùng
            updateLastID();

            scrollToBottom();
        })
        .catch(err => console.error(err));
}

// Hàm phụ: Tìm tin nhắn cuối cùng trong danh sách để lấy ID
function updateLastID() {
    let msgList = document.getElementById('message_list');
    if (!msgList) return;

    let lastMsg = msgList.lastElementChild;
    if (lastMsg && lastMsg.getAttribute('data-id')) {
        // Cập nhật biến toàn cục
        lastMessageID = parseInt(lastMsg.getAttribute('data-id'));
    }
}

// Hàm phụ: Hàm cuộn xuống đáy
function scrollToBottom() {
    const box = document.getElementById("message_list");
    if (box) box.scrollTop = box.scrollHeight;
}

// Load nội dung tin nhắn (Polling - Chỉ lấy tin mới)
function loadMessages(maCTC) {
    let msgList = document.getElementById('message_list');
    if (!msgList) return;

    // Kiểm tra người dùng có đang ở dưới cùng không
    let isAtBottom = (msgList.scrollHeight - msgList.scrollTop <= msgList.clientHeight + 100);

    // Gửi kèm last_id lên server
    fetch(`/project-FindU/app/controllers/thanhVien_AJAX.php?loadMessage=${maCTC}&last_id=${lastMessageID}&t=${new Date().getTime()}`)
        .then(res => res.text())
        .then(html => {
            // Nếu có nội dung mới (Server trả về tin nhắn có ID > lastMessageID)
            if (html.trim() !== "") {
                // KỸ THUẬT QUAN TRỌNG: Append (nối đuôi) vào cuối, KHÔNG thay thế toàn bộ
                msgList.insertAdjacentHTML('beforeend', html);

                // Cập nhật lại ID mới nhất
                updateLastID();

                // Logic tự động cuộn:
                // 1. Nếu người dùng đang xem ở đáy -> cuộn xuống
                // 2. Hoặc nếu tin nhắn đó là của "msg-me" (mình vừa gửi xong) -> cuộn xuống
                // (Kiểm tra class trong html trả về đơn giản bằng string includes)
                if (isAtBottom || html.includes("msg-me")) {
                    scrollToBottom();
                }
            }
        })
        .catch(err => console.error("Lỗi load tin nhắn:", err));
}

// Gửi tin nhắn
function sendMessage(maCTC, maTV_chat) {
    let txtInput = document.getElementById('txt_chat');
    let fileInput = document.getElementById('txt_file'); // 1. Lấy thẻ input file

    let message = txtInput.value.trim();
    let files = fileInput.files; // 2. Lấy danh sách các file đã chọn

    // Kiểm tra: Nếu không có tin nhắn TEXT và cũng không có FILE nào thì không gửi
    if (message === "" && files.length === 0) return;

    let formData = new FormData();
    formData.append("maCTC_send", maCTC);
    formData.append("maTV_chat_send", maTV_chat);
    formData.append("message", message);

    // 3. Duyệt qua từng file và đưa vào FormData
    // Tên 'file[]' phải khớp với name trong thẻ input HTML và phía PHP $_FILES['file']
    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            formData.append("file[]", files[i]);
        }
    }

    // UX: Xóa ô nhập liệu ngay lập tức
    txtInput.value = "";
    fileInput.value = ""; // Xóa luôn file đã chọn sau khi bấm gửi
    txtInput.focus();

    fetch("/project-FindU/app/controllers/thanhVien_AJAX.php", {
        method: "POST",
        body: formData // Fetch tự động nhận diện Multipart form data khi dùng FormData
    })
        .then(res => res.text())
        .then(response => {
            // console.log(response); // Bật lên để debug xem PHP trả về gì
            if (response.includes("success")) {
                loadMessages(maCTC);
                setTimeout(scrollToBottom, 100);
            } else {
                console.error("Gửi thất bại: " + response);
                alert("Gửi tin nhắn thất bại.");
                txtInput.value = message; // Hoàn lại tin nhắn nếu lỗi
            }
        })
        .catch(err => console.error(err));
}

// Interval check tin nhắn mới
// Gán vào biến để có thể clear nếu cần thiết (ví dụ khi đóng chat)
var chatInterval = setInterval(() => {
    if (typeof window.maCTC_global !== 'undefined' && window.maCTC_global) {
        loadMessages(window.maCTC_global);
    }
}, 2000);

// Cuộn xuống dưới cùng khi mở
var msgList = document.getElementById("message_list");
msgList.scrollTop = msgList.scrollHeight;

// Thêm sự kiện nhấn Enter để gửi
var txtInput = document.getElementById("txt_chat");
txtInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        document.getElementById("btnSend").click();
    }
});


// Lắng nghe sự kiện khi người dùng chọn file
document.getElementById('txt_file').addEventListener('change', function(e) {
    var fileCount = this.files.length;
    var preview = document.getElementById('file_preview');
    
    if (fileCount > 0) {
        preview.textContent = `Đã chọn ${fileCount} file`;
        preview.style.display = 'inline-block';
    } else {
        preview.textContent = '';
        preview.style.display = 'none';
    }
});

// CẬP NHẬT HÀM sendMessage ĐỂ RESET TEXT SAU KHI GỬI
// Tìm đoạn: txtInput.value = ""; fileInput.value = ""; 
// Thêm dòng này vào ngay dưới:
document.getElementById('file_preview').textContent = ""; // Xóa dòng thông báo chọn file