"use strict";

(function ($) {
  $(document).ready(function () {
    $.cookie('affiliate_id', stm_lms_affiliate_user_id['id'], {
      path: '/'
    });
  });
})(jQuery);