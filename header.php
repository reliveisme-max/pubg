<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <header class="main-header">
        <div class="container header-flex">
            <div class="logo">
                <a href="<?php echo home_url(); ?>">DYAT<span>PUBG</span></a>
            </div>

            <div class="user-meta">
                <?php if (is_user_logged_in()) : 
                $balance = get_field('user_balance', 'user_' . get_current_user_id());
            ?>
                <div style="display: flex; align-items: center; gap: 20px;">
                    <a href="<?php echo home_url('/lich-su-mua-hang/'); ?>"
                        style="color: #888; text-decoration: none; font-size: 14px; font-weight: 500;">Lịch sử</a>
                    <div class="wallet-badge">
                        <i class="fas fa-wallet"></i> <?php echo number_format($balance ? $balance : 0); ?>đ
                    </div>
                </div>
                <?php else : ?>
                <a href="<?php echo wp_login_url(); ?>" class="btn-bid">ĐĂNG NHẬP</a>
                <?php endif; ?>
            </div>
        </div>
    </header>