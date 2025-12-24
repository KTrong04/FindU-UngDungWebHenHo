            <style>
            .form-filter .input-with-icon { position: relative; display: flex; align-items: center; }
            .form-filter .input-with-icon .search-icon { position: absolute; left: 12px; width: 18px; height: 18px; fill: #9ca3af; pointer-events: none; }
            .form-filter .input-with-icon .input-text { padding-left: 40px; border-radius: 10px; border: 1px solid #e5e7eb; height: 42px; width: 100%; box-sizing: border-box; background: #fff; }
            .form-filter .btn-submit-filter { margin-top: 12px; padding: 10px 14px; border-radius: 8px; background: linear-gradient(90deg,#eb0052,#ec4899); color: #fff; border: none; cursor: pointer; font-weight: 700; }
            </style>
<header id="header-home">
    <ul class="menu-home">
        <li class="items">
            <a href="/project-FindU/app/views/user/trangChu.php">
                <svg class="icon-menu" viewBox="0 0 24 24" fill="#eb0052" class="x14rh7hd x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--x-color:var(--primary-button-background)">
                    <path d="M9.464 1.286C10.294.803 11.092.5 12 .5c.908 0 1.707.303 2.537.786.795.462 1.7 1.142 2.815 1.977l2.232 1.675c1.391 1.042 2.359 1.766 2.888 2.826.53 1.059.53 2.268.528 4.006v4.3c0 1.355 0 2.471-.119 3.355-.124.928-.396 1.747-1.052 2.403-.657.657-1.476.928-2.404 1.053-.884.119-2 .119-3.354.119H7.93c-1.354 0-2.471 0-3.355-.119-.928-.125-1.747-.396-2.403-1.053-.656-.656-.928-1.475-1.053-2.403C1 18.541 1 17.425 1 16.07v-4.3c0-1.738-.002-2.947.528-4.006.53-1.06 1.497-1.784 2.888-2.826L6.65 3.263c1.114-.835 2.02-1.515 2.815-1.977zM10.5 13A1.5 1.5 0 0 0 9 14.5V21h6v-6.5a1.5 1.5 0 0 0-1.5-1.5h-3z"></path>
                </svg>
            </a>
        </li>
        <li class="items">
            <a href="/project-FindU/app/views/user/capDoi.php">
                <svg class="icon-menu" viewBox="0 0 24 24" fill="#eb0052" class="x14rh7hd x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--x-color:var(--secondary-icon)">
                    <path d="M12.496 5a4 4 0 1 1 8 0 4 4 0 0 1-8 0zm4-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-9 2.5a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm-2 4a2 2 0 1 1 4 0 2 2 0 0 1-4 0zM5.5 15a5 5 0 0 0-5 5 3 3 0 0 0 3 3h8.006a3 3 0 0 0 3-3 5 5 0 0 0-5-5H5.5zm-3 5a3 3 0 0 1 3-3h4.006a3 3 0 0 1 3 3 1 1 0 0 1-1 1H3.5a1 1 0 0 1-1-1zm12-9.5a5.04 5.04 0 0 0-.37.014 1 1 0 0 0 .146 1.994c.074-.005.149-.008.224-.008h4.006a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-3.398a1 1 0 1 0 0 2h3.398a3 3 0 0 0 3-3 5 5 0 0 0-5-5H14.5z"></path>
                </svg>
            </a>
        </li>
        <!-- <li class="items"><a href="/project-FindU/app/views/user/ghepDoi.php"><img src="/project-FindU/public/assets/img/menu-heart.svg" alt="" class="icon-menu"></a></li> -->
        <li class="items">
            <div class="box-left-search">
                <a href="searchForm.php" id="btn-open-filter-link" class="btn-search-trigger" aria-controls="filter-modal" aria-expanded="false">
                    <svg class="icon-menu" viewBox="0 0 16 16" fill="currentColor" class="x14rh7hd x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--x-color:var(--secondary-icon)">
                        <g fill-rule="evenodd" transform="translate(-448 -544)">
                            <g fill-rule="nonzero" fill="#eb0052">
                                <path d="M10.743 2.257a6 6 0 1 1-8.485 8.486 6 6 0 0 1 8.485-8.486zm-1.06 1.06a4.5 4.5 0 1 0-6.365 6.364 4.5 4.5 0 0 0 6.364-6.363z" transform="translate(448 544)"></path>
                                <path d="M10.39 8.75a2.94 2.94 0 0 0-.199.432c-.155.417-.23.849-.172 1.284.055.415.232.794.54 1.103a.75.75 0 0 0 1.112-1.004l-.051-.057a.39.39 0 0 1-.114-.24c-.021-.155.014-.356.09-.563.031-.081.06-.145.08-.182l.012-.022a.75.75 0 1 0-1.299-.752z" transform="translate(448 544)"></path>
                                <path d="M9.557 11.659c.038-.018.09-.04.15-.064.207-.077.408-.112.562-.092.08.01.143.034.198.077l.041.036a.75.75 0 0 0 1.06-1.06 1.881 1.881 0 0 0-1.103-.54c-.435-.058-.867.018-1.284.175-.189.07-.336.143-.433.2a.75.75 0 0 0 .624 1.356l.066-.027.12-.061z" transform="translate(448 544)"></path>
                                <path d="m13.463 15.142-.04-.044-3.574-4.192c-.599-.703.355-1.656 1.058-1.057l4.191 3.574.044.04c.058.059.122.137.182.24.249.425.249.96-.154 1.41l-.057.057c-.45.403-.986.403-1.411.154a1.182 1.182 0 0 1-.24-.182zm.617-.616.444-.444a.31.31 0 0 0-.063-.052c-.093-.055-.263-.055-.35.024l.208.232.207-.206.006.007-.22.257-.026-.024.033-.034.025.027-.257.22-.007-.007zm-.027-.415c-.078.088-.078.257-.023.35a.31.31 0 0 0 .051.063l.205-.204-.233-.209z" transform="translate(448 544)"></path>
                            </g>
                        </g>
                    </svg>
                </a>
            </div>
        </li>
    </ul>
</header>

<!-- Modal Lọc -->
<div id="filter-modal" class="filter-modal-overlay" aria-hidden="true">
    <div class="filter-modal-content" role="dialog" aria-modal="true" aria-labelledby="filter-title">
        <button type="button" class="modal-close" id="btn-close-filter" aria-label="Đóng bộ lọc">×</button>

        <form action="/project-FindU/app/controllers/search_by_name.php" method="get" class="form-filter" id="filterForm">
            <h2 id="filter-title">Thanh Tìm Kiếm</h2>

            <!-- Họ tên -->
            <div class="filter-group">
                <div class="input-with-icon">
                    <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M21 20l-5.6-5.6a7 7 0 10-1.4 1.4L20 21l1-1zM10 16a6 6 0 110-12 6 6 0 010 12z"></path>
                    </svg>
                    <input type="text" id="fullName" name="hoTen" class="input-text" placeholder="Tìm kiếm theo tên, email, mô tả..." />
                </div>
            </div>

            
            <button type="submit" class="btn-submit-filter">🔎 TÌM KIẾM</button>
        </form>
    </div>
</div>