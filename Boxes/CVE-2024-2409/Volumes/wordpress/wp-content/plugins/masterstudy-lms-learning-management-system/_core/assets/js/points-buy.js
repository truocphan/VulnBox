"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_buy_for_points').on('click', function (e) {
      e.preventDefault();
      var $this = $(this);
      if ($this.hasClass('not-enough-points')) return false;
      var course_id = $(this).data('course');
      if (!confirm(stm_lms_points_buy['translate']['confirm'])) return false;
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          course_id: course_id,
          action: 'stm_lms_buy_for_points',
          nonce: stm_lms_nonces['stm_lms_buy_for_points']
        },
        beforeSend: function beforeSend() {
          $this.addClass('loading');
        },
        complete: function complete(data) {
          var data = data['responseJSON'];
          window.location.href = data.url;
          $this.removeClass('loading');
        }
      });
    });
    $('.points_dist').on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var win = window.open($(this).data('href'), '_blank');
      win.focus();
    });
  });
})(jQuery);