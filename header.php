<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="main-header">
        <div class="container header-flex">
            <div class="logo">
                <a href="<?php echo home_url(); ?>">PUBG<span>SHOP</span></a>
            </div>

            <div class="user-meta">
                <?php if (is_user_logged_in()) :
                    $balance = get_field('user_balance', 'user_' . get_current_user_id());
                ?>
                    <div class="wallet-badge">
                        <i class="fas fa-coins"></i>
                        <span><?php echo number_format($balance ? $balance : 0); ?>đ</span>
                    </div>
                <?php else : ?>
                    <a href="<?php echo wp_login_url(); ?>" class="btn-buy">ĐĂNG NHẬP</a>
                <?php endif; ?>
            </div>
        </div>
    </header>