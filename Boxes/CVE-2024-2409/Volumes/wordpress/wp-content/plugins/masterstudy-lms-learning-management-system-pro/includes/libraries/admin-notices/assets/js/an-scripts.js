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
})(jQuery);