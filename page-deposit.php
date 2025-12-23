<?php
/* Template Name: Nạp tiền */
if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth/'));
    exit;
}
get_header();

$user = wp_get_current_user();
$user_id = $user->ID;
$current_balance = get_field('user_balance', 'user_' . $user_id);
?>

<style>
    /* CSS đồng bộ giao diện */
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

    .deposit-main {
        background: #111;
        border: 1px solid #1a1a1a;
        border-radius: 15px;
        padding: 30px;
        flex: 1;
    }

    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #000;
        padding: 25px;
        border-radius: 12px;
        border: 1px dashed #333;
        margin-bottom: 25px;
    }

    .input-amount-box {
        margin-bottom: 25px;
    }

    .input-amount-box label {
        display: block;
        color: #f39c12;
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .input-amount-box input {
        width: 100%;
        background: #0a0a0a;
        border: 1px solid #222;
        padding: 15px;
        color: #fff;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        outline: none;
        transition: 0.3s;
    }

    .input-amount-box input:focus {
        border-color: #f39c12;
        box-shadow: 0 0 10px rgba(243, 156, 18, 0.1);
    }

    .bank-info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .info-row {
        background: #0a0a0a;
        border: 1px solid #222;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-row label {
        color: #555;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .info-row span {
        color: #fff;
        font-weight: bold;
        font-size: 15px;
    }

    .highlight-text {
        color: #f39c12 !important;
    }

    .notice-box {
        margin-top: 25px;
        padding: 15px;
        background: rgba(243, 156, 18, 0.05);
        border: 1px solid #33220a;
        border-radius: 8px;
        color: #888;
        font-size: 13px;
        line-height: 1.6;
    }
</style>

<div class="container" style="padding-bottom: 100px;">
    <div class="dashboard-layout">

        <aside class="dashboard-sidebar">
            <div class="user-profile-card">
                <div class="user-avatar-circle"><?php echo strtoupper(substr($user->user_login, 0, 2)); ?></div>
                <h3 style="color: #fff; margin-bottom: 15px; font-size: 18px;"><?php echo $user->display_name; ?></h3>
                <div class="user-balance-box"><i class="fa-solid fa-wallet"></i>
                    <?php echo number_format($current_balance ?: 0); ?>đ</div>
            </div>
            <ul class="dashboard-menu">
                <li><a href="<?php echo home_url('/tai-khoan/'); ?>"><i class="fa-solid fa-gauge"></i> Tổng quan</a>
                </li>
                <li><a href="<?php echo home_url('/lich-su-mua-hang/'); ?>"><i
                            class="fa-solid fa-clock-rotate-left"></i> Lịch sử mua hàng</a></li>
                <li><a href="<?php echo home_url('/nap-tien/'); ?>" class="active"><i
                            class="fa-solid fa-credit-card"></i> Nạp tiền</a></li>
                <li><a href="<?php echo wp_logout_url(home_url()); ?>" style="color: #e74c3c;"><i
                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main class="deposit-main">
            <h2 style="color: #f39c12; font-size: 20px; margin-bottom: 25px; text-transform: uppercase;">Nạp tiền qua
                Ngân hàng</h2>

            <div class="input-amount-box">
                <label>Số tiền bạn muốn nạp (VNĐ)</label>
                <input type="number" id="deposit-amount" placeholder="Ví dụ: 100000" value="100000">
            </div>

            <div class="qr-container">
                <img id="vietqr-image" src="" style="max-width: 250px; border-radius: 8px; border: 4px solid #fff;">
                <p style="color: #888; font-size: 12px; margin-top: 15px;">Mã QR tự động cập nhật theo số tiền bạn nhập
                </p>
            </div>

            <div class="bank-info-grid">
                <div class="info-row"><label>Ngân hàng</label><span>MB BANK (QUÂN ĐỘI)</span></div>
                <div class="info-row"><label>Số tài khoản</label><span class="highlight-text"
                        style="font-size: 18px;">89999998686</span></div>
                <div class="info-row"><label>Chủ tài khoản</label><span>LE ANH TU</span></div>
                <div class="info-row" style="background: #1a150e; border: 1px solid #33220a;">
                    <label style="color: #f39c12;">Nội dung</label>
                    <span class="highlight-text" style="text-transform: uppercase; font-size: 18px;">NAP
                        <?php echo $user->user_login; ?></span>
                </div>
            </div>

            <div class="notice-box">
                <strong>HƯỚNG DẪN NẠP:</strong><br>
                - Nhập số tiền bạn muốn nạp vào ô trên để nhận mã QR chính xác.<br>
                - Ghi đúng nội dung <strong style="color: #f39c12;">NAP <?php echo $user->user_login; ?></strong> nếu
                bạn chuyển khoản thủ công.<br>
                - Hệ thống sẽ cộng tiền tự động sau khi nhận được giao dịch.
            </div>
        </main>
    </div>
</div>

<script>
    /* Script cập nhật QR Real-time */
    const amountInput = document.getElementById('deposit-amount');
    const qrImage = document.getElementById('vietqr-image');
    const username = "<?php echo $user->user_login; ?>";

    function updateQR() {
        const amount = amountInput.value || 0;
        // Sử dụng VietQR API để tạo mã động
        const qrUrl =
            `https://img.vietqr.io/image/MB-89999998686-compact.png?amount=${amount}&addTag=1&accountName=LE%20ANH%20TU&text=NAP%20${username}`;
        qrImage.src = qrUrl;
    }

    // Cập nhật ngay khi load trang và khi người dùng gõ phím
    updateQR();
    amountInput.addEventListener('input', updateQR);
</script>

<?php get_footer(); ?>