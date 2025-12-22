jQuery(document).ready(function($) {
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        var nick_id = $(this).data('id');
        var btn = $(this);

        if (!confirm('Xác nhận sở hữu Nick này?')) return;

        btn.html('ĐANG XỬ LÝ...').prop('disabled', true);

        $.ajax({
            url: shop_ajax.url,
            type: 'POST',
            data: {
                action: 'buy_nick_action',
                nick_id: nick_id
            },
            success: function(response) {
                if (response.success) {
                    // Đổ dữ liệu vào Popup
                    $('#res-user').text(response.data.account);
                    $('#res-pass').text(response.data.password);
                    
                    // Hiển thị Popup với hiệu ứng
                    $('#purchase-modal').css('display', 'flex').hide().fadeIn();
                    
                    btn.html('ĐÃ MUA THÀNH CÔNG').css('background', '#444');
                } else {
                    alert(response.data);
                    btn.html('XÁC NHẬN MUA NGAY <i class="fa-solid fa-cart-shopping"></i>').prop('disabled', false);
                }
            }
        });
    });

    // Đóng Popup khi bấm nút Đóng
    $('.btn-close-modal').on('click', function() {
        $('#purchase-modal').fadeOut();
    });
});

// Tính năng copy cho bảng lịch sử
jQuery(document).on('click', '.btn-copy-mini', function() {
    var targetId = jQuery(this).data('copy');
    var textToCopy = jQuery('#' + targetId).text();
    
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
    temp.val(textToCopy).select();
    document.execCommand("copy");
    temp.remove();

    var icon = jQuery(this).find('i');
    icon.removeClass('fa-copy').addClass('fa-check');
    setTimeout(function() {
        icon.removeClass('fa-check').addClass('fa-copy');
    }, 1000);
});






jQuery(document).ready(function($) {
    // Chuyển Tab Đăng nhập/Đăng ký
    $('.auth-tab').on('click', function() {
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        $('.auth-form-box').hide();
        $('#' + $(this).data('target')).fadeIn();
    });

    // Xử lý Gửi Form Auth (Login/Register)
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
                background: '#111',
                color: '#fff',
                confirmButtonText: 'TIẾP TỤC'
            }).then((result) => {
                // CHUYỂN HƯỚNG TỚI TRANG TÀI KHOẢN
                // Thay '/tai-khoan/' bằng slug trang của bạn
                window.location.href = shop_ajax.home_url + '/tai-khoan/'; 
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'THẤT BẠI',
                text: res.data,
                background: '#111',
                color: '#fff',
                confirmButtonText: 'THỬ LẠI'
            });
            btn.prop('disabled', false).text(auth_type === 'login' ? 'ĐĂNG NHẬP NGAY' : 'TẠO TÀI KHOẢN');
        }
    });
});

    // Logic Mua Nick (Giữ nguyên cũ nhưng bổ sung logic trừ tiền)
    $('#btn-buy-now').on('click', function() {
        var nick_id = $(this).data('id');
        if(!confirm('Xác nhận thanh toán và sở hữu Nick này?')) return;

        $.post(shop_ajax.url, {action: 'buy_nick_action', nick_id: nick_id}, function(res) {
            if (res.success) {
                // Hiện Popup thành công
                $('#res-user').text(res.data.account);
                $('#res-pass').text(res.data.password);
                $('#purchase-modal').fadeIn();
            } else {
                alert(res.data);
            }
        });
    });
});



jQuery(document).ready(function($) {
    // 1. Gửi OTP
    $('#btn-send-otp').on('click', function() {
        var email = $('#reg_email').val();
        if(!email) { alert('Vui lòng nhập Email!'); return; }
        
        var btn = $(this);
        btn.prop('disabled', true).text('ĐANG GỬI...');
        
        $.post(shop_ajax.url, { action: 'send_otp_action', email: email }, function(res) {
            alert(res.data);
            if(res.success) {
                var sec = 60;
                var timer = setInterval(function() {
                    sec--; btn.text('GỬI LẠI ('+sec+'s)');
                    if(sec <= 0) { clearInterval(timer); btn.prop('disabled', false).text('GỬI MÃ'); }
                }, 1000);
            } else { btn.prop('disabled', false).text('GỬI MÃ'); }
        });
    });

    // 2. Submit Auth (Login/Register)
    $('#login-form, #reg-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize() + '&action=custom_auth_action&auth_type=' + (form.attr('id') == 'login-form' ? 'login' : 'register');
        
        $.post(shop_ajax.url, data, function(res) {
            alert(res.data);
            if(res.success) location.reload();
        });
    });

    // 3. Mua hàng
    $('#btn-confirm-purchase').on('click', function() {
        var nick_id = $(this).data('id');
        $.post(shop_ajax.url, { action: 'buy_nick_action', nick_id: nick_id }, function(res) {
            if(res.success) {
                $('#res-user').text(res.data.account);
                $('#res-pass').text(res.data.password);
                $('#purchase-modal').fadeIn();
            } else { alert(res.data); }
        });
    });
});

// Cập nhật trong file purchase.js
$.post(shop_ajax.url, data, function(res) {
    if (res.success) {
        Swal.fire({
            icon: 'success',
            title: 'THÀNH CÔNG!',
            text: res.data,
            background: '#111', color: '#fff'
        }).then(() => {
            // CHUYỂN HƯỚNG VỀ TRANG DASHBOARD TỰ TẠO
            window.location.href = shop_ajax.home_url + '/tai-khoan/';
        });
    }
});


// Xử lý Đổi mật khẩu tại Dashboard
$(document).on('submit', '#change-pass-form', function(e) {
    e.preventDefault();
    var btn = $(this).find('button');
    var new_pass = $(this).find('input[name="new_password"]').val();

    btn.prop('disabled', true).text('ĐANG CẬP NHẬT...');

    $.post(shop_ajax.url, {
        action: 'change_password_action',
        new_password: new_pass
    }, function(res) {
        if (res.success) {
            Swal.fire({
                icon: 'success',
                title: 'THÀNH CÔNG',
                text: res.data,
                background: '#111', color: '#fff',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({ icon: 'error', title: 'LỖI', text: res.data, background: '#111', color: '#fff' });
        }
        btn.prop('disabled', false).text('CẬP NHẬT NGAY');
    });
});