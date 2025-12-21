jQuery(document).ready(function($) {
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        
        var nick_id = $(this).data('id');
        var btn = $(this);

        if (!confirm('Xác nhận thanh toán và mua nick này?')) return;

        btn.text('ĐANG XỬ LÝ...').prop('disabled', true);

        $.ajax({
            url: shop_ajax.url, // Lấy từ wp_localize_script trong functions.php
            type: 'POST',
            data: {
                action: 'buy_nick_action',
                nick_id: nick_id
            },
            success: function(response) {
                if (response.success) {
                    btn.parent().html('<div style="background:#155724; color:#fff; padding:15px; border-radius:8px;">' +
                        '<h4>MUA THÀNH CÔNG!</h4>' +
                        '<p>Tài khoản: <strong>' + response.data.account + '</strong></p>' +
                        '<p>Mật khẩu: <strong>' + response.data.password + '</strong></p>' +
                        '</div>');
                    alert('Giao dịch hoàn tất!');
                } else {
                    alert(response.data);
                    btn.text('MUA NGAY').prop('disabled', false);
                }
            },
            error: function() {
                alert('Có lỗi xảy ra trong quá trình kết nối!');
                btn.text('MUA NGAY').prop('disabled', false);
            }
        });
    });
});