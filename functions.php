<?php

/**
 * SHOP PUBG FULL FUNCTIONS - VERSION 8.9 (FIXED ALL ADMIN AJAX & SHORTCODES)
 */

// 1. NẠP SCRIPTS & CẤU HÌNH CƠ BẢN
require get_template_directory() . '/inc/cpt-setup.php';
require get_template_directory() . '/inc/wallet-functions.php';

function shop_pubg_scripts()
{
    $theme_uri = get_template_directory_uri();
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_style('main-style', $theme_uri . '/assets/css/style.css', array(), time());
    wp_enqueue_script('purchase-js', $theme_uri . '/assets/js/purchase.js', array('jquery'), time(), true);

    wp_localize_script('purchase-js', 'shop_ajax', array(
        'url'      => admin_url('admin-ajax.php'),
        'home_url' => home_url()
    ));
}
add_action('wp_enqueue_scripts', 'shop_pubg_scripts');

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
register_nav_menus(array('primary_menu' => 'Menu Chính Header'));

// 2. KÍCH HOẠT SESSION & SMTP (EMAIL OTP)
add_action('init', function () {
    if (!session_id()) {
        session_start();
    }
}, 1);

add_action('phpmailer_init', 'custom_smtp_config');
function custom_smtp_config($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.gmail.com';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 587;
    $phpmailer->Username   = 'reliveisme@gmail.com';
    $phpmailer->Password   = 'ewxo kwvq xzyx jlvb';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = 'reliveisme@gmail.com';
    $phpmailer->FromName   = 'SHOP PUBG PRO';
}

// 3. LOGIC AUTH & MUA NICK (GIỮ NGUYÊN)
add_action('wp_ajax_nopriv_send_otp_action', 'handle_send_otp');
function handle_send_otp()
{
    $email = sanitize_email($_POST['email']);
    $otp = rand(100000, 999999);
    $_SESSION['register_otp'] = $otp;
    $_SESSION['register_email'] = $email;
    wp_mail($email, "MA XAC MINH OTP", "Mã của bạn là: $otp");
    wp_send_json_success('Mã đã gửi!');
}

add_action('wp_ajax_nopriv_custom_auth_action', 'handle_custom_auth');
function handle_custom_auth()
{
    $type = $_POST['auth_type'];
    if ($type == 'login') {
        $info = array('user_login' => $_POST['username'], 'user_password' => $_POST['password'], 'remember' => true);
        $user_signon = wp_signon($info, false);
        if (is_wp_error($user_signon)) {
            wp_send_json_error('Sai tài khoản!');
        } else {
            wp_send_json_success('Thành công!');
        }
    } else {
        if ($_POST['otp_code'] != $_SESSION['register_otp']) {
            wp_send_json_error('OTP sai!');
        }
        $user_id = wp_create_user(sanitize_user($_POST['reg_username']), $_POST['reg_password'], sanitize_email($_POST['reg_email']));
        if (!is_wp_error($user_id)) {
            update_field('user_balance', 0, 'user_' . $user_id);
            wp_set_auth_cookie($user_id);
            wp_send_json_success('Đăng ký xong!');
        } else {
            wp_send_json_error('Lỗi!');
        }
    }
}

add_action('wp_ajax_buy_nick_action', 'handle_buy_nick_logic');
function handle_buy_nick_logic()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('Vui lòng đăng nhập!');
    }
    $nick_id = intval($_POST['nick_id']);
    $user_id = get_current_user_id();
    if (get_field('is_sold', $nick_id) == 'yes') {
        wp_send_json_error('Đã bán!');
    }
    $price = (int)get_field('gia_sale', $nick_id) ?: (int)get_field('gia_ban', $nick_id);
    $balance = (int)get_field('user_balance', 'user_' . $user_id);
    if ($balance < $price) {
        wp_send_json_error('Không đủ tiền!');
    }
    $new_balance = $balance - $price;
    update_field('user_balance', $new_balance, 'user_' . $user_id);
    update_field('is_sold', 'yes', $nick_id);
    update_post_meta($nick_id, 'buyer_id', $user_id);
    add_row('lich_su_giao_dich', array(
        'ngay' => date('H:i - d/m/Y'),
        'loai' => 'Trừ tiền',
        'so_tien' => '-' . number_format($price) . 'đ',
        'noi_dung' => 'Mua Nick #' . $nick_id,
        'so_du_cuoi' => number_format($new_balance) . 'đ'
    ), 'user_' . $user_id);
    wp_send_json_success(['new_balance' => number_format($new_balance) . 'đ']);
}

