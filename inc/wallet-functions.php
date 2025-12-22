<?php
// Hiện cột số dư trong trang quản lý Thành viên (Admin)
add_filter('manage_users_columns', 'add_balance_column');
function add_balance_column($columns)
{
    $columns['user_balance'] = 'Số dư hiện tại';
    return $columns;
}

add_filter('manage_users_custom_column', 'show_balance_column', 10, 3);
// Sửa trong inc/wallet-functions.php
function show_balance_column($output, $column_name, $user_id)
{
    if ($column_name == 'user_balance') {
        // Thay get_field bằng get_user_meta để đồng bộ với dashboard
        $balance = (int)get_user_meta($user_id, '_user_balance', true);
        return '<strong>' . number_format($balance) . ' đ</strong>';
    }
    return $output;
}
