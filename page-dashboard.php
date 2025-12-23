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
                            class="fa-solid fa-clock-rotate-left"></i> Lịch sử giao dịch</a></li>
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
<script>
    jQuery(document).ready(function($) {
        // Lấy thông số từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const amount = urlParams.get('amount');

        if (status === 'success' && amount) {
            // Hiển thị thông báo thành công bằng SweetAlert2
            Swal.fire({
                title: 'NẠP TIỀN THÀNH CÔNG!',
                html: 'Chúc mừng! Bạn vừa nạp thành công <b style="color: #f39c12;">' + Number(amount)
                    .toLocaleString() + 'đ</b> vào tài khoản.',
                icon: 'success',
                background: '#111',
                color: '#fff',
                confirmButtonColor: '#f39c12',
                confirmButtonText: 'TUYỆT VỜI',
                backdrop: `rgba(0,0,0,0.8)`
            }).then((result) => {
                // Sau khi bấm OK, xóa các tham số trên URL cho sạch link
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }
    });
</script>
<?php get_footer(); ?>