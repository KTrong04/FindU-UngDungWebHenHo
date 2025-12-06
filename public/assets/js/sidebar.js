function border_items(clickedEl) {
    // remove active khỏi tất cả
    document.querySelectorAll('.sidebar-content-items').forEach(el => el.classList.remove('active'));

    // thêm active cho item được click
    clickedEl.classList.add('active');
}

function loadBox(menuName) {
    fetch(`/project-FindU/app/controllers/thanhVien_AJAX.php?sidebarMenu=${menuName}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('sidebar-main').innerHTML = html;
        })
        .catch(err => console.error(err));
}

