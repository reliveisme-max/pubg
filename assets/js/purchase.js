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
     * 4. XỬ LÝ MUA NICK (POPUP XÁC NHẬN & TRỪ TIỀN)
     */
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        var nick_id = $(this).data('id');

        Swal.fire({
            title: 'Xác nhận mua?',
            text: "Số dư của bạn sẽ bị trừ vào tài khoản ngay lập tức!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffae00',
            cancelButtonColor: '#333',
            confirmButtonText: 'MUA NGAY',
            cancelButtonText: 'HỦY',
            background: '#111', color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(shop_ajax.url, { action: 'buy_nick_action', nick_id: nick_id }, function(res) {
                    if (res.success) {
                        // Hiển thị thông tin vào Modal
                        $('#res-user').text(res.data.account);
                        $('#res-pass').text(res.data.password);
                        $('#purchase-modal').css('display', 'flex').hide().fadeIn();
                    } else {
                        Swal.fire({ icon: 'error', title: 'LỖI THANH TOÁN', text: res.data, background: '#111', color: '#fff' });
                    }
                });
            }
        });
    });

    // Đóng Popup kết quả mua hàng
    $('.btn-close-modal').on('click', function() {
        $('#purchase-modal').fadeOut();
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