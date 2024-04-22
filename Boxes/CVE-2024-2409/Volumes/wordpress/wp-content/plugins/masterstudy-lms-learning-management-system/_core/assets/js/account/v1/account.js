"use strict";

(function ($) {
  $(document).ready(function () {
    $('.become_instructor_info .info-close').on('click', function () {
      var userId = $(this).attr('data-user-id');
      var _this = $(this);
      $.ajax({
        url: stm_lms_ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          'user_id': userId,
          'action': 'stm_lms_hide_become_instructor_notice',
          'nonce': stm_lms_nonces['stm_lms_hide_become_instructor_notice']
        },
        beforeSend: function beforeSend() {
          _this.closest('.become_instructor_info').slideUp();
        }
      });
    });
  });
})(jQuery);