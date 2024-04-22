"use strict";

(function ($) {
  $(document).ready(function () {
    var container = $('.masterstudy-course-player-lesson'),
      submit_button = $('[data-id="masterstudy-course-player-lesson-submit"]');
    if (container.length > 0 && container.find('.masterstudy-course-player-lesson-video').length === 0) {
      submit_button.attr('disabled', 1);
      submit_button.addClass('masterstudy-button_disabled');
      checkVisibility();
      var parent = $(window).width() < 1025 ? window : '.masterstudy-course-player-content__wrapper';
      $(parent).on('scroll touchmove', function () {
        checkVisibility();
      });
    }
    function checkVisibility() {
      var submitButton = $(".masterstudy-course-player-lesson__submit-trigger");
      if (isElementVisible(submitButton)) {
        submit_button.removeAttr('disabled');
        submit_button.removeClass('masterstudy-button_disabled');
      }
    }
    function isElementVisible(el) {
      var _el$;
      var rect = el === null || el === void 0 || (_el$ = el[0]) === null || _el$ === void 0 ? void 0 : _el$.getBoundingClientRect();
      var windowHeight = window.innerHeight || document.documentElement.clientHeight;
      return rect.top >= 0 && rect.bottom - 40 <= windowHeight;
    }
  });
})(jQuery);