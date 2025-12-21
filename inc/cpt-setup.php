<?php
// Đăng ký Custom Post Type: Nick PUBG
function shop_pubg_register_post_type()
{
    $labels = array(
        'name'                  => 'Nick PUBG',
        'singular_name'         => 'Nick PUBG',
        'menu_name'             => 'Nick PUBG',
        'name_admin_bar'        => 'Nick PUBG',
        'add_new'               => 'Thêm Nick Mới',
        'add_new_item'          => 'Thêm Nick PUBG Mới',
        'new_item'              => 'Nick Mới',
        'edit_item'             => 'Sửa Nick',
        'view_item'             => 'Xem Nick',
        'all_items'             => 'Tất cả Nick',
        'search_items'          => 'Tìm Nick PUBG',
        'not_found'             => 'Không tìm thấy Nick nào.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'nick-pubg'), // Đường dẫn web sẽ là /nick-pubg/ten-nick
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-games', // Icon tay cầm chơi game
        'supports'           => array('title', 'thumbnail', 'editor'), // Hỗ trợ Tiêu đề, Ảnh đại diện và Mô tả
        'show_in_rest'       => false, // Hỗ trợ trình soạn thảo mới Gutenberg
    );

    register_post_type('nick-pubg', $args);
}
add_action('init', 'shop_pubg_register_post_type');
