<?php
// 1. Nhúng các file logic từ thư mục inc/
require get_template_directory() . '/inc/cpt-setup.php';
require get_template_directory() . '/inc/wallet-functions.php';
require get_template_directory() . '/inc/ajax-handle.php';

// 2. Nhúng CSS và JS
function shop_pubg_scripts()
{
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css');
    wp_enqueue_script('purchase-js', get_template_directory_uri() . '/assets/js/purchase.js', array('jquery'), null, true);

    // Truyền biến ra JS để dùng AJAX
    wp_localize_script('purchase-js', 'shop_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'shop_pubg_scripts');

// 3. Hỗ trợ ảnh đại diện (Featured Image)
add_theme_support('post-thumbnails');


function shop_pubg_assets()
{
    // Load CSS
    wp_enqueue_style('style-main', get_template_directory_uri() . '/assets/css/style.css');

    // Load Font Awesome để hiện icon (Ví tiền, Rank)
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'shop_pubg_assets');
