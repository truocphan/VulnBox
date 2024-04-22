"use strict";

(function ($) {
  $(document).ready(function () {
    $('.app_announcement .close').on('click', function (e) {
      e.preventDefault();
      $(this).closest('.app_announcement').fadeOut();
      $.ajax({
        url: stm_lms_ajaxurl,
        method: 'post',
        type: 'json',
        data: {
          action: 'stm_lms_hide_announcement',
          nonce: stm_lms_nonces['stm_lms_hide_announcement'],
          hide: true
        }
      });
    });
  });
})(jQuery);