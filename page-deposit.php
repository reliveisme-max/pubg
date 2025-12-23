<?php
/* Template Name: Nạp tiền */
get_header();
if (!is_user_logged_in()) { wp_redirect(home_url('/auth')); exit; }
$user_id = get_current_user_id();
$user_info = wp_get_current_user();
$current_balance = get_field('user_balance', 'user_' . $user_id);
?>

<style>
/* SIDEBAR: Tăng khoảng cách giữa các nút */
.dashboard-layout {
    display: flex;
    gap: 25px;
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
    margin-bottom: 20px;
}

.user-avatar-circle {
    width: 65px;
    height: 65px;
    background: #f39c12;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 26px;
    color: #000;
    font-weight: bold;
    border: 2px solid #222;
}

.dashboard-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.dashboard-menu li {
    margin-bottom: 12px;
}

/* Tăng khoảng cách giữa các dòng menu */
.dashboard-menu a {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 14px 20px;
    color: #bbb;
    text-decoration: none;
    border-radius: 10px;
    transition: 0.3s;
    font-size: 14px;
    font-weight: 600;
    background: #111;
    border: 1px solid #1a1a1a;
}

.dashboard-menu a:hover {
    background: #1a1a1a;
    color: #fff;
    border-color: #333;
}

.dashboard-menu a.active {
    background: #f39c12;
    color: #000;
    font-weight: bold;
    border-color: #f39c12;
}

/* NỘI DUNG NẠP TIỀN */
.deposit-container {
    background: #111;
    border: 1px solid #1a1a1a;
    border-radius: 12px;
    padding: 45px;
}

.vnpay-header {
    text-align: center;
    margin-bottom: 40px;
}

.vnpay-logo-img {
    height: 55px;
    margin-bottom: 18px;
}

.deposit-form-group {
    max-width: 450px;
    margin: 0 auto;
}

.deposit-input-wrapper input {
    width: 100%;
    padding: 18px 22px;
    background: #000;
    border: 2px solid #222;
    color: #f39c12;
    border-radius: 12px;
    font-size: 22px;
    font-weight: 800;
    margin-bottom: 25px;
}

/* NÚT XÁC NHẬN: Màu cam chuẩn, padding lớn */
.btn-vnpay-pay {
    width: 100%;
    padding: 18px;
    background: #f39c12;
    color: #000;
    border: none;
    border-radius: 12px;
    font-weight: 800;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    text-transform: uppercase;
    box-shadow: 0 4px 20px rgba(243, 156, 18, 0.15);
}

.btn-vnpay-pay:hover {
    background: #e67e22;
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(243, 156, 18, 0.25);
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
                <li><a href="<?php echo home_url('/lich-su-giao-dich'); ?>"><i
                            class="fa-solid fa-clock-rotate-left"></i> Lịch sử giao dịch</a></li>
                <li><a href="<?php echo home_url('/nap-tien'); ?>" class="active"><i
                            class="fa-solid fa-credit-card"></i> Nạp tiền</a></li>
                <li><a href="<?php echo wp_logout_url(home_url()); ?>" style="color: #e74c3c;"><i
                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main style="flex: 1;">
            <div class="deposit-container">
                <div class="vnpay-header">
                    <img src="https://stcd02206177151.cloud.edgevnpay.vn/assets/images/logo-icon/logo-primary.svg"
                        class="vnpay-logo-img" alt="VNPay">
                    <h2 style="color: #fff; font-size: 24px; font-weight: 800; text-transform: uppercase;">Nạp tiền qua
                        VNPay</h2>
                </div>
                <div class="deposit-form-group">
                    <label
                        style="color: #fff; font-weight: bold; display: block; margin-bottom: 12px; font-size: 13px;">SỐ
                        TIỀN MUỐN NẠP (đ)</label>
                    <div class="deposit-input-wrapper"><input type="number" id="vnpay_amount" value="100000"
                            min="10000"></div>
                    <button id="btn-vnpay-submit" class="btn-vnpay-pay">Xác nhận thanh toán <i
                            class="fa-solid fa-arrow-right-long" style="margin-left: 10px;"></i></button>
                </div>
            </div>
        </main>
    </div>
</div>
<?php get_footer(); ?>