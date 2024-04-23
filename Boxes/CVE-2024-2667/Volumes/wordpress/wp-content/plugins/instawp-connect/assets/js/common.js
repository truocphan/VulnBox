(function ($, window, document, plugin_object) {

    $(document).on('click', '.instawp-tools', function (e) {
        e.preventDefault();
        let el = $(this).find('a');
        $.ajax({
            type: 'POST',
            url: plugin_object.ajax_url,
            data: {
                'action': 'instawp_process_ajax',
                'type': el.attr('target'),
                'security': instawp_common.security
            },
            success: function (response) {
                console.log(response)
                if(el.attr('target') === 'cache') {
                    window.location.href = window.location.href + '?instawp-cache-cleared';
                } else {
                    window.open(response.data.login_url, '_blank');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    });

    $(document).on('click', '.instawp-shortcuts', function (e) {
        e.preventDefault();
        let el = $(this).find('a');
        localStorage.setItem('instawp_admin_current', el.attr('target'));
        window.location = el.attr('href');
    });
})(jQuery, window, document, instawp_common);

