"use strict";

(function ($) {
  $('.masterstudy-membership [data-id]').on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: stm_lms_ajaxurl,
      dataType: 'json',
      method: 'get',
      context: this,
      data: {
        action: 'stm_lms_use_membership',
        nonce: stm_lms_nonces['stm_lms_use_membership'],
        course_id: $(this).attr('data-id')
      },
      beforeSend: function beforeSend() {
        $(this).addClass('masterstudy-button_loading');
      },
      complete: function complete(data) {
        var result = data['responseJSON'];
        $(this).removeClass('masterstudy-button_loading');
        if (typeof result['url'] !== 'undefined') {
          window.location.href = result['url'];
        } else {
          location.reload();
        }
      }
    });
  });
})(jQuery);