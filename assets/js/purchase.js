jQuery(document).ready(function($) {

    /**
     * ==========================================
     * 1. HỆ THỐNG ĐĂNG NHẬP / ĐĂNG KÝ / QUÊN MK
     * ==========================================
     */

    // --- Chuyển Tab Đăng nhập / Đăng ký ---
    $('.auth-tab').on('click', function() {
        var target = $(this).data('target');
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        
        $('.auth-form-box').hide();
        $('#' + target).stop().fadeIn();
    });

    // --- Hiện Form Quên mật khẩu ---
    $(document).on('click', '#show-forgot-form', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.auth-form-box').hide();
        $('.auth-tab').removeClass('active');
        $('#forgot-form').stop().fadeIn();
    });

    // --- Quay lại Đăng nhập từ Quên mật khẩu ---
    $(document).on('click', '.back-to-login', function(e) {
        e.preventDefault();
        $('#forgot-form').hide();
        $('#login-form').stop().fadeIn();
        $('.auth-tab[data-target="login-form"]').addClass('active');
    });

    // --- Ẩn / Hiện mật khẩu (Icon con mắt) ---
    $(document).on('click', '.toggle-password-icon', function() {
        var input = $(this).siblings('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    /**
     * --- Gửi OTP (Dùng chung cho Đăng ký & Quên MK) ---
     */
    function sendOTP(email, btn, actionType) {
        if(!email) { 
            Swal.fire({ icon: 'error', text: 'Vui lòng nhập email!', background: '#111', color: '#fff' });
            return; 
        }
        
        btn.prop('disabled', true).text('ĐANG GỬI...');
        
        $.post(shop_ajax.url, { action: actionType, email: email }, function(res) {
            if(res.success) {
                Swal.fire({ icon: 'success', text: res.data, background: '#111', color: '#fff' });
                var sec = 60;
                var timer = setInterval(function() {
                    sec--; btn.text('GỬI LẠI ('+sec+'s)');
                    if(sec <= 0) { clearInterval(timer); btn.prop('disabled', false).text('GỬI MÃ'); }
                }, 1000);
            } else { 
                Swal.fire({ icon: 'error', text: res.data, background: '#111', color: '#fff' });
                btn.prop('disabled', false).text('GỬI MÃ'); 
            }
        });
    }

    // Nút gửi OTP Đăng ký
    $('#btn-send-otp').on('click', function() {
        sendOTP($('#reg_email').val(), $(this), 'send_otp_action');
    });

    // Nút gửi OTP Quên mật khẩu
    $('#btn-send-otp-forgot').on('click', function() {
        sendOTP($('#forgot_email').val(), $(this), 'forgot_pass_otp_action');
    });

    /**
     * --- Submit các Form Auth ---
     */
    $('#login-form, #reg-form, #forgot-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var data = form.serialize();
        var action = 'custom_auth_action';

        if (form.attr('id') === 'forgot-form') {
            action = 'reset_password_action';
        } else {
            var auth_type = (form.attr('id') == 'login-form' ? 'login' : 'register');
            data += '&auth_type=' + auth_type;
        }
        
        data += '&action=' + action;
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> ĐANG XỬ LÝ...');

        $.post(shop_ajax.url, data, function(res) {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'THÀNH CÔNG', text: res.data, background: '#111', color: '#fff' }).then(() => {
                    if (action === 'reset_password_action') location.reload();
                    else window.location.href = shop_ajax.home_url + '/tai-khoan/';
                });
            } else {
                Swal.fire({ icon: 'error', title: 'THẤT BẠI', text: res.data, background: '#111', color: '#fff' });
                btn.prop('disabled', false).html('THỰC HIỆN LẠI');
            }
        });
    });


    /**
     * ==========================================
     * 2. HỆ THỐNG CŨ (MUA NICK / COPY / NẠP TIỀN)
     * ==========================================
     */

    // --- Mua Nick ---
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        var post_id = $(this).data('id');
        var price = $(this).data('price');

        Swal.fire({
            title: 'XÁC NHẬN MUA NICK',
            text: "Bạn có chắc chắn muốn mua nick này với giá " + price.toLocaleString() + "đ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ĐỒNG Ý MUA',
            cancelButtonText: 'HỦY',
            background: '#111',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(shop_ajax.url, { action: 'buy_nick_action', post_id: post_id }, function(res) {
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'THÀNH CÔNG', text: res.data, background: '#111', color: '#fff' })
                        .then(() => { window.location.href = shop_ajax.home_url + '/lich-su-mua-hang/'; });
                    } else {
                        Swal.fire({ icon: 'error', title: 'LỖI', text: res.data, background: '#111', color: '#fff' });
                    }
                });
            }
        });
    });

    // --- Copy Tài khoản / Mật khẩu ---
    window.copyV = function(id) {
        var text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'ĐÃ COPY',
                text: text,
                timer: 1000,
                showConfirmButton: false,
                background: '#111',
                color: '#fff'
            });
        });
    }

    // --- Đổi mật khẩu trong Dashboard ---
    $('#change-pass-form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=change_password_action';
        $.post(shop_ajax.url, data, function(res) {
            if (res.success) {
                Swal.fire({ icon: 'success', text: res.data, background: '#111', color: '#fff' });
                $('#change-pass-form')[0].reset();
            } else {
                Swal.fire({ icon: 'error', text: res.data, background: '#111', color: '#fff' });
            }
        });
    });

});


jQuery(document).ready(function($) {
    // --- 1. MỞ/ĐÓNG MENU MOBILE ---
    $('#open-menu').on('click', function() {
        $('#mobile-menu').addClass('active');
        $('body').css('overflow', 'hidden'); 
    });

    $('#close-menu').on('click', function() {
        $('#mobile-menu').removeClass('active');
        $('body').css('overflow', 'auto');
    });

    // Đóng menu khi bấm ra ngoài vùng menu
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#mobile-menu, #open-menu').length) {
            $('#mobile-menu').removeClass('active');
            $('body').css('overflow', 'auto');
        }
    });

    // --- (GIỮ NGUYÊN CÁC LOGIC AUTH, MUA HÀNG, NẠP TIỀN PHÍA DƯỚI) ---
});