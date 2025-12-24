<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-styled.swal2-confirm {
            background-color: var(--color-accent) !important;
            color: #000 !important;
            font-weight: 800;
        }
    </style>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="main-header">
        <div class="container">
            <div class="header-top">
                <div class="mobile-toggle" id="open-menu">
                    <i class="fa-solid fa-bars"></i>
                </div>

                <div class="logo">
                    <a href="<?php echo home_url(); ?>">
                        <img src="https://cdn3.upanh.info/upload/server-sw3/images/u.png" alt="Logo">
                    </a>
                </div>

                <div class="header-search">
                    <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                        <input type="text" placeholder="Tìm kiếm tài khoản..." name="s"
                            value="<?php echo get_search_query(); ?>">
                        <input type="hidden" name="post_type" value="nick-pubg">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="header-actions">
                    <div class="pc-balance-wrapper d-none-mobile">
                        <?php echo do_shortcode('[user_balance]'); ?>
                    </div>

                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo home_url('/nap-tien/'); ?>" class="btn-deposit d-none-mobile">Nạp Tiền</a>
                    <?php else : ?>
                        <a href="<?php echo home_url('/auth/'); ?>" class="btn-deposit d-none-mobile">Nạp Tiền</a>
                    <?php endif; ?>

                    <div class="action-icons">
                        <div class="icon-item">
                            <a href="<?php echo is_user_logged_in() ? home_url('/tai-khoan/') : home_url('/auth/'); ?>">
                                <i class="far fa-user"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mobile-search-bar" id="search-bar-mobile">
                <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                    <div class="search-inner-mobile">
                        <input type="text" placeholder="Nhập mã số hoặc tên nick..." name="s"
                            value="<?php echo get_search_query(); ?>">
                        <input type="hidden" name="post_type" value="nick-pubg">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            <nav class="header-nav" id="mobile-menu">
                <div class="mobile-menu-header">
                    <span>DANH MỤC MENU</span>
                    <i class="fa-solid fa-xmark" id="close-menu"></i>
                </div>

                <?php if (is_user_logged_in()) : ?>
                    <div class="mobile-nav-user-area d-none-desktop">
                        <a href="<?php echo home_url('/nap-tien/'); ?>" class="user-card-clickable">
                            <div class="user-card-inner">
                                <div class="user-meta-info">
                                    <span class="welcome-text">SỐ DƯ TÀI KHOẢN</span>
                                    <div class="user-balance-value">
                                        <?php echo do_shortcode('[user_balance]'); ?>
                                    </div>
                                </div>
                                <div class="user-icon-box">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary_menu',
                    'menu_class'     => 'nav-list',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
                ?>
            </nav>
        </div>
    </header>