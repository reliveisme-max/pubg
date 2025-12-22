<?php
/* Template Name: Dashboard Cá Nhân */
if (!is_user_logged_in()) { wp_redirect(home_url('/auth/')); exit; }
get_header();
$user = wp_get_current_user();
// Ép kiểu (int) để tránh lỗi number_format trên PHP 8
$balance = (int)get_user_meta($user->ID, 'user_balance', true); 
?>
<div class="container dashboard-wrapper" style="margin-top: 50px; padding-bottom: 80px;">
    <div class="dashboard-grid">
        <div class="dash-sidebar">
            <div class="user-info-card">
                <div class="user-avatar"><i class="fa-solid fa-user-gear"></i></div>
                <h3><?php echo $user->display_name; ?></h3>
                <div class="balance-tag"><i class="fa-solid fa-wallet"></i> <?php echo number_format($balance); ?>đ
                </div>
            </div>
            <nav class="dash-nav">
                <a href="<?php echo home_url('/tai-khoan/'); ?>" class="active">Tổng quan</a>
                <a href="<?php echo home_url('/lich-su-mua-hang/'); ?>">Lịch sử mua</a>
                <a href="<?php echo home_url('/nap-tien/'); ?>">Nạp tiền</a>
                <a href="<?php echo wp_logout_url(home_url()); ?>">Đăng xuất</a>
            </nav>
        </div>
        <div class="dash-content">
            <div class="dash-stats">
                <div class="stat-card"><span>SỐ DƯ HIỆN
                        TẠI</span><strong><?php echo number_format($balance); ?>đ</strong></div>
                <div class="stat-card"><span>NICK ĐÃ MUA</span>
                    <?php $count = new WP_Query(['post_type' => 'nick-pubg', 'meta_query' => [['key' => 'buyer_id', 'value' => $user->ID]]]); ?>
                    <strong><?php echo $count->found_posts; ?> Acc</strong>
                </div>
            </div>
            <div class="dash-section">
                <h4>ĐỔI MẬT KHẨU</h4>
                <form id="change-pass-form">
                    <div class="input-item"><label>Mật khẩu mới</label><input type="password" name="new_password"
                            required></div>
                    <button type="submit" class="btn-auth">CẬP NHẬT</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>