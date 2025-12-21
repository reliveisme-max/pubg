jQuery(document).ready(function($) {
    $('#btn-buy-now').on('click', function(e) {
        e.preventDefault();
        var nick_id = $(this).data('id');
        var btn = $(this);

        if (!confirm('Xác nhận sở hữu Nick này?')) return;

        btn.text('ĐANG XỬ LÝ...').prop('disabled', true);

        $.ajax({
            url: shop_ajax.url,
            type: 'POST',
            data: {
                action: 'buy_nick_action',
                nick_id: nick_id
            },
            success: function(response) {
                if (response.success) {
                    btn.hide();
                    $('#res-user').text(response.data.account);
                    $('#res-pass').text(response.data.password);
                    $('#account-result').fadeIn();
                } else {
                    alert(response.data);
                    btn.text('MUA NGAY').prop('disabled', false);
                }
            }
        });
    });
});