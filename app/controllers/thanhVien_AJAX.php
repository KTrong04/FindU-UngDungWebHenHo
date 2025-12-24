<?php
session_start();
require_once __DIR__ . '/../controllers/thanhVienController.php';
$tv_ctr = new thanhVienController();

// GỬI TƯƠNG HỢP
if (isset($_POST['action']) || (isset($json['action']))) {
    header('Content-Type: application/json');

    // Lấy dữ liệu JSON / POST
    $userId   = $json['userId']   ?? $_POST['userId'] ?? null;
    $targetId = $json['targetId'] ?? $_POST['targetId'] ?? null;
    $action   = $json['action']   ?? $_POST['action'] ?? null;

    if (!$userId || !$targetId || !in_array($action, ['like', 'nope', 'superlike'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
        exit;
    }

    $result = $tv_ctr->gui_ghepDoi($userId, $targetId, $action);

    echo json_encode([
        'success' => true,
        'matched' => $result['matched'] ?? false
    ]);
    exit; // quan trọng: dừng script ở đây
}


// SIDEBAR MENU
if (isset($_GET['sidebarMenu'])) {
    header('Content-Type: text/html');

    $menu = $_GET['sidebarMenu'];

    if ($menu == 'tuongHop') {

        // Lấy dữ liệu
        $like   = $tv_ctr->loiMoi_ghepDoi_ganNhat($_SESSION['user_maTV'], 'like');
        $super  = $tv_ctr->loiMoi_ghepDoi_ganNhat($_SESSION['user_maTV'], 'superlike');

        // Đảm bảo luôn có avatar hợp lệ
        $lm_like = $like ?: ['anhDaiDien' => 'avatar-default.svg'];
        $lm_super = $super ?: ['anhDaiDien' => 'avatar-default.svg'];

        echo '
                <ul class="sidebar-main-menu">
                    <li class="sidebar-main-items like-tuonghop">
                        <a href="/project-FindU/app/views/user/capDoi.php?list_LoiMoi=like">
                            <div class="sidebar-main-items-box">
                                <img src="/project-FindU/public/uploads/avatars/' . htmlspecialchars($lm_like['anhDaiDien']) . '" alt="Người thích bạn">
                            </div>
                            
                            <div style="display:flex; align-items:center; gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none">
                                    <path d="M2.16 7.354h6.37a5.947 5.947 0 00-.894 2.084H2.16c-.406.04-.8-.15-1.015-.49a1.04 1.04 0 010-1.114c.215-.341.61-.532 1.015-.491v.01zm1.68 6.263c-.406.04-.8-.15-1.015-.49a1.04 1.04 0 010-1.114c.215-.34.61-.531 1.015-.49h3.796c.077.375.186.751.35 1.106l.021.043.022.043.546.902H3.84zm2.476 4.18c-.59 0-1.069-.472-1.069-1.053 0-.582.479-1.053 1.07-1.053h3.49l1.266 2.106H6.316zm13.746-1.837l-6.36 2.89a.495.495 0 01-.611-.183l-3.971-6.5a4.132 4.132 0 01-.185-3.02C9.556 7.183 11.127 6 12.949 6c.404 0 .818.064 1.233.183 1.222.365 1.745.999 2.476 2.299a5.271 5.271 0 012.346-.73c.327 0 .665.064 1.047.171 2.29.677 3.382 2.901 2.618 5.297a4.287 4.287 0 01-1.909 2.396l-.153.086-.152.075-.393.183z" 
                                        fill="#e9f208" 
                                        stroke="#35b951" 
                                        stroke-width="1"/>
                                </svg>
                                <span>Người thích bạn</span>
                            </div>
                            <small style="color: gray; font-size: 11px;">Xem ai đã thả tim</small>
                        </a>
                    </li>

                    <li class="sidebar-main-items superlike-tuonghop">
                        <a href="/project-FindU/app/views/user/capDoi.php?list_LoiMoi=superLike">
                            <div class="sidebar-main-items-box">
                                <img src="/project-FindU/public/uploads/avatars/' . htmlspecialchars($lm_super['anhDaiDien']) . '" alt="Siêu thích bạn">
                            </div>
                            
                            <div style="display:flex; align-items:center; gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.897l-7.334 3.86 1.4-8.168-5.934-5.787 8.2-1.192z" 
                                        fill="#00daff" 
                                        stroke="#0077ff" 
                                        stroke-width="1"/>
                                </svg>
                                <span>Siêu thích bạn</span>
                            </div>
                            <small style="color: gray; font-size: 11px;">Cơ hội ghép đôi cao</small>
                        </a>
                    </li>
                </ul>';

        echo '
                <ul class="sidebar-main-block">
                    <li class="sidebar-main-item-block block-tuonghop">
                        <a href="/project-FindU/app/views/user/danhSachChan.php">
                            <div style="display:flex; align-items:center; gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d32f2f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                                </svg>
                                <span>Danh sách chặn</span>
                            </div>
                            <small style="color: gray; font-size: 11px;">Những người bạn đã chặn</small>
                        </a>
                    </li>
                </ul>';
    } elseif ($menu == 'tinNhan') {
        $list = $tv_ctr->list_cuocTroChuyen($_SESSION['user_maTV']);
        echo '<p class="title-tinNhan">Trò chuyện</p>';
        echo '<ul class="list-cuocTroChuyen">';
        foreach ($list as $ctc) {
            echo '<li class="box-cuocTroChuyen">
                    <button onclick="loadChat(' . $ctc['maCTC'] . ',' . $ctc['maTV'] . '); scrollToBottom()">
                        <div class="box-cuocTroChuyen-avatar">
                            <img src="/project-FindU/public/uploads/avatars/' . htmlspecialchars($ctc['anhDaiDien']) . '" alt="">
                        </div>
                        <span>' . htmlspecialchars($ctc['hoTen']) . '</span>    
                    </button>
                </li>';
        }
        echo '</ul>';
    } else {
        echo 'Thông báo';
    }

    exit;
}


// Nhắn tin
// ====================================================
// LOAD KHUNG CHAT & TIN NHẮN BAN ĐẦU
// ====================================================
if (isset($_GET['maCTC']) && isset($_GET['maTV_chat'])) {
    $box = $tv_ctr->box_chat($_GET['maCTC']);
    $info = $tv_ctr->load_info($_GET['maTV_chat']);
    $listMessage = $tv_ctr->listMessage($_GET['maCTC']);
    $myID = $_SESSION['user_maTV'];

    // Header box chat
    echo '
    <div class="box-chat">
        <div class="box-chat-top">
            <a class="link-profile-chat" href="/project-FindU/app/views/user/hoSo.php?id_profile=' . $info['maTV'] . '">
                <button type="button">
                    <div class="box-chat-avatar">
                        <img src="/project-FindU/public/uploads/avatars/' . htmlspecialchars($info['anhDaiDien']) . '" 
                             onerror="this.src=\'/project-FindU/public/assets/images/default-avatar.png\'" alt="">
                    </div>
                    <span>' . htmlspecialchars($info['hoTen']) . '</span>    
                </button>
            </a>
        </div> 
        <div class="box-chat-center">
            <div id="message_list" class="message-list-container">';

    // RENDER DANH SÁCH TIN NHẮN
    if (!empty($listMessage)) {
        foreach ($listMessage as $m) {
            if ($m['maTVGui'] == $myID) {
                $class = "message-box msg-me";
            } else {
                $class = "message-box msg-friend";
            }

            if (!empty($m['noiDung'])) {
                $m['noiDung'] = SecurityHelper::decrypt($m['noiDung']);
                echo '<div class="' . $class . '" data-id="' . $m['maTN'] . '"><span class="textMessage">' . htmlspecialchars($m['noiDung']) . '</span></div>';
            }

            if (!empty($m['hinh'])) {
                $list_img = explode(',', $m['hinh']);
                echo '<div class="' . $class . '" data-id="' . $m['maTN'] . '"><span class="imgMessage">';
                foreach ($list_img as $i) {
                    echo '<img src="/project-FindU/public/uploads/images/' . htmlspecialchars($i) . '" class="chat-img">';
                }
                echo '</span></div>';
            }

            if (!empty($m['video'])) {
                $list_video = explode(',', $m['video']);
                echo '<div class="' . $class . '" data-id="' . $m['maTN'] . '"><span class="videoMessage">';
                foreach ($list_video as $v) {
                    echo '<video controls class="chat-video">
                            <source src="/project-FindU/public/uploads/videos/' . htmlspecialchars($v) . '" type="video/mp4">
                          </video>';
                }
                echo '</span></div>';
            }
        }
    }

    echo '  </div>
        </div>
        <div class="box-chat-bottom">';
    if ($tv_ctr->check_Chat($myID, $info['maTV']) == false) {
        echo '<div class="thong-bao-chat">Bạn và <b>' . $info['hoTen'] . '</b> đã không còn ghép nối với nhau! <br> Vui lòng kết nối với nhau để tiếp tục nhắn tin.</div>';
    } else {
        echo '<input type="file" name="file[]" id="txt_file" multiple class="input-file-hidden">
            <label for="txt_file" class="btn-attach" title="Đính kèm ảnh/video">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#eb0052" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
            </svg>
            </label>
            <input name="txt_chat" id="txt_chat" placeholder="Nhập tin nhắn..." rows="1">
            <button type="button" id="btnSend" class="btn-send" onclick="sendMessage(' . $_GET['maCTC'] . ',' . $info['maTV'] . ')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false" role="img" class="gamepad-icon gamepad-icon-invert" stroke="var(--color--border-sparks-super-like, inherit)"><title></title><g fill="#eb0052"><path d="M.29 9.229c2.647 1.427 5.236 2.522 7.187 3.274a1.4 1.4 0 0 0 1.228-.114l5.34-3.246c.532-.324 1.151.285.818.818l-3.246 5.34a1.397 1.397 0 0 0-.114 1.228 61.524 61.524 0 0 0 3.274 7.186c.22.4.79.371.971-.038.838-1.866 2.256-5.292 3.303-9.204l.171-.657c1.104-4.13 1.59-7.985 1.77-9.946a.796.796 0 0 0-.866-.866c-1.96.18-5.825.666-9.946 1.77l-.657.171C5.611 5.992 2.185 7.411.32 8.248a.543.543 0 0 0-.038.971z"></path></g></svg>
            </button>';
    }
    echo '</div>
    </div>';
    exit;
}

// ====================================================
// XỬ LÝ GỬI TIN NHẮN
// ====================================================
if (isset($_POST['maCTC_send']) && isset($_POST['maTV_chat_send'])) {
    $maCTC = $_POST['maCTC_send'];
    $maTVgui = $_SESSION['user_maTV'];
    $maTVnhan = $_POST['maTV_chat_send'];
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Lấy file (nếu không có thì mảng $_FILES['file'] vẫn tồn tại nhưng name rỗng)
    $files = $_FILES['file'] ?? [];

    // Kiểm tra có file đính kèm thực sự không
    $hasFiles = isset($files['name']) && !empty($files['name'][0]);

    if (!empty($message) || $hasFiles) {
        // Gọi controller
        $result = $tv_ctr->sendMessage($maCTC, $maTVgui, $maTVnhan, $message, $files);

        if ($result == true) {
            echo "success";
        } else {
            // Có thể echo thêm lý do nếu muốn debug, ví dụ: "error_upload"
            echo "error";
        }
    }
    exit;
}

// ====================================================
// API LOAD RIÊNG TIN NHẮN (POLLING)
// ====================================================
if (isset($_GET['loadMessage'])) {
    $maCTC = $_GET['loadMessage'];
    $listMessage = $tv_ctr->listMessage($maCTC);
    $myID = $_SESSION['user_maTV'];

    // Lấy last_id từ client gửi lên
    $last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

    if (!empty($listMessage)) {
        foreach ($listMessage as $m) {
            //Chỉ render những tin nhắn có ID lớn hơn last_id
            if ($m['maTN'] <= $last_id) {
                continue;
            }

            if ($m['maTVGui'] == $myID) {
                $class = "message-box msg-me";
            } else {
                $class = "message-box msg-friend";
            }

            if (!empty($m['noiDung'])) {
                $m['noiDung'] = SecurityHelper::decrypt($m['noiDung']);
                echo '<div class="' . $class . '" data-id="' . $m['maTN'] . '"><span class="textMessage">' . htmlspecialchars($m['noiDung']) . '</span></div>';
            }

            if (!empty($m['hinh'])) {
                $list_img = explode(',', $m['hinh']);
                echo '<div class="' . $class . '" data-id="' . $m['maTN'] . '"><span class="imgMessage">';
                foreach ($list_img as $i) {
                    echo '<img src="/project-FindU/public/uploads/images/' . htmlspecialchars($i) . '" class="chat-img">';
                }
                echo '</span></div>';
            }

            if (!empty($m['video'])) {
                $list_video = explode(',', $m['video']);
                echo '<div class="' . $class . '" data-id="' . $m['maTN'] . '"><span class="videoMessage">';
                foreach ($list_video as $v) {
                    echo '<video controls class="chat-video">
                            <source src="/project-FindU/public/uploads/videos/' . htmlspecialchars($v) . '" type="video/mp4">
                          </video>';
                }
                echo '</span></div>';
            }
        }
    }
    exit;
}
