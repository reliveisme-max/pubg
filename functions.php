<?php

/**
 * Shop PUBG Custom Functions
 */

require get_template_directory() . '/inc/cpt-setup.php';
require get_template_directory() . '/inc/wallet-functions.php';
require get_template_directory() . '/inc/ajax-handle.php';

function shop_pubg_scripts()
{
    $theme_uri = get_template_directory_uri();

    // 1. Nạp Font Inter
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    // 2. Nạp Swiper (CSS & JS) - Rất quan trọng cho Slider
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

    // 3. Nạp CSS chính (Dùng assets/css/style.css)
    wp_enqueue_style('main-style', $theme_uri . '/assets/css/style.css', array(), time());

    // 4. Nạp JS mua hàng
    wp_enqueue_script('purchase-js', $theme_uri . '/assets/js/purchase.js', array('jquery'), time(), true);
    wp_localize_script('purchase-js', 'shop_ajax', array('url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'shop_pubg_scripts');

add_theme_support('post-thumbnails');
add_theme_support('title-tag');

function shop_pubg_menus()
{
    register_nav_menus(array('primary_menu' => 'Menu Chính Header'));
}
add_action('after_setup_theme', 'shop_pubg_menus');


function custom_shop_filter($query)
{
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('nick-pubg')) {
        $meta_query = array();

        if (!empty($_GET['filter_rank'])) {
            $meta_query[] = array(
                'key' => 'rank_pubg',
                'value' => $_GET['filter_rank'],
                'compare' => '='
            );
        }

        if (!empty($_GET['filter_price'])) {
            $prices = explode('-', $_GET['filter_price']);
            $meta_query[] = array(
                'key' => 'gia_sale',
                'value' => array($prices[0], $prices[1]),
                'type' => 'numeric',
                'compare' => 'BETWEEN'
            );
        }

        if (!empty($meta_query)) {
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'custom_shop_filter');


// 1. KÍCH HOẠT SESSION (Bắt buộc để lưu mã OTP)
add_action('init', function () {
    if (!session_id()) {
        session_start();
    }
}, 1);

// 2. CẤU HÌNH SMTP GỬI MAIL (Thay thông tin của bạn)
add_action('phpmailer_init', 'custom_smtp_config');
function custom_smtp_config($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.gmail.com';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 587;
    $phpmailer->Username   = 'reliveisme@gmail.com'; // Email gửi mail
    $phpmailer->Password   = 'ewxo kwvq xzyx jlvb';    // Mật khẩu ứng dụng Gmail (16 ký tự)
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = 'reliveisme@gmail.com';
    $phpmailer->FromName   = 'SHOP PUBG PRO';
}

// 3. XỬ LÝ GỬI MÃ OTP QUA AJAX
add_action('wp_ajax_nopriv_send_otp_action', 'handle_send_otp');
function handle_send_otp()
{
    $email = sanitize_email($_POST['email']);
    if (!is_email($email)) {
        wp_send_json_error('Email không hợp lệ!');
    }

    $otp = rand(100000, 999999);
    $_SESSION['register_otp'] = $otp;
    $_SESSION['register_email'] = $email;

    $subject = "MÃ XÁC MINH OTP - SHOP PUBG";
    $message = "Mã xác minh của bạn là: $otp. Mã có hiệu lực trong 5 phút.";

    if (wp_mail($email, $subject, $message)) {
        wp_send_json_success('Mã xác minh đã được gửi vào Email của bạn!');
    } else {
        wp_send_json_error('Không thể gửi mail, hãy kiểm tra SMTP!');
    }
}

// 4. LOGIC ĐĂNG NHẬP & ĐĂNG KÝ (KIỂM TRA OTP)
add_action('wp_ajax_nopriv_custom_auth_action', 'handle_custom_auth');
function handle_custom_auth()
{
    $type = $_POST['auth_type'];

    if ($type == 'login') {
        $info = array('user_login' => $_POST['username'], 'user_password' => $_POST['password'], 'remember' => true);
        $user_signon = wp_signon($info, false);
        if (is_wp_error($user_signon)) {
            wp_send_json_error('Sai tài khoản hoặc mật khẩu!');
        } else {
            wp_send_json_success('Đăng nhập thành công!');
        }
    } else {
        $username = sanitize_user($_POST['reg_username']);
        $email = sanitize_email($_POST['reg_email']);
        $password = $_POST['reg_password'];
        $user_otp = $_POST['otp_code'];

        if (!isset($_SESSION['register_otp']) || $user_otp != $_SESSION['register_otp']) {
            wp_send_json_error('Mã OTP không chính xác!');
        }
        if ($email != $_SESSION['register_email']) {
            wp_send_json_error('Email không khớp với mã đã gửi!');
        }
        if (username_exists($username) || email_exists($email)) {
            wp_send_json_error('Tài khoản hoặc Email đã tồn tại!');
        } else {
            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, '_user_balance', 0); // Khởi tạo số dư

                // TỰ ĐỘNG ĐĂNG NHẬP SAU KHI ĐĂNG KÝ THÀNH CÔNG
                wp_clear_auth_cookie();
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);

                unset($_SESSION['register_otp']);
                wp_send_json_success('Chào mừng bạn đến với hệ thống!');
            } else {
                wp_send_json_error('Có lỗi xảy ra khi tạo tài khoản!');
            }
        }
    }
}

// 5. LOGIC MUA HÀNG & TRỪ TIỀN
add_action('wp_ajax_buy_nick_action', 'handle_buy_nick_logic');
function handle_buy_nick_logic()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('Vui lòng đăng nhập!');
    }

    $nick_id = intval($_POST['nick_id']);
    $user_id = get_current_user_id();

    if (get_field('is_sold', $nick_id) == 'Đã bán') {
        wp_send_json_error('Nick này đã bán!');
    }

    $price = (int)get_field('gia_sale', $nick_id);
    $balance = (int)get_user_meta($user_id, '_user_balance', true);

    if ($balance < $price) {
        wp_send_json_error('Số dư không đủ. Vui lòng nạp tiền!');
    }

    // Trừ tiền và cập nhật trạng thái
    update_user_meta($user_id, '_user_balance', $balance - $price);
    update_field('is_sold', 'Đã bán', $nick_id);
    update_post_meta($nick_id, 'buyer_id', $user_id);

    wp_send_json_success([
        'account' => get_field('tai_khoan', $nick_id),
        'password' => get_field('mat_khau', $nick_id)
    ]);
}


