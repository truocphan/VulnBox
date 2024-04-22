"use strict";

(function ($) {
  $(document).ready(function () {
    var question_trigger = $('.stm-lms-course__sidebar_toggle');
    var url = window.location.href;
    var hashIndex = url.indexOf('#');
    var hash = hashIndex !== -1 ? url.slice(hashIndex) : '';
    $('body').on('click', '.stm-lms-course__sidebar_toggle', function () {
      $('body').toggleClass('lesson-sidebar-opened');
    });
    if (hash === '#qa_trigger' && question_trigger.length) {
      question_trigger.click();
    }
    $('.stm-lesson_sidebar__close, .stm-lms-course__overlay').on('click', function () {
      $('body').removeClass('lesson-sidebar-opened');
    });
  });
})(jQuery);