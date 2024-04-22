"use strict";

(function ($) {
  "use strict";

  $(document).ready(function () {
    $('body').on('click', '.notice-dismiss', function (e) {
      if ($(this).closest('.stm-notice-cb-info')) {
        e.preventDefault();
        $.ajax({
          url: ms_lms_notice_data.ajax_url,
          type: "GET",
          data: 'add_pear_hb_status=skip&action=stm_close_cb_notice&security=' + ms_lms_notice_data.nonce,
          success: function success(data) {
            $('.stm-notice-cb-info').attr('style', 'display: none !important;');
          }
        });
      }
    });
    $('body').on('click', '.ms_settings_open', function (e) {
      if ($('[data-section="section_routes"]')) {
        $('[data-section="section_routes"]').click();
      }
    });
  });
})(jQuery);