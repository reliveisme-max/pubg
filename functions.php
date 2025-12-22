<?php

/**
 * Shop PUBG Custom Functions
 */

require get_template_directory() . '/inc/cpt-setup.php';
require get_template_directory() . '/inc/wallet-functions.php';
require get_template_directory() . '/inc/ajax-handle.php';

function shop_pubg_scripts()
{
    $theme_uri = get_template_directory_uri();

    // 1. Nạp Font Inter
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    // 2. Nạp Swiper (CSS & JS) - Rất quan trọng cho Slider
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

    // 3. Nạp CSS chính (Dùng assets/css/style.css)
    wp_enqueue_style('main-style', $theme_uri . '/assets/css/style.css', array(), time());

    // 4. Nạp JS mua hàng
    wp_enqueue_script('purchase-js', $theme_uri . '/assets/js/purchase.js', array('jquery'), time(), true);
    wp_localize_script('purchase-js', 'shop_ajax', array('url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'shop_pubg_scripts');

add_theme_support('post-thumbnails');
add_theme_support('title-tag');

function shop_pubg_menus()
{
    register_nav_menus(array('primary_menu' => 'Menu Chính Header'));
}
add_action('after_setup_theme', 'shop_pubg_menus');
