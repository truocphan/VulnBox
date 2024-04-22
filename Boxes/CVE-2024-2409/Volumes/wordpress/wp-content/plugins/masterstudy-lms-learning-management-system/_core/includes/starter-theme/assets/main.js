(function ($) {
    let adminAjaxUrl = null;
    $(document).ready(function () {
        /** Set ajax url value **/
        if (typeof stm_lms_starter_theme_data.stm_lms_admin_ajax_url !== 'undefined'
            && stm_lms_starter_theme_data.hasOwnProperty('stm_lms_admin_ajax_url')) {
            adminAjaxUrl = stm_lms_starter_theme_data.stm_lms_admin_ajax_url;
        }
        /** show step 2 **/
        $('.starter_install_theme_btn').on('click', function () {
            $('.starter_install_theme_btn .installing').css('display', 'inline-block');
            $('.starter_install_theme_btn span').html('Installing ');
            if (null !== adminAjaxUrl) {
                $.ajax({
                    url: adminAjaxUrl,
                    dataType: 'json',
                    context: this,
                    method: 'POST',
                    data: {
                        action: 'stm_install_starter_theme',
                        slug: 'ms-lms-starter-theme',
                        type: 'theme',
                        nonce: stm_lms_nonces['stm_install_starter_theme'],
                        is_last: false
                    },
                    complete: function (data) {
                        $('.starter_install_theme_btn .installing').css('display', 'none');
                        $('.starter_install_theme_btn .downloaded').css('display', 'inline-block');
                        $('.starter_install_theme_btn span').html('Successfully Installed');
                        setTimeout(
                            function () {
                                location.replace(location.origin + '/wp-admin/admin.php?page=starter_lms_demo_installer');
                            }, 2000
                        )
                    }
                });
            }
        });

    });
})(jQuery);