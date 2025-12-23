<?php
/* Template Name: Dashboard Cá Nhân */
if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth/'));
    exit;
}
get_header();

$user = wp_get_current_user();
$user_id = $user->ID;
$balance = (int)get_field('user_balance', 'user_' . $user_id);
?>

<style>
    .dashboard-layout {
        display: flex;
        gap: 20px;
        margin-top: 30px;
    }

    .dashboard-sidebar {
        width: 280px;
        flex-shrink: 0;
    }

    .user-profile-card {
        background: #111;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        border: 1px solid #1a1a1a;
        margin-bottom: 15px;
    }

    .user-avatar-circle {
        width: 60px;
        height: 60px;
        background: #f39c12;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
        color: #fff;
        font-weight: bold;
        border: 2px solid #222;
    }

    .user-balance-box {
        background: #1a150e;
        color: #f39c12;
        padding: 10px;
        border-radius: 8px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 15px;
        border: 1px solid #33220a;
    }

    .dashboard-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .dashboard-menu li {
        margin-bottom: 8px;
    }

    .dashboard-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: #888;
        text-decoration: none;
        border-radius: 8px;
        transition: 0.3s;
        font-size: 14px;
    }

    .dashboard-menu a:hover {
        background: #111;
        color: #fff;
    }

    .dashboard-menu a.active {
        background: #f39c12;
        color: #000;
        font-weight: bold;
    }

    .dash-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: #111;
        border: 1px solid #1a1a1a;
        padding: 25px;
        border-radius: 12px;
        border-left: 4px solid #f39c12;
    }

    .stat-card span {
        display: block;
        color: #555;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .stat-card strong {
        display: block;
        color: #fff;
        font-size: 24px;
        font-weight: 800;
    }

    .dash-section {
        background: #111;
        border: 1px solid #1a1a1a;
        padding: 30px;
        border-radius: 12px;
    }

    .dash-section h4 {
        color: #fff;
        font-size: 16px;
        margin-bottom: 25px;
        border-bottom: 1px solid #222;
        padding-bottom: 15px;
    }

    .btn-auth {
        background: #f39c12;
        border: none;
        color: #000;
        padding: 15px 30px;
        border-radius: 8px;
        font-weight: 800;
        cursor: pointer;
        width: 100%;
        transition: 0.3s;
    }
</style>

<div class="container" style="padding-bottom: 100px;">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <div class="user-profile-card">
                <div class="user-avatar-circle"><?php echo strtoupper(substr($user->user_login, 0, 2)); ?></div>
                <h3 style="color: #fff; margin-bottom: 15px; font-size: 18px;"><?php echo $user->display_name; ?></h3>
                <div class="user-balance-box"><i class="fa-solid fa-wallet"></i> <?php echo number_format($balance); ?>đ
                </div>
            </div>
            <ul class="dashboard-menu">
                <li><a href="<?php echo home_url('/tai-khoan/'); ?>" class="active"><i class="fa-solid fa-gauge"></i>
                        Tổng quan</a></li>
                <li><a href="<?php echo home_url('/lich-su-mua-hang/'); ?>"><i
                            class="fa-solid fa-clock-rotate-left"></i> Lịch sử mua hàng</a></li>
                <li><a href="<?php echo home_url('/nap-tien/'); ?>"><i class="fa-solid fa-credit-card"></i> Nạp tiền</a>
                </li>
                <li><a href="<?php echo wp_logout_url(home_url()); ?>" style="color: #e74c3c;"><i
                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main style="flex: 1;">
            <div class="dash-stats">
                <div class="stat-card"><span>SỐ DƯ HIỆN
                        TẠI</span><strong><?php echo number_format($balance); ?>đ</strong></div>
                <div class="stat-card"><span>NICK ĐÃ MUA</span>
                    <?php $count = new WP_Query(['post_type' => 'nick-pubg', 'meta_query' => [['key' => 'buyer_id', 'value' => $user_id]]]); ?>
                    <strong><?php echo $count->found_posts; ?> Acc</strong>
                </div>
            </div>
            <div class="dash-section">
                <h4>ĐỔI MẬT KHẨU</h4>
                <form id="change-pass-form">
                    <div style="margin-bottom: 20px;"><label
                            style="display:block;color:#555;font-size:12px;font-weight:bold;margin-bottom:8px;">MẬT KHẨU
                            MỚI</label><input type="password" name="new_password"
                            style="width:100%;background:#000;border:1px solid #222;padding:12px;color:#fff;border-radius:6px;"
                            required></div>
                    <button type="submit" class="btn-auth">CẬP NHẬT NGAY</button>
                </form>
            </div>
        </main>
    </div>
</div>
<?php get_footer(); ?>