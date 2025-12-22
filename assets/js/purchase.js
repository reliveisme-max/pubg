jQuery(document).ready(function($) {
    // 1. Xử lý nút Mua ngay
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        var nick_id = $(this).data('id');

        Swal.fire({
            title: 'Xác nhận mua?',
            text: "Số dư của bạn sẽ bị trừ ngay lập tức!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffae00',
            cancelButtonColor: '#333',
            confirmButtonText: 'MUA NGAY',
            cancelButtonText: 'HỦY',
            background: '#111',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi yêu cầu mua tới server
                $.post(shop_ajax.url, { action: 'buy_nick_action', nick_id: nick_id }, function(res) {
                    if (res.success) {
                        $('#res-user').text(res.data.account);
                        $('#res-pass').text(res.data.password);
                        $('#purchase-modal').fadeIn(); // Hiện popup kết quả
                    } else {
                        Swal.fire({ icon: 'error', title: 'LỖI', text: res.data, background: '#111', color: '#fff' });
                    }
                });
            }
        });
    });
    // 1. Chuyển Tab Đăng nhập/Đăng ký
    $('.auth-tab').on('click', function() {
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        $('.auth-form-box').hide();
        $('#' + $(this).data('target')).fadeIn();
    });

    // 2. Xử lý Đăng nhập & Đăng ký (Dùng SweetAlert2)
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
                    title: 'THÀNH CÔNG',
                    text: res.data,
                    background: '#111', color: '#fff'
                }).then(() => {
                    // Chuyển hướng chính xác tới trang Dashboard
                    window.location.href = shop_ajax.home_url + '/tai-khoan/'; 
                });
            } else {
                Swal.fire({ icon: 'error', title: 'THẤT BẠI', text: res.data, background: '#111', color: '#fff' });
                btn.prop('disabled', false).text(auth_type == 'login' ? 'ĐĂNG NHẬP NGAY' : 'TẠO TÀI KHOẢN');
            }
        });
    });

    // 3. Xử lý Gửi OTP
    $('#btn-send-otp').on('click', function() {
        var email = $('#reg_email').val();
        if(!email) { Swal.fire('Lỗi', 'Vui lòng nhập Email!', 'error'); return; }
        var btn = $(this);
        btn.prop('disabled', true).text('ĐANG GỬI...');
        $.post(shop_ajax.url, { action: 'send_otp_action', email: email }, function(res) {
            Swal.fire('Thông báo', res.data, res.success ? 'success' : 'error');
            if(res.success) {
                var sec = 60;
                var timer = setInterval(function() {
                    sec--; btn.text('GỬI LẠI ('+sec+'s)');
                    if(sec <= 0) { clearInterval(timer); btn.prop('disabled', false).text('GỬI MÃ'); }
                }, 1000);
            } else { btn.prop('disabled', false).text('GỬI MÃ'); }
        });
    });

    // 4. Logic Mua Nick
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        var nick_id = $(this).data('id');
        Swal.fire({
            title: 'Xác nhận mua?',
            text: "Bạn sẽ bị trừ tiền vào số dư tài khoản!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'MUA NGAY'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(shop_ajax.url, {action: 'buy_nick_action', nick_id: nick_id}, function(res) {
                    if (res.success) {
                        $('#res-user').text(res.data.account);
                        $('#res-pass').text(res.data.password);
                        $('#purchase-modal').fadeIn();
                    } else {
                        Swal.fire('Lỗi', res.data, 'error');
                    }
                });
            }
        });
    });
});