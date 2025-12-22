<?php
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
        // Lấy đúng từ key user_balance không gạch dưới
        $balance = (int)get_user_meta($user_id, 'user_balance', true);
        return '<strong>' . number_format($balance) . ' đ</strong>';
    }
    return $output;
}
