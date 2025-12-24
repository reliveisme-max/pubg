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
                <div class="stat-card card-balance">
                    <i class="fa-solid fa-wallet abs-icon"></i>
                    <span>SỐ DƯ HIỆN TẠI</span>
                    <strong><?php echo number_format($balance); ?>đ</strong>
                </div>

                <div class="stat-card card-bought">
                    <i class="fa-solid fa-cart-shopping abs-icon"></i>
                    <span>NICK ĐÃ MUA</span>
                    <?php
                    $count = new WP_Query(['post_type' => 'nick-pubg', 'meta_query' => [['key' => 'buyer_id', 'value' => $user_id]]]);
                    ?>
                    <strong><?php echo $count->found_posts; ?> Acc</strong>
                </div>

                <div class="stat-card card-total">
                    <i class="fa-solid fa-circle-dollar-to-slot abs-icon"></i>
                    <span>TỔNG TIỀN NẠP</span>
                    <?php
                    $logs = get_field('lich_su_giao_dich', 'user_' . $user_id) ?: [];
                    $total_deposit = 0;
                    foreach ($logs as $log) {
                        if ($log['loai'] == 'Cộng tiền') {
                            $clean_price = (int)preg_replace('/[^0-9]/', '', $log['so_tien']);
                            $total_deposit += $clean_price;
                        }
                    }
                    ?>
                    <strong><?php echo number_format($total_deposit); ?>đ</strong>
                </div>
            </div>

            <div class="dash-section" style="margin-top: 30px; margin-bottom: 30px;">
                <h4 style="color: #f39c12; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-clock-rotate-left"></i> HOẠT ĐỘNG GẦN ĐÂY
                </h4>

                <div class="history-content-container"
                    style="background: #111; border: 1px solid #1a1a1a; border-radius: 15px; padding: 25px;">
                    <?php
                    $recent_nicks = new WP_Query(array(
                        'post_type' => 'nick-pubg',
                        'post_status' => 'any',
                        'posts_per_page' => 5,
                        'meta_query' => array(array('key' => 'buyer_id', 'value' => $user_id))
                    ));

                    if ($recent_nicks->have_posts()) : ?>
                        <table class="table-history-fix"
                            style="width: 100%; color: #fff; font-size: 13px; border-collapse: collapse;">
                            <thead>
                                <tr style="color: #fff; border-bottom: 2px solid #222;">
                                    <th class="th-ms">MÃ ĐƠN</th>
                                    <th class="th-acc">TÀI KHOẢN</th>
                                    <th class="th-date">NGÀY MUA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($recent_nicks->have_posts()) : $recent_nicks->the_post(); ?>
                                    <tr>
                                        <td class="td-ms-fix">#<?php the_ID(); ?></td>
                                        <td>
                                            <div class="acc-secret-wrapper">
                                                <div class="acc-secret-item">
                                                    <label>TK:</label> <span
                                                        id="dash-u-<?php the_ID(); ?>"><?php the_field('tai_khoan'); ?></span>
                                                    <button class="btn-copy-mini"
                                                        onclick="copyV('dash-u-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                                <div class="acc-secret-item">
                                                    <label>MK:</label> <span
                                                        id="dash-p-<?php the_ID(); ?>"><?php the_field('mat_khau'); ?></span>
                                                    <button class="btn-copy-mini"
                                                        onclick="copyV('dash-p-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="td-date-fix">
                                            <?php echo get_the_modified_date('d/m/Y'); ?> |
                                            <?php echo get_the_modified_date('H:i'); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div style="text-align: center; margin-top: 20px;">
                            <a href="<?php echo home_url('/lich-su-mua-hang'); ?>"
                                style="color: #f39c12; text-decoration: none; font-size: 12px; font-weight: 700;">XEM TẤT CẢ
                                LỊCH SỬ <i class="fa-solid fa-angles-right"></i></a>
                        </div>
                    <?php else: ?>
                        <p style="text-align:center; padding: 20px; color: #555;">Bạn chưa thực hiện giao dịch nào.</p>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </div>
            </div>

            <div class="dash-section">
                <h4 style="color: #f39c12; margin-bottom: 20px;"><i class="fa-solid fa-key"></i> ĐỔI MẬT KHẨU</h4>
                <form id="change-pass-form">
                    <div style="margin-bottom: 20px;">
                        <label style="display:block;color:#555;font-size:12px;font-weight:bold;margin-bottom:8px;">MẬT
                            KHẨU MỚI</label>
                        <input type="password" name="new_password" placeholder="Nhập mật khẩu mới của bạn..."
                            style="width:100%;background:#000;border:1px solid #222;padding:12px;color:#fff;border-radius:6px;"
                            required>
                    </div>
                    <button type="submit" class="btn-auth">CẬP NHẬT NGAY</button>
                </form>
            </div>
        </main>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const amount = urlParams.get('amount');

        if (status === 'success' && amount) {
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
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }
    });
</script>
<?php get_footer(); ?>