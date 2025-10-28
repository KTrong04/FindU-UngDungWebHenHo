document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('filter-modal');
  const openBtn = document.getElementById('btn-open-filter-link');
  const closeBtn = document.getElementById('btn-close-filter');
  const ageSlider = document.getElementById('age-slider');
  const ageValue = document.getElementById('age-value');

  // Mở modal
  openBtn.addEventListener('click', (e) => {
    e.preventDefault();
    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
  });

  // Đóng modal
  const closeModal = () => {
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
  };
  closeBtn.addEventListener('click', closeModal);

  // Bấm ra ngoài vùng modal thì đóng
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // Đóng khi nhấn ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  // Hiển thị giá trị tuổi
  ageSlider.addEventListener('input', () => {
    ageValue.textContent = ageSlider.value;
  });
});


document.addEventListener('DOMContentLoaded', () => {
  const wrap   = document.getElementById('hobbyWrap');
  const field  = document.getElementById('hobbyField');
  const panel  = document.getElementById('hobbyPanel');

  const open = () => { wrap.classList.add('open'); field.setAttribute('aria-expanded','true'); };
  const close = () => { wrap.classList.remove('open'); field.setAttribute('aria-expanded','false'); };

  // Toggle khi nhấn ô field
  field.addEventListener('click', (e) => {
    e.stopPropagation();
    wrap.classList.toggle('open');
    field.setAttribute('aria-expanded', wrap.classList.contains('open') ? 'true' : 'false');
  });

  // Không đóng khi click trong panel (quan trọng)
  panel.addEventListener('click', (e) => { e.stopPropagation(); });

  // Cập nhật text đã chọn
  panel.addEventListener('change', () => {
    const chosen = Array.from(panel.querySelectorAll('input:checked')).map(i => i.value);
    field.textContent = chosen.length ? chosen.join(', ') : 'Chọn sở thích...';
  });

  // Click ra ngoài => đóng
  document.addEventListener('click', close);

  // ESC để đóng
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
});
