<?php
add_action('wp_ajax_buy_nick_action', 'handle_buy_nick_ajax');

function handle_buy_nick_ajax()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('Bạn cần đăng nhập!');
    }

    $nick_id = intval($_POST['nick_id']);
    $user_id = get_current_user_id();

    $price = (int)get_field('gia_ban', $nick_id);
    $is_sold = get_field('is_sold', $nick_id);
    $acc = get_field('tai_khoan', $nick_id);
    $pass = get_field('mat_khau', $nick_id);

    if ($is_sold === 'yes') {
        wp_send_json_error('Nick đã bán!');
    }

    $balance = (int)get_user_meta($user_id, '_user_balance', true);

    if ($balance < $price) {
        wp_send_json_error('Số dư không đủ!');
    }

    $new_balance = $balance - $price;
    update_user_meta($user_id, '_user_balance', $new_balance);
    update_field('user_balance', $new_balance, 'user_' . $user_id);
    update_field('is_sold', 'yes', $nick_id);

    // Lưu ID người mua để trang Lịch sử có thể lấy dữ liệu
    update_post_meta($nick_id, 'buyer_id', $user_id);

    wp_send_json_success(array(
        'account' => $acc,
        'password' => $pass
    ));

    wp_die();
}
