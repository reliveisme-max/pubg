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

// 5. LOGIC MUA HÀNG, TRỪ TIỀN & GHI LỊCH SỬ GIAO DỊCH (BẢN FULL)
add_action('wp_ajax_buy_nick_action', 'handle_buy_nick_logic');
function handle_buy_nick_logic()
{
    // Kiểm tra đăng nhập
    if (!is_user_logged_in()) {
        wp_send_json_error('Vui lòng đăng nhập để mua hàng!');
    }

    $nick_id = intval($_POST['nick_id']);
    $user_id = get_current_user_id();

    // 1. Kiểm tra trạng thái bán
    if (get_field('is_sold', $nick_id) == 'yes' || get_field('is_sold', $nick_id) == 'Đã bán') {
        wp_send_json_error('Nick này đã bán!');
    }

    // 2. Tính toán giá (Ưu tiên giá sale)
    $price_origin = (int)get_field('gia_ban', $nick_id);
    $price_sale   = (int)get_field('gia_sale', $nick_id);
    $final_price  = ($price_sale > 0) ? $price_sale : $price_origin;

    // 3. Kiểm tra số dư
    $balance = (int)get_field('user_balance', 'user_' . $user_id);

    if ($balance < $final_price) {
        wp_send_json_error('Số dư không đủ. Vui lòng nạp thêm tiền!');
    }

    $new_balance = $balance - $final_price;

    // 4. Cập nhật Database
    // Cập nhật số dư User và trạng thái Nick
    update_field('user_balance', $new_balance, 'user_' . $user_id);
    update_field('is_sold', 'yes', $nick_id);
    update_post_meta($nick_id, 'buyer_id', $user_id);

    // 5. GHI LỊCH SỬ GIAO DỊCH VÀO ACF USER
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $history_row = array(
        'ngay'       => date('H:i - d/m/Y'),
        'loai'       => 'Trừ tiền',
        'so_tien'    => '-' . number_format($final_price) . 'đ',
        'noi_dung'   => 'Mua Nick #' . $nick_id,
        'so_du_cuoi' => number_format($new_balance) . 'đ'
    );
    // Sử dụng add_row của ACF để thêm vào repeater 'lich_su_giao_dich'
    add_row('lich_su_giao_dich', $history_row, 'user_' . $user_id);

    // 6. TRẢ VỀ DỮ LIỆU THÀNH CÔNG (BAO GỒM SỐ DƯ MỚI)
    wp_send_json_success([
        'account'     => get_field('tai_khoan', $nick_id),
        'password'    => get_field('mat_khau', $nick_id),
        'new_balance' => number_format($new_balance) . 'đ'
    ]);

    wp_die();
}

// 6. TỰ ĐỘNG ẨN NICK ĐÃ BÁN Ở CÁC TRANG DANH MỤC
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && (is_post_type_archive('nick-pubg') || is_tax())) {
        $query->set('meta_query', array(
            array('key' => 'is_sold', 'value' => 'yes', 'compare' => '!=')
        ));
    }
});
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

// Kích hoạt trang Options để chỉnh Slider và Banner
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'    => 'Cấu hình Banner',
        'menu_title'    => 'Cấu hình Banner',
        'menu_slug'     => 'acf-options', // Slug này phải khớp với giá trị "value" trong file JSON đã import
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}


/**
 * Dọn dẹp và làm đẹp trang cá nhân thành viên
 */
add_action('admin_head', 'shoptule_clean_user_profile_ui');
function shoptule_clean_user_profile_ui()
{
    // Chỉ chạy trong trang chỉnh sửa User
    $screen = get_current_screen();
    if ($screen->id !== 'profile' && $screen->id !== 'user-edit') return;
?>
    <style>
        /* 1. Ẩn toàn bộ các mục mặc định rườm rà của WordPress */
        #profile-page h2,
        .user-rich-editing-wrap,
        .user-admin-bar-front-wrap,
        .user-syntax-highlighting-wrap,
        .user-comment-shortcuts-wrap,
        .user-admin-color-wrap,
        .user-first-name-wrap,
        .user-last-name-wrap,
        .user-nickname-wrap,
        .user-display-name-wrap,
        .user-email-wrap,
        .user-url-wrap,
        .user-description-wrap,
        .user-profile-picture-wrap,
        .user-language-wrap,
        .user-sessions-wrap {
            display: none !important;
        }

        /* 2. Làm đẹp vùng ACF User */
        .acf-field-setting-fc {
            background: #f9f9f9;
        }

        #poststuff {
            padding-top: 0;
        }

        /* 3. Tùy chỉnh riêng cho ô Số dư tài khoản */
        .acf-field[data-name="so_du_tai_khoan"] input {
            font-size: 20px !important;
            font-weight: bold !important;
            color: #d35400 !important;
            border: 2px solid #e67e22 !important;
            padding: 10px !important;
        }
    </style>
<?php
}



/**
 * 1. Hiển thị cột Số dư vào danh sách Thành viên Admin
 */
