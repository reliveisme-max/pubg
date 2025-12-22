<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body <?php body_class(); ?>>

    <header class="main-header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <a href="<?php echo home_url(); ?>">
                        <img src="https://pubg.shoprito.com/assets/img/logo.png" alt="Shoprito Logo"
                            style="height: 45px;">
                    </a>
                </div>

                <div class="header-search">
                    <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                        <input type="text" placeholder="Tìm kiếm tài khoản..." name="s">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="header-actions">
                    <a href="<?php echo home_url('/nap-tien/'); ?>" class="btn-deposit">Nạp Tiền</a>

                    <div class="action-icons">
                        <div class="icon-item"><i class="far fa-bell"></i></div>
                        <div class="icon-item">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge">0</span>
                        </div>
                        <div class="icon-item">
                            <?php if (is_user_logged_in()) : ?>
                            <a href="<?php echo home_url('/account'); ?>"><i class="far fa-user"></i></a>
                            <?php else : ?>
                            <a href="<?php echo wp_login_url(); ?>"><i class="far fa-user"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="header-nav">
                <div class="container">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary_menu', // Phải khớp với tên đã đăng ký ở bước 1
                        'menu_class'     => 'nav-list',    // Giữ nguyên class CSS để không bị vỡ giao diện
                        'container'      => false,
                        'fallback_cb'    => false,
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    ));
                    ?>
                </div>
            </nav>
        </div>
    </header>