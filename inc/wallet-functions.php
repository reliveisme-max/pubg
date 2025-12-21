<?php
// Thêm cột số dư vào trang quản lý User để chủ shop dễ nhìn
add_filter('manage_users_columns', 'add_balance_column');
function add_balance_column($columns)
{
    $columns['user_balance'] = 'Số dư (đ)';
    return $columns;
}

add_filter('manage_users_custom_column', 'show_balance_column', 10, 3);
function show_balance_column($output, $column_name, $user_id)
{
    if ($column_name == 'user_balance') {
        $balance = get_field('user_balance', 'user_' . $user_id);
        return number_format($balance ? $balance : 0) . ' đ';
    }
    return $output;
}
