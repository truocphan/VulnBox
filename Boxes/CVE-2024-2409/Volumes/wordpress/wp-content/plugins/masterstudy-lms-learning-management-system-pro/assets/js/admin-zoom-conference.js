(function ($) {
    $(document).ready(function () {
        $('#zoom_addon_install .button').on('click', function (e) {
            e.preventDefault();
            $('#zoom_addon_install .message').show();
            $('#zoom_addon_install .button').hide();
            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'install_zoom_addon',
                    nonce: stm_lms_nonces['install_zoom_addon']
                },
                success(r){
                    if(typeof r.activated !== 'undefined'){
                        var url = window.location;
                        url = url.origin + url.pathname + '?page=stm_zoom_settings';
                        window.location.href = url;
                    }
                }
            })
        })
    });
})(jQuery);