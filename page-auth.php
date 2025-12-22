<?php
/* Template Name: Auth Page */

// Nếu khách đã đăng nhập thì tự động đẩy sang trang Tài khoản
if (is_user_logged_in()) {
    wp_redirect(home_url('/tai-khoan/'));
    exit;
}

get_header(); ?>
<div class="container auth-wrapper">
    <div class="auth-card">
        <div class="auth-nav">
            <div class="auth-tab active" data-target="login-form">ĐĂNG NHẬP</div>
            <div class="auth-tab" data-target="reg-form">ĐĂNG KÝ</div>
        </div>

        <form id="login-form" class="auth-form-box">
            <div class="input-item"><label>TÀI KHOẢN</label><input type="text" name="username" required></div>
            <div class="input-item"><label>MẬT KHẨU</label><input type="password" name="password" required></div>
            <button type="submit" class="btn-auth">ĐĂNG NHẬP NGAY <i class="fa-solid fa-right-to-bracket"></i></button>
        </form>

        <form id="reg-form" class="auth-form-box" style="display:none;">
            <div class="input-item"><label>TÊN TÀI KHOẢN</label><input type="text" name="reg_username" required></div>
            <div class="input-item"><label>EMAIL</label><input type="email" id="reg_email" name="reg_email" required>
            </div>
            <div class="input-item"><label>MẬT KHẨU</label><input type="password" name="reg_password" required></div>
            <div class="input-item">
                <label>MÃ XÁC MINH (OTP)</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="otp_code" placeholder="Nhập mã..." required>
                    <button type="button" id="btn-send-otp" class="btn-auth"
                        style="width: auto; padding: 0 15px; margin: 0; font-size: 12px; white-space: nowrap;">GỬI
                        MÃ</button>
                </div>
            </div>
            <button type="submit" class="btn-auth">TẠO TÀI KHOẢN <i class="fa-solid fa-user-plus"></i></button>
        </form>
    </div>
</div>
<?php get_footer(); ?>