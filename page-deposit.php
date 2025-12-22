<?php
/* Template Name: Trang Nạp Tiền */
get_header(); ?>

<div class="container" style="margin-top: 40px; padding-bottom: 80px;">
    <div class="deposit-wrapper" style="max-width: 800px; margin: 0 auto;">

        <div class="section-title-wrapper" style="text-align: center; margin-bottom: 40px;">
            <h2
                style="color: var(--color-accent); text-transform: uppercase; font-family: 'Rajdhani'; font-size: 36px; margin-bottom: 10px;">
                <i class="fas fa-qrcode"></i> NẠP TIỀN QUA QR / ATM
            </h2>
            <p style="color: #888;">Chuyển khoản chính xác nội dung để được cộng tiền nhanh nhất.</p>
        </div>

        <div class="deposit-qr-grid"
            style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid #222;">

            <div class="qr-display-box" style="text-align: center;">
                <div
                    style="background: #fff; padding: 15px; border-radius: 12px; display: inline-block; margin-bottom: 15px;">
                    <?php
                    $user_id = get_current_user_id();
                    $bank_id = "MB"; // Thay bằng ngân hàng của bạn
                    $account_no = "0123456789"; // Thay bằng STK của bạn
                    $template = "compact2";
                    $description = "NAP " . $user_id;
                    $qr_url = "https://img.vietqr.io/image/{$bank_id}-{$account_no}-{$template}.png?addInfo={$description}";
                    ?>
                    <img src="<?php echo $qr_url; ?>" alt="QR Code Nạp Tiền"
                        style="width: 250px; height: 250px; display: block;">
                </div>
                <p style="color: #888; font-size: 13px;">Quét mã bằng ứng dụng Ngân hàng hoặc Momo để thanh toán nhanh.
                </p>
            </div>

            <div class="bank-details-box">
                <h3 style="color: #fff; margin-bottom: 20px; font-family: 'Rajdhani';">THÔNG TIN TÀI KHOẢN</h3>

                <div class="info-row" style="margin-bottom: 15px;">
                    <span style="color: #888; display: block; font-size: 13px;">NGÂN HÀNG</span>
                    <strong style="color: #fff; font-size: 18px;">MB BANK (QUÂN ĐỘI)</strong>
                </div>

                <div class="info-row" style="margin-bottom: 15px;">
                    <span style="color: #888; display: block; font-size: 13px;">CHỦ TÀI KHOẢN</span>
                    <strong style="color: #fff; font-size: 18px;">NGUYEN VAN A</strong>
                </div>

                <div class="info-row" style="margin-bottom: 15px;">
                    <span style="color: #888; display: block; font-size: 13px;">SỐ TÀI KHOẢN</span>
                    <strong style="color: var(--color-accent); font-size: 24px; font-weight: 900;">0123456789</strong>
                </div>

                <div class="info-row"
                    style="background: #000; padding: 15px; border-radius: 8px; border: 1px dashed var(--color-accent); margin-top: 20px;">
                    <span style="color: var(--color-accent); display: block; font-size: 13px; font-weight: 700;">NỘI
                        DUNG CHUYỂN KHOẢN</span>
                    <strong style="color: #fff; font-size: 22px; font-weight: 900; letter-spacing: 1px;">NAP
                        <?php echo $user_id; ?></strong>
                </div>
            </div>
        </div>

        <div
            style="margin-top: 30px; background: rgba(255, 174, 0, 0.05); border: 1px solid rgba(255, 174, 0, 0.2); padding: 20px; border-radius: 12px;">
            <h4 style="color: var(--color-accent); margin: 0 0 10px 0;"><i class="fas fa-info-circle"></i> QUY TRÌNH NẠP
                TIỀN:</h4>
            <ul style="color: #aaa; padding-left: 20px; margin: 0; line-height: 1.6;">
                <li>Bước 1: Quét mã QR hoặc chuyển khoản thủ công theo thông tin trên.</li>
                <li>Bước 2: Hệ thống sẽ tự động cộng tiền sau 1-3 phút nếu bạn ghi đúng nội dung.</li>
                <li>Bước 3: Nếu sau 10 phút chưa nhận được tiền, hãy liên hệ ngay với Fanpage để được hỗ trợ thủ công.
                </li>
            </ul>
        </div>
    </div>
</div>

<?php get_footer(); ?>