"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_recent_courses__term').on('click', function () {
      var $wrapper = $(this).closest('.stm_lms_recent_courses');
      var $courses = $wrapper.find('.stm_lms_courses__grid');
      if ($courses.hasClass('loading')) return false;
      $(this).closest('.stm_lms_recent_courses__terms').find('.stm_lms_recent_courses__term').removeClass('active');
      $(this).addClass('active');
      var term = $(this).attr('data-term');
      if (typeof term !== 'undefined') {
        var args = $wrapper.attr('data-args').replace('}', ', "term":' + term + '}');
      } else {
        var args = $wrapper.attr('data-args');
      }
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          action: 'stm_lms_load_content',
          args: args,
          offset: 0,
          template: $wrapper.attr('data-template'),
          nonce: stm_lms_nonces['load_content']
        },
        beforeSend: function beforeSend() {
          $courses.addClass('loading');
        },
        complete: function complete(data) {
          var data = data['responseJSON'];
          $courses.html(data.content);
          jQuery('.masterstudy-countdown').each(function () {
            if (jQuery(this).find('.countDays').length === 0) {
              jQuery(this).countdown({
                timestamp: jQuery(this).data('timer')
              });
            }
          });
          $wrapper.find('.stm_lms_recent_courses__all a').attr('href', data.link);
          setTimeout(function () {
            $courses.removeClass('loading');
          }, 300);
        }
      });
    });
  });
})(jQuery);