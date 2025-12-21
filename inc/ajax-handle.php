<?php
// Đăng ký hành động AJAX cho cả khách và admin
add_action('wp_ajax_buy_nick_action', 'handle_buy_nick_ajax');

function handle_buy_nick_ajax()
{
    // 1. Kiểm tra đăng nhập
    if (!is_user_logged_in()) {
        wp_send_json_error('Bạn cần đăng nhập để thực hiện mua hàng!');
    }

    $nick_id = intval($_POST['nick_id']);
    $user_id = get_current_user_id();

    // 2. Lấy thông tin từ ACF
    $price = (int)get_field('gia_ban', $nick_id);
    $is_sold = get_field('is_sold', $nick_id);
    $acc = get_field('tai_khoan', $nick_id);
    $pass = get_field('mat_khau', $nick_id);

    // 3. Kiểm tra trạng thái Nick
    if ($is_sold === 'yes') {
        wp_send_json_error('Nick này đã được bán cho người khác!');
    }

    // 4. Kiểm tra ví tiền khách hàng
    $balance = (int)get_field('user_balance', 'user_' . $user_id);

    if ($balance < $price) {
        wp_send_json_error('Số dư tài khoản không đủ. Vui lòng nạp thêm!');
    }

    // 5. THỰC HIỆN TRỪ TIỀN VÀ BÀN GIAO
    $new_balance = $balance - $price;
    update_field('user_balance', $new_balance, 'user_' . $user_id);
    update_field('is_sold', 'yes', $nick_id);
    // Lưu ID người mua vào Nick để xem lại lịch sử
    update_post_meta($nick_id, 'buyer_id', $user_id);

    // Trả về thông tin tài khoản mật khẩu
    wp_send_json_success(array(
        'message' => 'Mua hàng thành công!',
        'account' => $acc,
        'password' => $pass
    ));

    wp_die();
}
