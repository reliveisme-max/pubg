<?php
/* Template Name: Trang Nạp Tiền */
get_header(); ?>

<div class="container" style="margin-top: 50px; padding-bottom: 80px;">
    <div class="deposit-header" style="text-align: center; margin-bottom: 40px;">
        <h2
            style="color: var(--color-accent); text-transform: uppercase; font-size: 32px; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 15px;">
            <i class="fa-duotone fa-solid fa-qrcode"></i> NẠP TIỀN QUA QR / ATM
        </h2>
        <p style="color: #888; font-size: 15px; margin-top: 10px;">Chuyển khoản chính xác nội dung để được cộng tiền
            nhanh nhất.</p>
    </div>

    <div class="deposit-main-card">
        <div class="qr-section">
            <div class="qr-frame">
                <?php
                $user_id = get_current_user_id();
                $bank_id = "MB";
                $account_no = "89999998686"; // Đã cập nhật STK mới của bạn
                $template = "compact2";
                $description = "NAP " . $user_id;
                $qr_url = "https://img.vietqr.io/image/{$bank_id}-{$account_no}-{$template}.png?addInfo={$description}";
                ?>
                <img src="<?php echo $qr_url; ?>" alt="QR Nạp Tiền">
            </div>
            <p class="qr-subtext">Quét mã bằng ứng dụng Ngân hàng hoặc Momo để thanh toán nhanh.</p>
        </div>

        <div class="bank-info-section">
            <h3 class="info-title">THÔNG TIN TÀI KHOẢN</h3>

            <div class="info-group">
                <span>NGÂN HÀNG</span>
                <strong>MB BANK (NGÂN HÀNG QUÂN ĐỘI)</strong>
            </div>

            <div class="info-group">
                <span>CHỦ TÀI KHOẢN</span>
                <strong>LE ANH TU</strong>
            </div>

            <div class="info-group">
                <span>SỐ TÀI KHOẢN</span>
                <strong class="acc-number">89999998686</strong>
            </div>

            <div class="content-box">
                <span class="content-label">NỘI DUNG CHUYỂN KHOẢN</span>
                <strong class="content-text">NAP <?php echo $user_id; ?></strong>
            </div>
        </div>
    </div>

    <div class="deposit-rules">
        <h4><i class="fa-duotone fa-solid fa-list-check"></i> QUY TRÌNH NẠP TIỀN:</h4>
        <ul>
            <li>
                <i class="fa-solid fa-qrcode"></i>
                <span><strong>Bước 1:</strong> Quét mã QR hoặc chuyển khoản thủ công theo thông tin trên thẻ ngân
                    hàng.</span>
            </li>
            <li>
                <i class="fa-solid fa-bolt-auto"></i>
                <span><strong>Bước 2:</strong> Hệ thống sẽ tự động quét biến động và cộng tiền sau 1-3 phút nếu bạn ghi
                    đúng nội dung.</span>
            </li>
            <li>
                <i class="fa-solid fa-headset"></i>
                <span><strong>Bước 3:</strong> Nếu sau 10 phút chưa nhận được tiền, hãy liên hệ ngay với Fanpage để được
                    hỗ trợ nhanh nhất.</span>
            </li>
        </ul>
    </div>
</div>

<?php get_footer(); ?>