// 4. CẤU HÌNH NẠP TIỀN & VNPAY (OPTION PAGE)
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array('page_title' => 'Cài đặt Nạp', 'menu_slug' => 'nap-tien-settings'));
    acf_add_options_page(array('page_title' => 'Cấu hình Trang chủ', 'menu_slug' => 'acf-options'));
}

add_action('wp_ajax_vnpay_create_payment', 'vnpay_create_payment_handler');
function vnpay_create_payment_handler()
{
    $tmnCode = get_field('vnpay_tmncode', 'option');
    $hashSecret = get_field('vnpay_hashsecret', 'option');
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $amount = intval($_POST['amount']);
    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $tmnCode,
        "vnp_Amount" => $amount * 100,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
        "vnp_Locale" => 'vn',
        "vnp_OrderInfo" => "Nap tien",
        "vnp_OrderType" => 'billpayment',
        "vnp_ReturnUrl" => home_url('/nap-tien/?vnpay=return'),
        "vnp_TxnRef" => time(),
    );
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $hashSecret);
    wp_send_json_success($vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash);
}

// 5. THỐNG KÊ CHI TIẾT & FIX LỖI "ĐANG TẢI" (TRANG CHỦ ADMIN)
add_filter('acf/load_field/name=user_balance_stats', 'render_admin_stats_dashboard');
function render_admin_stats_dashboard($field)
{
    $users = get_users(array('fields' => array('ID', 'user_login', 'display_name')));
    $total_balance = 0;
    $html = '<div style="background: #1e1e1e; border-radius: 8px; overflow: hidden; margin-bottom: 20px; border: 1px solid #333;">';
    $html .= '<table style="width: 100%; border-collapse: collapse; color: #ccc; font-size: 13px;"><thead style="background: #2c3e50; color: #fff;"><tr><th style="padding: 12px; text-align: left;">Username</th><th style="padding: 12px; text-align: right;">Số dư</th></tr></thead><tbody>';
    foreach ($users as $u) {
        $bal = (int)get_field('user_balance', 'user_' . $u->ID) ?: 0;
        $total_balance += $bal;
        $html .= '<tr style="border-bottom: 1px solid #333;"><td style="padding: 10px 12px; color: #f39c12;">' . $u->user_login . '</td><td style="padding: 10px 12px; text-align: right; color: #2ecc71;">' . number_format($bal) . 'đ</td></tr>';
    }
    $html .= '</tbody></table></div>';
    $html .= '<div style="text-align:right; color:#fff; font-weight:bold;">TỔNG VÍ HỆ THỐNG: ' . number_format($total_balance) . 'đ</div>';
    $field['message'] = $html;
    return $field;
}

// 6. FIX LỖI "KHÔNG HIỆN SỐ DƯ HIỆN TẠI" TRONG REPEATER (MỤC ĐỎ BẠN KÊU)
add_action('wp_ajax_get_user_balance_quick', function () {
    $user_id = intval($_POST['user_id']);
    $balance = get_field('user_balance', 'user_' . $user_id);
    echo number_format((int)$balance ?: 0) . 'đ';
    wp_die();
});

add_action('admin_footer', function () {
?>
    <script type="text/javascript">
        (function($) {
            $(document).on('change', '.acf-field[data-name="target_user"] select', function() {
                var $row = $(this).closest('.acf-row');
                var userId = $(this).val();
                if (userId) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'get_user_balance_quick',
                            user_id: userId
                        },
                        success: function(res) {
                            $row.find('.acf-field[data-name="current_balance_display"] input').val(res);
                        }
                    });
                }
            });
        })(jQuery);
    </script>
