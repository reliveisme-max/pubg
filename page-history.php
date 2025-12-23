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

<style>
    /* ... (Các CSS cũ giữ nguyên) ... */
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

    .dashboard-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: #bbb;
        text-decoration: none;
        border-radius: 8px;
        transition: 0.3s;
        font-size: 14px;
    }

    .dashboard-menu a.active {
        background: #f39c12;
        color: #000;
        font-weight: bold;
    }

    /* CẬP NHẬT MÀU TRẮNG CHO ACC INLINE */
    .acc-inline-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #000;
        border: 1px solid #222;
        padding: 10px 15px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
    }

    .acc-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .acc-item span {
        color: #f39c12;
        font-weight: bold;
        font-size: 14px;
    }

    .acc-divider {
        color: #444;
        margin: 0 5px;
    }

    /* Làm vách ngăn rõ hơn */
    .btn-copy-sm {
        background: none;
        border: none;
        color: #888;
        cursor: pointer;
        font-size: 12px;
        transition: 0.2s;
    }

    .btn-copy-sm:hover {
        color: #fff;
    }

    .history-pagination {
        margin-top: 25px;
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .history-pagination a,
    .history-pagination span {
        padding: 8px 14px;
        background: #111;
        border: 1px solid #222;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .history-pagination span.current {
        background: #f39c12;
        color: #000;
        border-color: #f39c12;
    }
</style>

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
                <li><a href="<?php echo home_url('/lich-su-giao-dich'); ?>" class="active"><i
                            class="fa-solid fa-clock-rotate-left"></i> Lịch sử giao dịch</a></li>
                <li><a href="<?php echo home_url('/nap-tien'); ?>"><i class="fa-solid fa-credit-card"></i> Nạp tiền</a>
                </li>
                <li><a href="<?php echo wp_logout_url(home_url()); ?>" style="color: #e74c3c;"><i
                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main style="flex: 1;">
            <div class="history-tabs-nav" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <a href="?tab=nicks" class="tab-trigger <?php echo $active_tab == 'nicks' ? 'active' : ''; ?>"
                    style="text-decoration:none; padding: 10px 20px; border-radius:6px; background: <?php echo $active_tab == 'nicks' ? '#f39c12' : '#111'; ?>; color: <?php echo $active_tab == 'nicks' ? '#000' : '#fff'; ?>; font-weight: bold; font-size: 13px; border: 1px solid #222;">LỊCH
                    SỬ MUA NICK</a>
                <a href="?tab=money" class="tab-trigger <?php echo $active_tab == 'money' ? 'active' : ''; ?>"
                    style="text-decoration:none; padding: 10px 20px; border-radius:6px; background: <?php echo $active_tab == 'money' ? '#f39c12' : '#111'; ?>; color: <?php echo $active_tab == 'money' ? '#000' : '#fff'; ?>; font-weight: bold; font-size: 13px; border: 1px solid #222;">BIẾN
                    ĐỘNG SỐ DƯ</a>
            </div>

            <?php if ($active_tab == 'nicks'): ?>
                <div style="background: #111; border: 1px solid #1a1a1a; border-radius: 12px; padding: 25px;">
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $nicks = new WP_Query(array('post_type' => 'nick-pubg', 'post_status' => 'any', 'posts_per_page' => 10, 'paged' => $paged, 'meta_query' => array(array('key' => 'buyer_id', 'value' => $user_id))));
                    if ($nicks->have_posts()) : ?>
                        <table style="width: 100%; color: #fff; font-size: 13px; border-collapse: collapse;">
                            <thead>
                                <tr style="color: #fff; border-bottom: 2px solid #333;">
                                    <th style="padding: 15px; text-align: left;">Mã số</th>
                                    <th style="padding: 15px; text-align: left;">Thông tin tài khoản/mật khẩu</th>
                                    <th style="padding: 15px; text-align: right;">Ngày mua</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($nicks->have_posts()) : $nicks->the_post(); ?>
                                    <tr style="border-bottom: 1px solid #1a1a1a;">
                                        <td style="padding: 15px; color: #f39c12; font-weight: bold;">#<?php the_ID(); ?></td>
                                        <td style="padding: 15px;">
                                            <div class="acc-inline-wrapper">
                                                <div class="acc-item">
                                                    <small style="color: #eee;">TK:</small> <span
                                                        id="u-<?php the_ID(); ?>"><?php the_field('tai_khoan'); ?></span> <button
                                                        class="btn-copy-sm" onclick="copyValue('u-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                                <div class="acc-divider">|</div>
                                                <div class="acc-item">
                                                    <small style="color: #eee;">MK:</small> <span
                                                        id="p-<?php the_ID(); ?>"><?php the_field('mat_khau'); ?></span> <button
                                                        class="btn-copy-sm" onclick="copyValue('p-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 15px; text-align: right; color: #ccc;">
                                            <?php echo get_the_modified_date('d/m/Y H:i'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div class="history-pagination">
                            <?php echo paginate_links(array('total' => $nicks->max_num_pages, 'current' => $paged, 'format' => '/page/%#%/?tab=nicks', 'prev_text' => 'Trước', 'next_text' => 'Sau')); ?>
                        </div>
                    <?php else: echo '<p style="text-align:center; padding: 40px; color: #fff;">Bạn chưa mua nick nào.</p>';
                    endif;
                    wp_reset_postdata(); ?>
                </div>

            <?php else: ?>
                <div style="background: #111; border: 1px solid #1a1a1a; border-radius: 12px; padding: 25px;">
                    <table style="width: 100%; color: #fff; font-size: 13px; border-collapse: collapse;">
                        <thead>
                            <tr style="color: #fff; border-bottom: 2px solid #333;">
                                <th style="padding: 15px; text-align: left;">Thời gian</th>
                                <th style="padding: 15px; text-align: center;">Hành động</th>
                                <th style="padding: 15px; text-align: right;">Số tiền</th>
                                <th style="padding: 15px; text-align: right;">Số dư cuối</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $all_logs = get_field('lich_su_giao_dich', 'user_' . $user_id) ?: [];
                            $all_logs = array_reverse($all_logs);
                            $per_page = 10;
                            $total_logs = count($all_logs);
                            $total_pages = ceil($total_logs / $per_page);
                            $current_page = isset($_GET['p_money']) ? max(1, intval($_GET['p_money'])) : 1;
                            $offset = ($current_page - 1) * $per_page;
                            $logs = array_slice($all_logs, $offset, $per_page);
                            if ($logs): foreach ($logs as $l): ?>
                                    <tr style="border-bottom: 1px solid #1a1a1a;">
                                        <td style="padding: 15px; color: #ccc;"><?php echo $l['ngay']; ?></td>
                                        <td style="padding: 15px; text-align: center;"><span
                                                style="background: <?php echo ($l['loai'] == 'Cộng tiền') ? '#27ae60' : '#c0392b'; ?>; padding: 3px 8px; border-radius: 4px; font-size: 10px; color: #fff;"><?php echo $l['loai']; ?></span>
                                        </td>
                                        <td
                                            style="padding: 15px; text-align: right; font-weight: bold; color: <?php echo (strpos($l['so_tien'], '+') !== false) ? '#2ecc71' : '#e74c3c'; ?>;">
                                            <?php echo $l['so_tien']; ?></td>
                                        <td style="padding: 15px; text-align: right; color: #f1c40f;">
                                            <?php echo $l['so_du_cuoi']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="history-pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?tab=money&p_money=<?php echo $i; ?>"
                                class="<?php echo ($current_page == $i) ? 'current' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    </div>
                <?php else: echo '<tr><td colspan="4" style="text-align:center; padding: 40px; color: #fff;">Chưa có lịch sử biến động số dư.</td></tr>';
                            endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script>
    function copyValue(id) {
        var text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Đã Copy!',
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