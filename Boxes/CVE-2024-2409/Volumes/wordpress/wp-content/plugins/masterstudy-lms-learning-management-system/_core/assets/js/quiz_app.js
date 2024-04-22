"use strict";

(function ($) {
  $(window).on('load', function () {
    if (!$('.stm-lms-course__lesson-content').hasClass('passed')) {
      stm_lms_start_quiz();
      stm_lms_retake_quiz();
    }
  });
})(jQuery);
function stm_lms_start_quiz() {
  var $ = jQuery;
  $('.stm_lms_start_quiz').click();
  setTimeout(stm_lms_item_match_resize, 500);
}
function stm_lms_submit_quiz() {
  var $ = jQuery;
  $('.stm-lms-single_quiz').submit();
}
function stm_lms_update_quiz() {
  window.location.reload();
}
function stm_lms_retake_quiz() {
  var $ = jQuery;
  $('.btn-retake').click();
  setTimeout(stm_lms_item_match_resize, 500);
}