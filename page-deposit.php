<?php
/* Template Name: Nạp tiền */
get_header();
if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth'));
    exit;
}

$user_id = get_current_user_id();
$user_info = wp_get_current_user();
$current_balance = get_field('user_balance', 'user_' . $user_id);

// Lấy thông tin cấu hình từ ACF Options
$is_vnpay_on = get_field('bat_vnpay', 'option');
$bank_id = get_field('bank_id', 'option') ?: 'VCB'; // Mã ngân hàng (VD: VCB, MB, TCB...)
$bank_stk = get_field('mb_stk', 'option') ?: '0123456789'; // Số tài khoản nạp
$bank_name = get_field('mb_name', 'option') ?: 'LE ANH TU'; // Tên chủ tài khoản
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
                <li><a href="<?php echo home_url('/lich-su-mua-hang'); ?>"><i class="fa-solid fa-clock-rotate-left"></i>
                        Lịch sử giao dịch</a></li>
                <li><a href="<?php echo home_url('/nap-tien'); ?>" class="active"><i
                            class="fa-solid fa-credit-card"></i> Nạp tiền</a></li>
                <li><a href="<?php echo wp_logout_url(home_url()); ?>" style="color: #e74c3c;"><i
                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main style="flex: 1;">
            <div class="deposit-container"
                style="background: #111; border: 1px solid #222; border-radius: 15px; padding: 35px;">

                <?php if ($is_vnpay_on) : ?>
                    <div style="text-align: center; margin-bottom: 40px;">
                        <img src="https://stcd02206177151.cloud.edgevnpay.vn/assets/images/logo-icon/logo-primary.svg"
                            style="height: 55px;" alt="VNPay">
                        <h2 style="color: #fff; font-size: 22px; font-weight: 800; margin-top: 15px;">NẠP TIỀN QUA VN PAY
                        </h2>
                    </div>
                    <div style="max-width: 450px; margin: 0 auto;">
                        <label
                            style="color: #fff; font-weight: bold; display: block; margin-bottom: 12px; font-size: 13px;">SỐ
                            TIỀN MUỐN NẠP (đ)</label>
                        <input type="number" id="v_amount" placeholder="Tối thiểu 10.000đ..." min="10000"
                            style="width: 100%; padding: 18px; background: #000; border: 2px solid #222; color: #f39c12; border-radius: 12px; font-size: 18px; font-weight: 700; margin-bottom: 30px;">
                        <button id="v_submit" class="btn-vnpay-custom">XÁC NHẬN THANH TOÁN <i
                                class="fa-solid fa-arrow-right-long"></i></button>
                    </div>

                <?php else : ?>
                    <div style="text-align: center;">
                        <h2 style="color: #f39c12; font-size: 22px; font-weight: 800; margin-bottom: 10px;">NẠP TIỀN QUA
                            NGÂN HÀNG</h2>
                        <p style="color: #888; margin-bottom: 30px;">Hệ thống sẽ cộng tiền sau khi nhận được chuyển khoản.
                        </p>

                        <div
                            style="background: #fff; padding: 15px; display: inline-block; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 0 20px rgba(243, 156, 18, 0.2);">
                            <?php
                            // Tự động dọn dẹp mã ngân hàng (xóa khoảng trắng) để tránh lỗi link ảnh
                            $clean_bank_id = str_replace(' ', '', trim($bank_id));
                            $qr_url = "https://img.vietqr.io/image/{$clean_bank_id}-{$bank_stk}-compact2.jpg?amount=0&addInfo=NAP%20TIEN%20{$user_info->user_login}&accountName=" . urlencode($bank_name);
                            ?>
                            <img src="<?php echo $qr_url; ?>" style="max-width: 280px; width: 100%; display: block;"
                                alt="Mã QR Thanh Toán"
                                onerror="this.src='https://via.placeholder.com/280x280?text=Loi+Ma+QR';">
                        </div>

                        <div
                            style="text-align: left; max-width: 420px; margin: 0 auto; background: #000; padding: 25px; border-radius: 12px; border: 1px solid #333;">
                            <div
                                style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px solid #1a1a1a; padding-bottom: 8px;">
                                <span style="color: #888;">Ngân hàng:</span> <strong
                                    style="color: #fff;"><?php echo $bank_id; ?></strong>
                            </div>
                            <div
                                style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px solid #1a1a1a; padding-bottom: 8px;">
                                <span style="color: #888;">Số tài khoản:</span> <strong
                                    style="color: #f39c12; font-size: 18px;"><?php echo $bank_stk; ?></strong>
                            </div>
                            <div
                                style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px solid #1a1a1a; padding-bottom: 8px;">
                                <span style="color: #888;">Chủ tài khoản:</span> <strong
                                    style="color: #fff;"><?php echo $bank_name; ?></strong>
                            </div>
                            <div
                                style="background: rgba(243, 156, 18, 0.1); padding: 15px; border-radius: 8px; border: 1px dashed #f39c12; margin-top: 15px;">
                                <span
                                    style="color: #f39c12; font-size: 11px; display: block; margin-bottom: 5px; font-weight: 800;">NỘI
                                    DUNG CHUYỂN KHOẢN:</span>
                                <strong style="color: #fff; font-size: 16px;">NAP TIEN
                                    <?php echo $user_info->user_login; ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#v_submit').on('click', function(e) {
            e.preventDefault();
            const amount = $('#v_amount').val();
            if (amount < 10000) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Nạp tối thiểu 10.000đ',
                    background: '#111',
                    color: '#fff'
                });
                return;
            }
            $(this).prop('disabled', true).html(
                '<i class="fa-solid fa-circle-notch fa-spin"></i> ĐANG XỬ LÝ...');
            $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'vnpay_create_payment',
                amount: amount
            }, function(res) {
                if (res.success) window.location.href = res.data;
                else {
                    Swal.fire({
                        icon: 'error',
                        text: res.data,
                        background: '#111',
                        color: '#fff'
                    });
                    $('#v_submit').prop('disabled', false).html('XÁC NHẬN THANH TOÁN');
                }
            });
        });
    });
</script>
<?php get_footer(); ?>