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
            <div class="deposit-container">
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
                    <input type="number" id="v_amount" placeholder="Nhập số tiền muốn nạp (Ví dụ: 100000)..."
                        min="10000"
                        style="width: 100%; padding: 18px; background: #000; border: 2px solid #222; color: #f39c12; border-radius: 12px; font-size: 18px; font-weight: 700; margin-bottom: 30px;">

                    <button id="v_submit" class="btn-vnpay-custom">
                        XÁC NHẬN THANH TOÁN <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </div>
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