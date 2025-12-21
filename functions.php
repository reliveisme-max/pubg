<?php
// 1. Nhúng các file logic
require get_template_directory() . '/inc/cpt-setup.php';
require get_template_directory() . '/inc/wallet-functions.php';
require get_template_directory() . '/inc/ajax-handle.php';

// 2. Nhúng CSS và JS
function shop_pubg_scripts()
{
    // Nạp Font từ Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Rajdhani:wght@600;700&display=swap');

    // Nạp Font Awesome Pro cho các Icon Bid, Wallet
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

    // Nạp CSS chính
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css');

    // Nạp JS xử lý mua hàng
    wp_enqueue_script('purchase-js', get_template_directory_uri() . '/assets/js/purchase.js', array('jquery'), null, true);

    wp_localize_script('purchase-js', 'shop_ajax', array('url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'shop_pubg_scripts');

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