// 1. Chặn khách vào trang /wp-admin/
add_action('admin_init', function () {
    if (defined('DOING_AJAX') && DOING_AJAX) return;
    if (!current_user_can('administrator')) {
        wp_redirect(home_url('/tai-khoan/')); // Chuyển hướng về trang Dashboard tự tạo
        exit;
    }
});

// 2. Ẩn thanh Admin Bar (thanh đen phía trên) đối với khách
add_filter('show_admin_bar', function ($show) {
    if (!current_user_can('administrator')) return false;
    return $show;
});


// XỬ LÝ ĐỔI MẬT KHẨU TỪ DASHBOARD
add_action('wp_ajax_change_password_action', 'handle_change_password');
function handle_change_password()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('Bạn chưa đăng nhập!');
    }

    $user_id = get_current_user_id();
    $new_pass = $_POST['new_password'];

    if (strlen($new_pass) < 6) {
        wp_send_json_error('Mật khẩu phải có ít nhất 6 ký tự!');
    }

    // Thực hiện đổi mật khẩu
    wp_set_password($new_pass, $user_id);

    // Sau khi đổi pass, WordPress sẽ thoát đăng nhập, ta cần login lại tự động
    $user = get_user_by('id', $user_id);
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success('Đổi mật khẩu thành công!');
}
