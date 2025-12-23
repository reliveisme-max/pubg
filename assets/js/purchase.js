jQuery(document).ready(function($) {
    /**
     * 1. XỬ LÝ GIAO DIỆN AUTH (ĐĂNG NHẬP / ĐĂNG KÝ)
     */
    $('.auth-tab').on('click', function() {
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        $('.auth-form-box').hide();
        $('#' + $(this).data('target')).fadeIn();
    });

    /**
     * 2. XỬ LÝ GỬI MÃ OTP QUA EMAIL
     */
    $('#btn-send-otp').on('click', function() {
        var email = $('#reg_email').val();
        if(!email) { 
            Swal.fire({ icon: 'error', title: 'LỖI', text: 'Vui lòng nhập Email trước!', background: '#111', color: '#fff' });
            return; 
        }
        
        var btn = $(this);
        btn.prop('disabled', true).text('ĐANG GỬI...');
        
        $.post(shop_ajax.url, { action: 'send_otp_action', email: email }, function(res) {
            if(res.success) {
                Swal.fire({ icon: 'success', title: 'THÀNH CÔNG', text: res.data, background: '#111', color: '#fff' });
                var sec = 60;
                var timer = setInterval(function() {
                    sec--; btn.text('GỬI LẠI ('+sec+'s)');
                    if(sec <= 0) { clearInterval(timer); btn.prop('disabled', false).text('GỬI MÃ'); }
                }, 1000);
            } else { 
                Swal.fire({ icon: 'error', title: 'THẤT BẠI', text: res.data, background: '#111', color: '#fff' });
                btn.prop('disabled', false).text('GỬI MÃ'); 
            }
        });
    });

    /**
     * 3. XỬ LÝ ĐĂNG NHẬP & ĐĂNG KÝ (AJAX)
     */
    $('#login-form, #reg-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button');
        var auth_type = (form.attr('id') == 'login-form' ? 'login' : 'register');
        var data = form.serialize() + '&action=custom_auth_action&auth_type=' + auth_type;

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> ĐANG XỬ LÝ...');

        $.post(shop_ajax.url, data, function(res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: auth_type === 'login' ? 'ĐĂNG NHẬP THÀNH CÔNG!' : 'ĐĂNG KÝ THÀNH CÔNG!',
                    text: res.data,
                    background: '#111', color: '#fff', confirmButtonText: 'TIẾP TỤC'
                }).then(() => {
                    // Chuyển hướng chính xác về trang Dashboard tự tạo
                    window.location.href = shop_ajax.home_url + '/tai-khoan/'; 
                });
            } else {
                Swal.fire({ icon: 'error', title: 'THẤT BẠI', text: res.data, background: '#111', color: '#fff' });
                btn.prop('disabled', false).html(auth_type === 'login' ? 'ĐĂNG NHẬP NGAY <i class="fa-solid fa-right-to-bracket"></i>' : 'TẠO TÀI KHOẢN <i class="fa-solid fa-user-plus"></i>');
            }
        });
    });

    /**
     * 4. XỬ LÝ MUA NICK (CẬP NHẬT REAL-TIME KHÔNG F5)
     */
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        var nick_id = $(this).data('id');

        Swal.fire({
            title: 'Xác nhận mua?',
            text: "Số dư của bạn sẽ bị trừ ngay lập tức!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffae00',
            confirmButtonText: 'MUA NGAY',
            cancelButtonText: 'HỦY',
            background: '#111', color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                var $btn = $(this);
                $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>...');

                $.post(shop_ajax.url, { action: 'buy_nick_action', nick_id: nick_id }, function(res) {
                    if (res.success) {
                        // 1. Cập nhật số dư trên Header và Dashboard
                        $('.user-balance-nav').html('<i class="fa-solid fa-wallet"></i> ' + res.data.new_balance);
                        $('.user-balance-box, .stat-card strong').first().text(res.data.new_balance);

                        // 2. Hiển thị thông tin Nick vào Modal
                        $('#res-user').text(res.data.account);
                        $('#res-pass').text(res.data.password);
                        $('#purchase-modal').fadeIn().css('display', 'flex');
                        
                        // 3. Ẩn nút mua để khách không bấm lại được nữa
                        $btn.remove(); 
                    } else {
                        Swal.fire({ icon: 'error', title: 'LỖI', text: res.data, background: '#111', color: '#fff' });
                        $btn.prop('disabled', false).text('XÁC NHẬN MUA NGAY');
                    }
                });
            }
        });
    });

    /**
     * 5. XỬ LÝ NÚT ĐÓNG MODAL (DÙNG DELEGATION ĐỂ FIX LỖI)
     */
    $(document).on('click', '.btn-close-modal', function() {
        $('#purchase-modal').fadeOut(function() {
            $(this).css('display', 'none');
        });
    });

    /**
     * 5. XỬ LÝ ĐỔI MẬT KHẨU TẠI DASHBOARD
     */
    $(document).on('submit', '#change-pass-form', function(e) {
        e.preventDefault();
        var btn = $(this).find('button');
        var new_pass = $(this).find('input[name="new_password"]').val();

        btn.prop('disabled', true).text('ĐANG CẬP NHẬT...');

        $.post(shop_ajax.url, { action: 'change_password_action', new_password: new_pass }, function(res) {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'THÀNH CÔNG', text: res.data, background: '#111', color: '#fff' });
            } else {
                Swal.fire({ icon: 'error', title: 'LỖI', text: res.data, background: '#111', color: '#fff' });
            }
            btn.prop('disabled', false).text('CẬP NHẬT NGAY');
        });
    });

    /**
     * 6. TÍNH NĂNG COPY NHANH (CHO LỊCH SỬ MUA HÀNG)
     */
    $(document).on('click', '.btn-copy-mini', function() {
        var targetId = $(this).data('copy');
        var textToCopy = $('#' + targetId).text();
        
        var temp = $("<input>");
        $("body").append(temp);
        temp.val(textToCopy).select();
        document.execCommand("copy");
        temp.remove();

        var icon = $(this).find('i');
        icon.removeClass('fa-copy').addClass('fa-check');
        setTimeout(function() {
            icon.removeClass('fa-check').addClass('fa-copy');
        }, 1500);
    });
});