add_filter('manage_users_columns', 'shoptule_add_user_balance_column');
function shoptule_add_user_balance_column($columns)
{
    $columns['user_balance'] = 'Số dư ví';
    return $columns;
}
add_filter('manage_users_custom_column', 'shoptule_show_user_balance_content', 10, 3);
function shoptule_show_user_balance_content($value, $column_name, $user_id)
{
    if ('user_balance' == $column_name) {
        $balance = get_field('user_balance', 'user_' . $user_id);
        return '<span style="font-weight:bold; color:#27ae60">' . number_format((int)$balance ?: 0) . 'đ</span>';
    }
    return $value;
}

/**
 * 2. Vẽ bảng thống kê số dư toàn bộ thành viên
 */
add_filter('acf/load_field/key=field_user_balance_dashboard', 'shoptule_render_user_balance_table');
function shoptule_render_user_balance_table($field)
{
    $users = get_users(array('fields' => array('ID', 'user_login', 'display_name')));
    $html = '<div style="background: #1e1e1e; border-radius: 8px; overflow: hidden; margin-bottom: 20px;">';
    $html .= '<table style="width: 100%; border-collapse: collapse; color: #ccc; font-size: 13px;">';
    $html .= '<thead style="background: #2c3e50; color: #fff;"><tr>';
    $html .= '<th style="padding: 12px; text-align: left;">Username</th><th style="padding: 12px; text-align: left;">Tên hiển thị</th><th style="padding: 12px; text-align: right;">Số dư hiện tại</th>';
    $html .= '</tr></thead><tbody>';
    foreach ($users as $user) {
        $balance = get_field('user_balance', 'user_' . $user->ID);
        $html .= '<tr style="border-bottom: 1px solid #333;">';
        $html .= '<td style="padding: 10px 12px; color: #f39c12;">' . esc_html($user->user_login) . '</td>';
        $html .= '<td style="padding: 10px 12px;">' . esc_html($user->display_name) . '</td>';
        $html .= '<td style="padding: 10px 12px; text-align: right; font-weight: bold; color: #2ecc71;">' . number_format((int)$balance ?: 0) . 'đ</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table></div>';
    $field['message'] = $html;
    return $field;
}

/**
 * 3. Xử lý Nạp/Rút tiền và TỰ ĐỘNG GHI LỊCH SỬ
 */
add_action('acf/save_post', 'shoptule_sync_member_data_from_options', 20);
function shoptule_sync_member_data_from_options($post_id)
{
    if ($post_id !== 'options') return;

    $user_list = get_field('quick_user_list', 'option');

    if (is_array($user_list) && !empty($user_list)) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_time = date('H:i - d/m/Y');

        foreach ($user_list as $row) {
            $user_id = $row['target_user'];
            $action  = $row['action_type']; // 'plus' hoặc 'minus'
            $amount  = (int)$row['target_amount'];

            if ($user_id && $amount > 0) {
                $old_balance = (int)get_field('user_balance', 'user_' . $user_id);

                if ($action === 'minus') {
                    $new_total = max(0, $old_balance - $amount);
                    $type_text = 'Trừ tiền';
                    $amount_text = '-' . number_format($amount) . 'đ';
                } else {
                    $new_total = $old_balance + $amount;
                    $type_text = 'Cộng tiền';
                    $amount_text = '+' . number_format($amount) . 'đ';
                }

                // Cập nhật số dư
                update_field('user_balance', $new_total, 'user_' . $user_id);

                // GHI LỊCH SỬ GIAO DỊCH VÀO USER
                $history_row = array(
                    'ngay'       => $current_time,
                    'loai'       => $type_text,
                    'so_tien'    => $amount_text,
                    'noi_dung'   => 'Admin điều chỉnh số dư',
                    'so_du_cuoi' => number_format($new_total) . 'đ'
                );
                add_row('lich_su_giao_dich', $history_row, 'user_' . $user_id);

                // Đổi mật khẩu nếu có nhập
                if (!empty($row['target_password'])) wp_set_password($row['target_password'], $user_id);
            }
        }
        update_field('quick_user_list', array(), 'option');
    }
}

/**
 * 4. AJAX & Script hiển thị số dư nhanh
 */
add_action('wp_ajax_get_user_balance_quick', 'shoptule_ajax_get_balance');
function shoptule_ajax_get_balance()
{
    $user_id = intval($_POST['user_id']);
    $balance = get_field('user_balance', 'user_' . $user_id);
    echo number_format((int)$balance ?: 0) . 'đ';
    wp_die();
}

add_action('admin_footer', 'shoptule_admin_wallet_js');
function shoptule_admin_wallet_js()
{
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
}


// Tự động ẩn Nick đã bán khỏi các trang danh sách
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && (is_post_type_archive('nick-pubg') || is_tax())) {
        $meta_query = array(
            array(
                'key'     => 'is_sold',
                'value'   => 'yes',
                'compare' => '!='
            )
        );
        $query->set('meta_query', $meta_query);
    }
});
