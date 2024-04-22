"use strict";

(function ($) {
  $(window).on('load', function () {
    stm_lms_print_message({
      event_type: 'order_created'
    });
  });
})(jQuery);