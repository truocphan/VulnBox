(function($) {
    "use strict";
    $(document).ready(function () {
        $('body').on('click', '.skip_pear_hb', function (e) {
            e.preventDefault();
            add_pear_hb_added_set_option('skip');
        });
    });

    function add_pear_hb_added_set_option(status) {
        $('.pear_hb_message').attr('style', 'display: none !important;');
        $.ajax({
            url: ajaxurl,
            type: "GET",
            data: 'add_pear_hb_status=' + status + '&action=stm_ajax_add_pear_hb&security=' + stm_ajax_add_pear_hb,
            success: function (data) {}
        });
    }

    $('[data-type="discard"]').on('click', function (e) {
        if($(this).attr('data-key') != 'starter_theme') {
            e.preventDefault();
        }

        let $this = $(this);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'stm_discard_admin_notice',
                pluginName: $(this).attr('data-key'),
            },
            success: function () {
                $this.closest('.stm-notice').fadeOut(10).remove();
            }
        });

    });

})(jQuery);