<?php
/* Template Name: Lịch sử Giao dịch */
get_header();

if (!is_user_logged_in()) {
    wp_redirect(home_url('/dang-nhap'));
    exit;
}

$user_id = get_current_user_id();
$user_info = wp_get_current_user();
$current_balance = get_field('user_balance', 'user_' . $user_id);
?>

<style>
    /* CSS đồng bộ sidebar và tab */
    .dashboard-layout {
        display: flex;
        gap: 20px;
        margin-top: 30px;
    }

    /* Sidebar trái chuẩn theo image_02023d.png */
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
        color: #fff;
        font-weight: bold;
    }

    /* Nội dung Tab */
    .history-tabs-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .tab-trigger {
        background: #111;
        color: #888;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
        text-transform: uppercase;
        border: 1px solid #222;
    }

    .tab-trigger.active {
        background: #f39c12;
        color: #000;
        border-color: #f39c12;
    }

    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
        animation: fadeIn 0.4s;
    }

    .account-box {
        background: #000;
        border: 1px solid #222;
        padding: 8px 12px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 5px;
    }

    .btn-copy {
        background: none;
        border: none;
        color: #f39c12;
        cursor: pointer;
        font-size: 14px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>

<div class="container" style="padding-bottom: 100px;">
    <div class="dashboard-layout">

        <aside class="dashboard-sidebar">
            <div class="user-profile-card">
                <div class="user-avatar-circle">
                    <?php echo strtoupper(substr($user_info->user_login, 0, 2)); ?>
                </div>
                <h3 style="color: #fff; margin-bottom: 15px; font-size: 18px;"><?php echo $user_info->user_login; ?>
                </h3>
                <div class="user-balance-box">
                    <i class="fa-solid fa-wallet"></i> <?php echo number_format($current_balance ?: 0); ?>đ
                </div>
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
            <div class="history-tabs-nav">
                <button class="tab-trigger active" onclick="switchTab(event, 'tab-nicks')">Lịch sử mua Nick</button>
                <button class="tab-trigger" onclick="switchTab(event, 'tab-money')">Biến động số dư</button>
            </div>

            <div id="tab-nicks" class="tab-panel active">
                <div style="background: #111; border: 1px solid #1a1a1a; border-radius: 12px; padding: 25px;">
                    <?php
                    $nicks = new WP_Query(array('post_type' => 'nick-pubg', 'post_status' => 'any', 'meta_query' => array(array('key' => 'buyer_id', 'value' => $user_id))));
                    if ($nicks->have_posts()) : ?>
                        <table style="width: 100%; color: #fff; font-size: 13px; border-collapse: collapse;">
                            <thead>
                                <tr style="color: #555; border-bottom: 2px solid #1a1a1a;">
                                    <th style="padding: 15px; text-align: left;">Mã số</th>
                                    <th style="padding: 15px; text-align: left;">Thông tin tài khoản</th>
                                    <th style="padding: 15px; text-align: right;">Ngày mua</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($nicks->have_posts()) : $nicks->the_post(); ?>
                                    <tr style="border-bottom: 1px solid #1a1a1a;">
                                        <td style="padding: 15px; color: #f39c12; font-weight: bold;">#<?php the_ID(); ?></td>
                                        <td style="padding: 15px; width: 350px;">
                                            <div style="margin-bottom: 10px;">
                                                <small style="color: #444;">Tài khoản:</small>
                                                <div class="account-box"><span
                                                        id="u-<?php the_ID(); ?>"><?php the_field('tai_khoan'); ?></span><button
                                                        class="btn-copy" onclick="copyValue('u-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button></div>
                                            </div>
                                            <div>
                                                <small style="color: #444;">Mật khẩu:</small>
                                                <div class="account-box"><span
                                                        id="p-<?php the_ID(); ?>"><?php the_field('mat_khau'); ?></span><button
                                                        class="btn-copy" onclick="copyValue('p-<?php the_ID(); ?>')"><i
                                                            class="fa-regular fa-copy"></i></button></div>
                                            </div>
                                        </td>
                                        <td style="padding: 15px; text-align: right; color: #444;">
                                            <?php echo get_the_modified_date('d/m/Y H:i'); ?></td>
                                    </tr>
                                <?php endwhile;
                                wp_reset_postdata(); ?>
                            </tbody>
                        </table>
                    <?php else: echo '<p style="text-align:center; padding: 40px; color: #333;">Bạn chưa mua nick nào.</p>';
                    endif; ?>
                </div>
            </div>

            <div id="tab-money" class="tab-panel">
                <div style="background: #111; border: 1px solid #1a1a1a; border-radius: 12px; padding: 25px;">
                    <table style="width: 100%; color: #fff; font-size: 13px; border-collapse: collapse;">
                        <thead>
                            <tr style="color: #555; border-bottom: 2px solid #1a1a1a;">
                                <th style="padding: 15px; text-align: left;">Thời gian</th>
                                <th style="padding: 15px; text-align: center;">Hành động</th>
                                <th style="padding: 15px; text-align: right;">Số tiền</th>
                                <th style="padding: 15px; text-align: right;">Số dư cuối</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $logs = get_field('lich_su_giao_dich', 'user_' . $user_id);
                            if ($logs): foreach (array_reverse($logs) as $l): ?>
                                    <tr style="border-bottom: 1px solid #1a1a1a;">
                                        <td style="padding: 15px; color: #444;"><?php echo $l['ngay']; ?></td>
                                        <td style="padding: 15px; text-align: center;"><span
                                                style="background: <?php echo ($l['loai'] == 'Cộng tiền') ? '#27ae60' : '#c0392b'; ?>; padding: 3px 8px; border-radius: 4px; font-size: 10px; color: #fff;"><?php echo $l['loai']; ?></span>
                                        </td>
                                        <td
                                            style="padding: 15px; text-align: right; font-weight: bold; color: <?php echo (strpos($l['so_tien'], '+') !== false) ? '#2ecc71' : '#e74c3c'; ?>;">
                                            <?php echo $l['so_tien']; ?></td>
                                        <td style="padding: 15px; text-align: right; color: #f1c40f;">
                                            <?php echo $l['so_du_cuoi']; ?></td>
                                    </tr>
                            <?php endforeach;
                            else: echo '<tr><td colspan="4" style="text-align:center; padding: 40px; color: #333;">Chưa có lịch sử biến động số dư.</td></tr>';
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    function switchTab(evt, tabName) {
        var i, panels, triggers;
        panels = document.getElementsByClassName("tab-panel");
        for (i = 0; i < panels.length; i++) {
            panels[i].style.display = "none";
            panels[i].classList.remove("active");
        }
        triggers = document.getElementsByClassName("tab-trigger");
        for (i = 0; i < triggers.length; i++) {
            triggers[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    function copyValue(id) {
        var text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert("Đã copy: " + text);
        });
    }
</script>

<?php get_footer(); ?>