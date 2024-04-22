"use strict";

(function ($) {
  $(document).ready(function () {
    var cookie_to = $.cookie('stm_lms_course_buy');
    $.ajax({
      url: stm_lms_ajaxurl,
      dataType: 'json',
      context: this,
      data: {
        course_id: cookie_to,
        action: 'stm_lms_get_course_cookie_redirect',
        nonce: stm_lms_nonces['stm_lms_get_course_cookie_redirect']
      },
      complete: function complete(data) {
        data = data['responseJSON'];
        $.removeCookie('stm_lms_course_buy', {
          path: '/'
        });
        if (typeof data.url !== 'undefined') {
          window.location.href = data.url + '#membership';
        }
      }
    });
  });
})(jQuery);