<?php
});

// Xử lý cộng trừ tiền khi nhấn Update
add_action('acf/save_post', function ($post_id) {
    if ($post_id !== 'options') return;
    $user_list = get_field('quick_user_list', 'option');
    if ($user_list) {
        foreach ($user_list as $row) {
            $u_id = $row['target_user'];
            $amt = (int)$row['target_amount'];
            if (!$u_id || $amt <= 0) continue;
            $old = (int)get_field('user_balance', 'user_' . $u_id);
            $is_plus = ($row['action_type'] === 'plus');
            $new = $is_plus ? ($old + $amt) : max(0, $old - $amt);
            update_field('user_balance', $new, 'user_' . $u_id);
            add_row('lich_su_giao_dich', array(
                'ngay' => date('H:i - d/m/Y'),
                'loai' => $is_plus ? 'Cộng tiền' : 'Trừ tiền',
                'so_tien' => ($is_plus ? '+' : '-') . number_format($amt) . 'đ',
                'noi_dung' => 'Admin điều chỉnh số dư',
                'so_du_cuoi' => number_format($new) . 'đ'
            ), 'user_' . $u_id);
            if (!empty($row['target_password'])) wp_set_password($row['target_password'], $u_id);
        }
        update_field('quick_user_list', array(), 'option');
    }
}, 20);

// 7. FIX LỖI [user_balance] VÀ DỌN DẸP UI
add_shortcode('user_balance', function () {
    if (is_user_logged_in()) {
        $balance = (int)get_field('user_balance', 'user_' . get_current_user_id());
        return '<span class="user-balance-nav"><i class="fa-solid fa-wallet"></i> ' . number_format($balance) . 'đ</span>';
    }
    return '';
});

add_action('admin_head', function () {
    $screen = get_current_screen();
    if ($screen->id === 'profile' || $screen->id === 'user-edit') {
        echo '<style>h2, .user-rich-editing-wrap, .user-admin-bar-front-wrap, .user-syntax-highlighting-wrap, .user-comment-shortcuts-wrap, .user-admin-color-wrap, .user-first-name-wrap, .user-last-name-wrap, .user-nickname-wrap, .user-display-name-wrap, .user-url-wrap, .user-description-wrap, .user-profile-picture-wrap, .user-language-wrap, .user-sessions-wrap { display: none !important; } .acf-field[data-name="lich_su_giao_dich"] { display: none !important; }</style>';
    }
});

// 8. BỘ LỌC NICK PUBG (GIỮ NGUYÊN)
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('nick-pubg')) {
        $meta_query = array('relation' => 'AND');
        if (!empty($_GET['filter_rank'])) {
            $meta_query[] = array('key' => 'rank_pubg', 'value' => sanitize_text_field($_GET['filter_rank']), 'compare' => '=');
        }
        if (!empty($_GET['filter_price'])) {
            $range = explode('-', $_GET['filter_price']);
            $meta_query[] = array('key' => 'gia_ban', 'value' => array($range[0], $range[1]), 'type' => 'numeric', 'compare' => 'BETWEEN');
        }
        $query->set('meta_query', $meta_query);
    }
});
/**
 * Ẩn thanh Admin Bar đối với tất cả người dùng trừ Administrator
 */
add_filter('show_admin_bar', function ($show) {
    if (!current_user_can('administrator')) {
        return false;
    }
    return $show;
});

/**
 * Ngăn chặn khách truy cập vào đường dẫn /wp-admin/
 */
add_action('admin_init', function () {
    if (is_admin() && !current_user_can('administrator') && ! (defined('DOING_AJAX') && DOING_AJAX)) {
        wp_redirect(home_url('/tai-khoan')); // Chuyển hướng về trang cá nhân của bạn
        exit;
    }
});
