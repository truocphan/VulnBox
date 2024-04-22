"use strict";

(function ($) {
  $(document).ready(function () {
    var $delete = $('.stm_lms_subscription_warning__course .delete');
    $delete.on('click', function () {
      var $this = $(this);
      if ($this.hasClass('loading')) return true;
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          course_id: $this.data('course'),
          action: 'stm_lms_delete_course_subscription',
          nonce: stm_lms_nonces['stm_lms_delete_course_subscription']
        },
        beforeSend: function beforeSend() {
          $this.addClass('loading');
        },
        complete: function complete(data) {
          data = data['responseJSON'];
          $this.removeClass('loading');
          $this.closest('.stm_lms_subscription_warning__course').remove();
          process_checkout();
        }
      });
    });
  });
  function process_checkout() {
    var courses_num = $('.stm_lms_subscription_warning__course').length;
    var quota = $('.stm_lms_subscription_warning').data('quota');
    if (courses_num <= quota) {
      $('.stm_lms_subscription_warning').addClass('hidden');
      $('.pmpro_form').addClass('active');
    }
  }
})(jQuery);