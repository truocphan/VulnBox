"use strict";

(function ($) {
  /**
   * @var stm_lms_expired_course
   */

  var cookie_name = "stm_lms_expired_course_".concat(stm_lms_expired_course.id);
  var cookie = $.cookie(cookie_name);
  $(document).ready(function () {
    if (cookie !== 'closed') {
      $('html').addClass('expired_popup');
      $('.stm_lms_expired_popup').addClass('active');
    }
    $('.stm_lms_expired_popup__overlay, .stm_lms_expired_popup__close').on('click', function () {
      $('html').removeClass('expired_popup');
      $('.stm_lms_expired_popup').removeClass('active');
      var date = new Date();
      $.cookie(cookie_name, 'closed', {
        path: '/',
        expires: date.getTime() + 24 * 60 * 60 * 1000
      });
    });
  });
})(jQuery);