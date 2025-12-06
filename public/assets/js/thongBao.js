document.addEventListener('click', function (e) {
    if (e.target.classList.contains('close-btn')) {
        e.target.parentElement.remove(); // Ẩn hoặc xóa thông báo
    }
});

