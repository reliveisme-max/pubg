<?php

/**
 * Shop PUBG Custom Functions - Consolidated Version
 */

// 1. CẤU HÌNH CƠ BẢN & NẠP SCRIPTS
require get_template_directory() . '/inc/cpt-setup.php';
require get_template_directory() . '/inc/wallet-functions.php';
// Lưu ý: Không require inc/ajax-handle.php để tránh xung đột logic mua hàng

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

// 2. KÍCH HOẠT SESSION & SMTP
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

// 3. LOGIC XÁC MINH OTP QUA EMAIL
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

// 4. LOGIC ĐĂNG NHẬP & ĐĂNG KÝ
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
            wp_send_json_error('Email không khớp!');
        }
        if (username_exists($username) || email_exists($email)) {
            wp_send_json_error('Tài khoản hoặc Email đã tồn tại!');
        }

        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            update_user_meta($user_id, 'user_balance', 0); // Khởi tạo số dư chuẩn
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            unset($_SESSION['register_otp']);
            wp_send_json_success('Chào mừng bạn đến với hệ thống!');
        } else {
            wp_send_json_error('Lỗi tạo tài khoản!');
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

    if (get_field('is_sold', $nick_id) == 'yes' || get_field('is_sold', $nick_id) == 'Đã bán') {
        wp_send_json_error('Nick này đã bán!');
    }

    $price = (int)get_field('gia_sale', $nick_id);
    $balance = (int)get_user_meta($user_id, 'user_balance', true); // Sử dụng user_balance không gạch dưới

    if ($balance < $price) {
        wp_send_json_error('Số dư không đủ. Vui lòng nạp tiền!');
    }

    update_user_meta($user_id, 'user_balance', $balance - $price);
    update_field('is_sold', 'yes', $nick_id);
    update_post_meta($nick_id, 'buyer_id', $user_id);

    wp_send_json_success(['account' => get_field('tai_khoan', $nick_id), 'password' => get_field('mat_khau', $nick_id)]);
}

// 6. SHORTCODE HIỂN THỊ SỐ DƯ (ĐÃ FIX LỖI PHP 8)
function get_user_balance_shortcode()
{
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $balance = (int)get_user_meta($user_id, 'user_balance', true); // Ép kiểu (int) để tránh lỗi number_format
        return '<span class="user-balance-nav"><i class="fa-solid fa-wallet"></i> ' . number_format($balance) . 'đ</span>';
    }
    return '';
}
add_shortcode('user_balance', 'get_user_balance_shortcode');

// 7. BẢO MẬT & ĐIỀU HƯỚNG ADMIN
add_action('admin_init', function () {
    if (defined('DOING_AJAX') && DOING_AJAX) return;
    if (!current_user_can('administrator')) {
        wp_redirect(home_url('/tai-khoan/'));
        exit;
    }
});
add_filter('show_admin_bar', function ($show) {
    return current_user_can('administrator');
});

// 8. ĐỔI MẬT KHẨU DASHBOARD
add_action('wp_ajax_change_password_action', 'handle_change_password');
function handle_change_password()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('Chưa đăng nhập!');
    }
    $user_id = get_current_user_id();
    $new_pass = $_POST['new_password'];
    if (strlen($new_pass) < 6) {
        wp_send_json_error('Mật khẩu tối thiểu 6 ký tự!');
    }
    wp_set_password($new_pass, $user_id);
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_send_json_success('Đổi mật khẩu thành công!');
}
