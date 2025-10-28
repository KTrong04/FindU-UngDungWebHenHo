// form khóa tài khoản thành viên
document.querySelector('.rd_khoaVV').addEventListener('change', () => {
    document.querySelector('.timeKhoa').classList.remove('block');
    document.querySelector('.timeKhoa').classList.add('none');
});

document.querySelector('.rd_khoaTH').addEventListener('change', () => {
    document.querySelector('.timeKhoa').classList.remove('none');
    document.querySelector('.timeKhoa').classList.add('block');
});

document.querySelector('.btn_khoaTV').addEventListener('click', () => {
  document.querySelector('.box-form-khoa').classList.toggle('block');
});




