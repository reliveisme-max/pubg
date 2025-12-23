<?php
/* Template Name: Lịch sử Giao dịch */
get_header();
if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth'));
    exit;
}

$user_id = get_current_user_id();
$user_info = wp_get_current_user();
$current_balance = get_field('user_balance', 'user_' . $user_id);
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'nicks';
?>

<div class="container" style="padding-bottom: 100px;">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <div class="user-profile-card">
                <div class="user-avatar-circle"><?php echo strtoupper(substr($user_info->user_login, 0, 2)); ?></div>
                <h3 style="color: #fff; margin-bottom: 15px; font-size: 18px;"><?php echo $user_info->user_login; ?>
                </h3>
                <div class="user-balance-box"><i class="fa-solid fa-wallet"></i>
                    <?php echo number_format($current_balance ?: 0); ?>đ</div>
            </div>
            <ul class="dashboard-menu">
                <li><a href="<?php echo home_url('/tai-khoan'); ?>"><i class="fa-solid fa-gauge"></i> Tổng quan</a></li>
                <li><a href="<?php echo home_url('/lich-su-mua-hang'); ?>" class="active"><i
                            class="fa-solid fa-clock-rotate-left"></i> Lịch sử giao dịch</a></li>
                <li><a href="<?php echo home_url('/nap-tien'); ?>"><i class="fa-solid fa-credit-card"></i> Nạp tiền</a>
                </li>
                <li><a href="<?php echo wp_logout_url(home_url()); ?>" style="color: #e74c3c;"><i
                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main style="flex: 1;">
            <div class="history-tabs-nav" style="display: flex; gap: 10px; margin-bottom: 25px;">
                <a href="?tab=nicks" class="tab-trigger <?php echo $active_tab == 'nicks' ? 'active' : ''; ?>"
                    style="text-decoration:none; padding: 12px 25px; border-radius:12px; background: <?php echo $active_tab == 'nicks' ? '#f39c12' : '#111'; ?>; color: <?php echo $active_tab == 'nicks' ? '#000' : '#fff'; ?>; font-weight: 800; border: 1px solid #222;">MUA
                    NICK</a>
                <a href="?tab=money" class="tab-trigger <?php echo $active_tab == 'money' ? 'active' : ''; ?>"
                    style="text-decoration:none; padding: 12px 25px; border-radius:12px; background: <?php echo $active_tab == 'money' ? '#f39c12' : '#111'; ?>; color: <?php echo $active_tab == 'money' ? '#000' : '#fff'; ?>; font-weight: 800; border: 1px solid #222;">BIẾN
                    ĐỘNG SỐ DƯ</a>
            </div>

            <div style="background: #111; border: 1px solid #1a1a1a; border-radius: 15px; padding: 25px;">
                <?php if ($active_tab == 'nicks'): ?>
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $nicks = new WP_Query(array('post_type' => 'nick-pubg', 'post_status' => 'any', 'posts_per_page' => 10, 'paged' => $paged, 'meta_query' => array(array('key' => 'buyer_id', 'value' => $user_id))));
                    if ($nicks->have_posts()) : ?>
                        <table style="width: 100%; color: #fff; font-size: 13px; border-collapse: collapse;">
                            <thead>
                                <tr style="color: #fff; border-bottom: 2px solid #222;">
                                    <th style="padding: 15px; text-align: left;">MÃ ĐƠN</th>
                                    <th style="padding: 15px; text-align: left;">THÔNG TIN TÀI KHOẢN</th>
                                    <th style="padding: 15px; text-align: right;">NGÀY MUA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($nicks->have_posts()) : $nicks->the_post(); ?>
                                    <tr style="border-bottom: 1px solid #1a1a1a;">
                                        <td style="padding: 15px; color: #f39c12; font-weight: 900;">#<?php the_ID(); ?></td>
                                        <td style="padding: 15px;">
                                            <div class="acc-secret-wrapper">
                                                <div class="acc-secret-item">
                                                    <label>TK:</label> <span
                                                        id="u-<?php the_ID(); ?>"><?php the_field('tai_khoan'); ?></span>
                                                    <button class="btn-copy-mini" onclick="copyV('u-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                                <div style="width:1px; height:15px; background:#222; margin: 0 5px;"></div>
                                                <div class="acc-secret-item">
                                                    <label>MK:</label> <span
                                                        id="p-<?php the_ID(); ?>"><?php the_field('mat_khau'); ?></span>
                                                    <button class="btn-copy-mini" onclick="copyV('p-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 15px; text-align: right; color: #bbb;">
                                            <?php echo get_the_modified_date('d/m/Y H:i'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div class="history-pagination">
                            <?php echo paginate_links(array('total' => $nicks->max_num_pages, 'current' => $paged, 'format' => '/page/%#%/?tab=nicks', 'prev_text' => '<i class="fa-solid fa-chevron-left"></i>', 'next_text' => '<i class="fa-solid fa-chevron-right"></i>')); ?>
                        </div>
                    <?php else: echo '<p style="text-align:center; padding: 40px; color: #fff;">Chưa có đơn hàng nào.</p>';
                    endif;
                    wp_reset_postdata(); ?>

                <?php else: ?>
                    <table style="width: 100%; color: #fff; font-size: 13px; border-collapse: collapse;">
                        <thead>
                            <tr style="color: #fff; border-bottom: 2px solid #222;">
                                <th style="padding: 15px; text-align: left;">THỜI GIAN</th>
                                <th style="padding: 15px; text-align: center;">HÀNH ĐỘNG</th>
                                <th style="padding: 15px; text-align: right;">SỐ TIỀN</th>
                                <th style="padding: 15px; text-align: right;">SỐ DƯ CUỐI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $all_logs = get_field('lich_su_giao_dich', 'user_' . $user_id) ?: [];
                            $all_logs = array_reverse($all_logs);
                            $per_page = 10;
                            $total_pages = ceil(count($all_logs) / $per_page);
                            $current_page = isset($_GET['p_m']) ? max(1, intval($_GET['p_m'])) : 1;
                            $logs = array_slice($all_logs, ($current_page - 1) * $per_page, $per_page);
                            if ($logs): foreach ($logs as $l): ?>
                                    <tr style="border-bottom: 1px solid #1a1a1a;">
                                        <td style="padding: 15px; color: #bbb;"><?php echo $l['ngay']; ?></td>
                                        <td style="padding: 15px; text-align: center;"><span
                                                style="background: <?php echo ($l['loai'] == 'Cộng tiền') ? '#27ae60' : '#c0392b'; ?>; padding: 3px 8px; border-radius: 4px; font-size: 10px; color: #fff;"><?php echo $l['loai']; ?></span>
                                        </td>
                                        <td
                                            style="padding: 15px; text-align: right; font-weight: bold; color: <?php echo (strpos($l['so_tien'], '+') !== false) ? '#2ecc71' : '#ff4d4d'; ?>;">
                                            <?php echo $l['so_tien']; ?></td>
                                        <td style="padding: 15px; text-align: right; color: #f39c12;">
                                            <?php echo $l['so_du_cuoi']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="history-pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?tab=money&p_m=<?php echo $i; ?>"
                                class="<?php echo ($current_page == $i) ? 'current' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    </div>
                <?php else: echo '<tr><td colspan="4" style="text-align:center; padding: 40px; color: #fff;">Chưa có lịch sử biến động.</td></tr>';
                            endif; ?>
            <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<script>
    function copyV(id) {
        var text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Đã copy!',
                text: text,
                timer: 1000,
                showConfirmButton: false,
                background: '#111',
                color: '#fff'
            });
        });
    }
</script>
<?php get_footer(); ?>