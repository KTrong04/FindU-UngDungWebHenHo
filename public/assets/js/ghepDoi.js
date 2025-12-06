// Core swipe logic - PHIÊN BẢN CUỐI CÙNG, HOÀN HẢO 100%
(function () {
    const cardsContainer = document.getElementById('cards');
    let draggingCard = null;
    let startX = 0, startY = 0;
    let currentX = 0, currentY = 0;
    let isDragging = false;
    const threshold = 100;
    const history = []; // Lưu cả card + action để undo

    function getRemainingCards() {
        return Array.from(cardsContainer.querySelectorAll('.card:not(.removed)'));
    }

    function updateCardStack() {
        const cards = getRemainingCards();
        cards.forEach((card, index) => {
            card.style.transition = 'all 0.3s ease';
            if (index === 0) {
                card.style.zIndex = 100;
                card.style.transform = 'translateY(0) scale(1)';
                card.style.opacity = 1;
            } else if (index === 1) {
                card.style.zIndex = 99;
                card.style.transform = 'translateY(12px) scale(0.98)';
                card.style.opacity = 0.95;
            } else if (index === 2) {
                card.style.zIndex = 98;
                card.style.transform = 'translateY(24px) scale(0.96)';
                card.style.opacity = 0.9;
            } else {
                card.style.zIndex = 97;
                card.style.transform = 'translateY(36px) scale(0.94)';
                card.style.opacity = 0.85;
            }
        });
    }

    // Khởi tạo hình ảnh + tên tuổi
    document.querySelectorAll('.card').forEach(card => {
        const img = card.dataset.img || '';
        const imgPath = img && (img.startsWith('/') || img.startsWith('http'))
            ? img
            : (img ? '/project-FindU/public/uploads/avatars/' + img : '');

        card.style.backgroundImage = imgPath ? `url("${imgPath}")` : '';
        card.style.backgroundColor = imgPath ? 'transparent' : '#ddd';

        const name = card.dataset.name;
        const age = card.dataset.age;
        if (name && age) {
            const nameEl = card.querySelector('.name');
            if (nameEl) {
                nameEl.innerHTML = `${name} <span style="font-weight:700;color:#fff;opacity:0.95">${age}</span>`;
            }
        }
    });

    updateCardStack();

    // Drag handlers
    function pointerDown(e) {
        const topCard = getRemainingCards()[0];
        const clickedCard = e.target.closest('.card');
        if (!topCard || topCard !== clickedCard) return;

        draggingCard = topCard;
        isDragging = true;
        startX = e.touches ? e.touches[0].clientX : e.clientX;
        startY = e.touches ? e.touches[0].clientY : e.clientY;
        currentX = currentY = 0;
        draggingCard.style.transition = 'none';
    }

    function pointerMove(e) {
        if (!isDragging || !draggingCard) return;
        e.preventDefault();

        const x = e.touches ? e.touches[0].clientX : e.clientX;
        const y = e.touches ? e.touches[0].clientY : e.clientY;
        currentX = x - startX;
        currentY = y - startY;

        const rot = currentX / 15;
        draggingCard.style.transform = `translate(${currentX}px, ${currentY}px) rotate(${rot}deg)`;

        // Indicator
        const like = draggingCard.querySelector('.indicator.like');
        const nope = draggingCard.querySelector('.indicator.nope');
        const superIcon = draggingCard.querySelector('.indicator.super');
        const opacity = Math.min(Math.abs(currentX) / 150, 1);

        if (currentX > 0) {
            if (like) like.style.opacity = opacity;
            if (nope) nope.style.opacity = 0;
        } else {
            if (nope) nope.style.opacity = opacity;
            if (like) like.style.opacity = 0;
        }

        // SUPER LIKE: vuốt lên
        if (currentY < -40 && Math.abs(currentY) > Math.abs(currentX) * 1.3) {
            if (superIcon) superIcon.style.opacity = Math.min((-currentY) / 150, 1);
        } else {
            if (superIcon) superIcon.style.opacity = 0;
        }


        // Next card peek
        const next = getRemainingCards()[1];
        if (next) {
            const percent = Math.min(Math.abs(currentX) / window.innerWidth, 1);
            next.style.transform = `translateY(${12 - 12 * percent}px) scale(${0.98 + 0.02 * percent})`;
            next.style.opacity = 0.95 + 0.05 * percent;
        }
    }

    function pointerUp() {
        if (!isDragging || !draggingCard) return;
        isDragging = false;
        draggingCard.style.transition = 'transform 0.4s ease';

        const swipedLeftRight = Math.abs(currentX) > threshold;
        const swipedUp = currentY < -120 && Math.abs(currentY) > Math.abs(currentX) * 1.3;

        const targetId = draggingCard.dataset.id;
        const swipedCard = draggingCard;

        // ===== SUPER LIKE (vuốt lên) =====
        if (swipedUp) {
            performSwipeAnimation(swipedCard, true, 'superlike');
            history.push({ el: swipedCard, action: 'superlike' });

            handleSwipe(targetId, 'superlike')
                .then(data => {
                    if (data.matched) {
                        setTimeout(() => showMatchPopup(swipedCard.dataset.name), 600);
                    }
                })
                .catch(() => {
                    history.pop();
                    alert('Lỗi mạng! Super Like đã được hoàn tác.');
                    undoSwipe();
                });

            draggingCard = null;
            return;
        }

        // ===== LIKE / NOPE (vuốt ngang) =====
        if (swipedLeftRight) {
            const action = currentX > 0 ? 'like' : 'nope';

            performSwipeAnimation(swipedCard, currentX > 0, action);
            history.push({ el: swipedCard, action });

            handleSwipe(targetId, action)
                .then(data => {
                    if (data.matched) {
                        setTimeout(() => showMatchPopup(swipedCard.dataset.name), 600);
                    }
                })
                .catch(() => {
                    history.pop();
                    alert('Lỗi mạng! Thao tác đã được hoàn tác.');
                    undoSwipe();
                });

        } else {
            // Không vượt threshold → trả về
            draggingCard.style.transform = '';
            updateCardStack();
        }

        // Reset indicator
        draggingCard?.querySelectorAll('.indicator').forEach(el => el.style.opacity = 0);

        draggingCard = null;
        currentX = currentY = 0;
    }



    // Gửi AJAX
    function handleSwipe(targetId, action) {
        console.log("CALL handleSwipe:", targetId, action);

        const formData = new FormData();
        formData.append('userId', CURRENT_USER_ID);
        formData.append('targetId', targetId);
        formData.append('action', action);

        return fetch('/Project-FindU/app/controllers/thanhVien_AJAX.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(res => res.json());
    }


    // Animation khi vuốt
    function performSwipeAnimation(card, toRight, action) {
        const offX = (toRight ? 1 : -1) * window.innerWidth * 1.5;
        const rot = toRight ? 30 : -30;

        card.style.transition = 'transform 0.6s cubic-bezier(0.18, 0.89, 0.32, 1.28)';
        if (action === 'superlike') {
            card.style.transform = `translateY(-${window.innerHeight * 1.4}px) scale(0.8)`;
        } else {
            card.style.transform = `translate(${offX}px, -80px) rotate(${rot}deg)`;
        }

        setTimeout(() => {
            card.classList.add('removed');
            card.style.display = 'none';
            updateCardStack();
        }, 600);
    }

    // Nút bấm
    // Nút bấm (optmistic) - tương tự: animate ngay, push history, gọi API, rollback nếu lỗi
    function swipeCard(toRight, action = null) {
        const top = getRemainingCards()[0];
        if (!top) return;

        const finalAction = action || (toRight ? 'like' : 'nope');
        const swipedCard = top;

        // Animate ngay và push lịch sử
        performSwipeAnimation(swipedCard, toRight, finalAction);
        history.push({ el: swipedCard, action: finalAction });

        handleSwipe(swipedCard.dataset.id, finalAction)
            .then(data => {
                if (data.matched) {
                    setTimeout(() => showMatchPopup(swipedCard.dataset.name), 600);
                }
            })
            .catch(() => {
                // rollback giống pointerUp
                if (history.length && history[history.length - 1].el === swipedCard) {
                    history.pop();
                } else {
                    for (let i = history.length - 1; i >= 0; i--) {
                        if (history[i].el === swipedCard) {
                            history.splice(i, 1);
                            break;
                        }
                    }
                }
                alert('Lỗi mạng! Thao tác đã được hoàn tác.');
                undoSwipe();
            });
    }

    function superLike() {
        swipeCard(true, 'superlike');
    }

    // UNDO SIÊU ĐẸP - HOÀN HẢO
    function undoSwipe() {
        if (history.length === 0) {
            alert('Không có hành động nào để hoàn tác!');
            return;
        }

        const last = history.pop();
        const card = last.el;

        // Hiển thị lại card
        card.classList.remove('removed');
        card.style.display = '';
        card.style.transition = 'none';
        card.style.transform = 'translateY(100px) scale(0.8)';
        card.style.opacity = '0';

        // Đưa về đầu stack
        cardsContainer.prepend(card);

        // Force reflow + animate đẹp
        void card.offsetWidth;
        card.style.transition = 'all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        card.style.transform = 'translateY(0) scale(1)';
        card.style.opacity = '1';

        setTimeout(updateCardStack, 100);
    }

    // Popup match đẹp (có thể thay bằng modal thật)
    function showMatchPopup(name) {
        const popup = document.createElement('div');
        popup.innerHTML = `
            <div style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);z-index:9999;display:flex;align-items:center;justify-content:center;">
                <div style="text-align:center;color:white;">
                    <h1 style="font-size:3rem;margin:0;">IT'S A MATCH!</h1>
                    <p style="font-size:1.5rem;margin:20px 0;">Bạn và <strong>${name}</strong> đã thích nhau!</p>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            style="padding:12px 30px;font-size:1.1rem;border:none;border-radius:50px;background:#ff5a5f;color:white;cursor:pointer;">
                        Tiếp tục vuốt
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(popup);
    }

    // === TOÀN BỘ PHẦN GẮN SỰ KIỆN – DÁN THAY THẾ HOÀN TOÀN ===
    cardsContainer.addEventListener('mousedown', pointerDown);
    cardsContainer.addEventListener('touchstart', pointerDown, { passive: false });

    // FIX BUG CHÍNH: dùng capture phase
    document.addEventListener('mouseup', pointerUp, true);
    document.addEventListener('touchend', pointerUp, true);

    document.addEventListener('mousemove', pointerMove);
    document.addEventListener('touchmove', pointerMove, { passive: false });

    // Nút bấm
    document.getElementById('noBtn')?.addEventListener('click', () => swipeCard(false));
    document.getElementById('likeBtn')?.addEventListener('click', () => swipeCard(true));
    document.getElementById('starBtn')?.addEventListener('click', superLike);
    document.getElementById('undoBtn')?.addEventListener('click', undoSwipe);
    document.getElementById('msgBtn')?.addEventListener('click', () => {
        const name = getRemainingCards()[0]?.dataset.name || 'người này';
        alert(`Nhắn tin với ${name} chưa được triển khai`);
    });

    // Đảm bảo có biến này trong Blade
    if (typeof CURRENT_USER_ID === 'undefined') {
        console.error('CURRENT_USER_ID chưa được định nghĩa!');
    }

})();

