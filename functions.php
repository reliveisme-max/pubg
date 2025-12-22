<?php

/**
 * Shop PUBG Custom Functions
 * File: functions.php
 */

// 1. Nhúng các file logic xử lý hệ thống
require get_template_directory() . '/inc/cpt-setup.php';      // Thiết lập Custom Post Type Nick PUBG
require get_template_directory() . '/inc/wallet-functions.php'; // Quản lý số dư người dùng
require get_template_directory() . '/inc/ajax-handle.php';    // Xử lý mua hàng bằng AJAX

// 2. Nhúng CSS và JS với cơ chế chống Cache (Auto-Versioning)
function shop_pubg_scripts()
{
    // Đường dẫn tuyệt đối đến thư mục theme để kiểm tra thời gian file
    $theme_path = get_template_directory();
    $theme_uri  = get_template_directory_uri();

    // Nạp Font từ Google Fonts (Không cần chống cache vì là link ngoài)
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Rajdhani:wght@600;700&display=swap');

    // Nạp Font Awesome cho các Icon
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

    // Thêm Swiper Slider (CSS & JS)
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

    // --- Nạp CSS chính có chống Cache ---
    $css_file = '/assets/css/style.css';
    $css_ver  = file_exists($theme_path . $css_file) ? filemtime($theme_path . $css_file) : '1.0';
    wp_enqueue_style('main-style', $theme_uri . $css_file, array(), $css_ver);

    // --- Nạp JS xử lý mua hàng có chống Cache ---
    $js_file = '/assets/js/purchase.js';
    $js_ver  = file_exists($theme_path . $js_file) ? filemtime($theme_path . $js_file) : '1.0';
    wp_enqueue_script('purchase-js', $theme_uri . $js_file, array('jquery'), $js_ver, true);

    // Cung cấp URL xử lý AJAX cho file JS
    wp_localize_script('purchase-js', 'shop_ajax', array('url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'shop_pubg_scripts');

// 3. Hỗ trợ các tính năng cơ bản của Theme
add_theme_support('post-thumbnails');
add_theme_support('title-tag');

// Thêm trang cài đặt Slider vào Admin
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'    => 'Cài đặt Slider',
        'menu_title'    => 'Cài đặt Slider',
        'menu_slug'     => 'slider-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

// Đăng ký vị trí Menu cho Theme
function shop_pubg_register_menus()
{
    register_nav_menus(array(
        'primary_menu' => __('Menu Chính Header', 'shop-pubg'),
    ));
}
add_action('after_setup_theme', 'shop_pubg_register_menus');

// Chỉ tìm kiếm trong Post Type 'nick-pubg' để kết quả chính xác hơn
function shop_pubg_search_filter($query)
{
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('nick-pubg'));
    }
    return $query;
}
add_filter('pre_get_posts', 'shop_pubg_search_filter');
