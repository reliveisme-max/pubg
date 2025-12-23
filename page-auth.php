<?php
/* Template Name: Auth Page */
if (is_user_logged_in()) {
    wp_redirect(home_url('/tai-khoan/'));
    exit;
}
get_header(); ?>

<div class="container auth-wrapper" style="margin-top: 30px; margin-bottom: 30px;">
    <div class="auth-card">
        <div class="auth-nav">
            <div class="auth-tab active" data-target="login-form">ĐĂNG NHẬP</div>
            <div class="auth-tab" data-target="reg-form">ĐĂNG KÝ</div>
        </div>

        <form id="login-form" class="auth-form-box">
            <div class="input-item">
                <label>TÀI KHOẢN</label>
                <input type="text" name="username" placeholder="Nhập tên tài khoản..." required>
            </div>
            <div class="input-item">
                <label>MẬT KHẨU</label>
                <div class="password-toggle-wrapper">
                    <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
                    <i class="fa-solid fa-eye toggle-password-icon"></i>
                </div>
            </div>
            <div class="forgot-pass-link-wrapper">
                <a href="javascript:void(0)" id="show-forgot-form">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="btn-auth">ĐĂNG NHẬP NGAY <i class="fa-solid fa-right-to-bracket"></i></button>
        </form>

        <form id="forgot-form" class="auth-form-box" style="display:none;">
            <div style="margin-bottom: 25px; text-align: center;">
                <h3 style="color: #f39c12; font-size: 18px; font-weight: 800; margin-bottom: 10px;">KHÔI PHỤC MẬT KHẨU
                </h3>
                <p style="color: #888; font-size: 13px;">Mã OTP sẽ được gửi về Email của bạn</p>
            </div>
            <div class="input-item">
                <label>EMAIL TÀI KHOẢN</label>
                <div style="display: flex; gap: 10px;">
                    <input type="email" id="forgot_email" name="forgot_email" placeholder="Nhập email..." required>
                    <button type="button" id="btn-send-otp-forgot" class="btn-auth"
                        style="width: auto; padding: 0 15px; font-size: 12px; white-space: nowrap;">GỬI MÃ</button>
                </div>
            </div>
            <div class="input-item">
                <label>MÃ XÁC MINH (OTP)</label>
                <input type="text" name="otp_code_forgot" placeholder="Nhập mã 6 số..." required>
            </div>
            <div class="input-item">
                <label>MẬT KHẨU MỚI</label>
                <div class="password-toggle-wrapper">
                    <input type="password" name="new_password_forgot" placeholder="Tối thiểu 6 ký tự..." required>
                    <i class="fa-solid fa-eye toggle-password-icon"></i>
                </div>
            </div>
            <button type="submit" class="btn-auth" style="margin-top: 20px;">ĐẶT LẠI MẬT KHẨU <i
                    class="fa-solid fa-key"></i></button>
            <div style="text-align: center; margin-top: 20px;">
                <a href="javascript:void(0)" class="back-to-login"
                    style="color: #888; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại Đăng nhập
                </a>
            </div>
        </form>

        <form id="reg-form" class="auth-form-box" style="display:none;">
            <div class="input-item"><label>TÊN TÀI KHOẢN</label><input type="text" name="reg_username"
                    placeholder="Ví dụ: shoptule123" required></div>
            <div class="input-item"><label>EMAIL XÁC THỰC</label><input type="email" id="reg_email" name="reg_email"
                    placeholder="Nhập email để nhận OTP..." required></div>
            <div class="input-item"><label>MẬT KHẨU</label>
                <div class="password-toggle-wrapper"><input type="password" name="reg_password"
                        placeholder="Tạo mật khẩu bảo mật..." required><i
                        class="fa-solid fa-eye toggle-password-icon"></i></div>
            </div>
            <div class="input-item"><label>MÃ XÁC MINH (OTP)</label>
                <div style="display: flex; gap: 10px;"><input type="text" name="otp_code" placeholder="Mã 6 số..."
                        required><button type="button" id="btn-send-otp" class="btn-auth"
                        style="width: auto; padding: 0 15px; font-size: 12px; white-space: nowrap;">GỬI MÃ</button>
                </div>
            </div>
            <button type="submit" class="btn-auth">TẠO TÀI KHOẢN <i class="fa-solid fa-user-plus"></i></button>
        </form>
    </div>
</div>
<?php get_footer(); ?>