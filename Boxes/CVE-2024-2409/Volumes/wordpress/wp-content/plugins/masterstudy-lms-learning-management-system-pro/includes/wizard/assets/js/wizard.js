(function ($) {

    $(document).ready(function () {
        $('.stm_lms_pro_wizard__close').on('click', function () {
            $('.stm_lms_pro_wizard__overlay, .stm_lms_pro_wizard').remove();
        });

        $('.stm_lms_install_button').on('click', function (e) {
            e.preventDefault();

            var src = $(this).attr('href');

            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'plugin': src,
                    'action': 'stm_lms_pro_install_base',
                    'nonce': stm_lms_pro_nonces['stm_lms_pro_install_base']
                },
                beforeSend: function () {
                    $(this).text('Installing');
                },
                complete: function (data) {
                    $(this).removeClass('loading');

                    data = data.responseJSON;

                    if (typeof data !== 'undefined') {
                        window.location.href = data.url;
                    } else {
                        window.location.reload();
                    }
                },
                error: function () {
                    window.location.reload();
                }

            });
        });
    });
})(jQuery);