<?php
// 1. Nhúng các file logic từ thư mục inc/
require get_template_directory() . '/inc/cpt-setup.php';
require get_template_directory() . '/inc/wallet-functions.php';
if (file_exists(get_template_directory() . '/inc/ajax-handle.php')) {
    require get_template_directory() . '/inc/ajax-handle.php';
}

// 2. Nhúng CSS và JS
function shop_pubg_scripts()
{
    // CSS chính với giao diện Premium
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css');

    // JS xử lý mua hàng AJAX
    wp_enqueue_script('purchase-js', get_template_directory_uri() . '/assets/js/purchase.js', array('jquery'), null, true);

    // Truyền biến AJAX vào JS
    wp_localize_script('purchase-js', 'shop_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'shop_pubg_scripts');

// 3. Hỗ trợ ảnh đại diện và các tính năng bổ sung
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
