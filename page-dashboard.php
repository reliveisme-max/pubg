<?php
/* Template Name: Dashboard Cá Nhân */
get_header();
$user = wp_get_current_user();
$balance = get_user_meta($user->ID, '_user_balance', true) ?: 0;
?>

<div class="container dashboard-wrapper" style="margin-top: 50px; padding-bottom: 80px;">
    <div class="dashboard-grid">
        <div class="dash-sidebar">
            <div class="user-info-card">
                <div class="user-avatar">
                    <i class="fa-duotone fa-solid fa-user-gear"></i>
                </div>
                <h3>Xin chào, <?php echo $user->display_name; ?>!</h3>
                <p><?php echo $user->user_email; ?></p>
                <div class="balance-tag">
                    <i class="fa-solid fa-wallet"></i> <?php echo number_format($balance); ?>đ
                </div>
            </div>
            <nav class="dash-nav">
                <a href="<?php echo home_url('/tai-khoan/'); ?>" class="active"><i class="fa-solid fa-grid-2"></i> Tổng
                    quan</a>
                <a href="<?php echo home_url('/lich-su-mua-hang/'); ?>"><i class="fa-solid fa-clock-rotate-left"></i>
                    Lịch sử mua</a>
                <a href="<?php echo home_url('/nap-tien/'); ?>"><i class="fa-solid fa-qrcode"></i> Nạp tiền</a>
                <a href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa-solid fa-right-from-bracket"></i> Đăng
                    xuất</a>
            </nav>
        </div>

        <div class="dash-content">
            <div class="dash-stats">
                <div class="stat-card">
                    <span>SỐ DƯ HIỆN TẠI</span>
                    <strong><?php echo number_format($balance); ?>đ</strong>
                </div>
                <div class="stat-card">
                    <span>NICK ĐÃ SỞ HỮU</span>
                    <?php
                    $count = new WP_Query(['post_type' => 'nick-pubg', 'meta_query' => [['key' => 'buyer_id', 'value' => $user->ID]]]);
                    ?>
                    <strong><?php echo $count->found_posts; ?> Acc</strong>
                </div>
            </div>

            <div class="dash-section">
                <h4><i class="fa-solid fa-shield-keyhole"></i> ĐỔI MẬT KHẨU</h4>
                <form id="change-pass-form">
                    <div class="input-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="new_pass" required>
                    </div>
                    <button type="submit" class="btn-auth">CẬP NHẬT NGAY</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>