/**
 * XỬ LÝ ẨN/HIỆN MẬT KHẨU
 */
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

/* --- JS RIÊNG CHO QUÊN MẬT KHẨU --- */

// 1. Click hiện form quên mật khẩu
$(document).on('click', '#show-forgot-form', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $('.auth-form-box').hide(); // Ẩn Login/Register
    $('.auth-tab').removeClass('active');
    $('#forgot-form').stop().fadeIn(); // Hiện Quên mật khẩu
});

// 2. Quay lại đăng nhập
$(document).on('click', '.back-to-login', function(e) {
    e.preventDefault();
    $('#forgot-form').hide();
    $('#login-form').fadeIn();
    $('.auth-tab[data-target="login-form"]').addClass('active');
});

// 3. Gửi mã OTP Quên mật khẩu
$('#btn-send-otp-forgot').on('click', function() {
    var email = $('#forgot_email').val();
    if(!email) { 
        Swal.fire({ icon: 'error', text: 'Nhập email của bạn!', background: '#111', color: '#fff' });
        return; 
    }
    var btn = $(this);
    btn.prop('disabled', true).text('ĐANG GỬI...');
    
    $.post(shop_ajax.url, { action: 'forgot_pass_otp_action', email: email }, function(res) {
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
});

// 4. Submit đặt lại mật khẩu mới
$('#forgot-form').on('submit', function(e) {
    e.preventDefault();
    var data = $(this).serialize() + '&action=reset_password_action';
    var btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true).text('ĐANG CẬP NHẬT...');

    $.post(shop_ajax.url, data, function(res) {
        if (res.success) {
            Swal.fire({ icon: 'success', title: 'THÀNH CÔNG', text: res.data, background: '#111', color: '#fff' }).then(() => {
                location.reload(); 
            });
        } else {
            Swal.fire({ icon: 'error', text: res.data, background: '#111', color: '#fff' });
            btn.prop('disabled', false).text('XÁC NHẬN ĐỔI MẬT KHẨU');
        }
    });
});