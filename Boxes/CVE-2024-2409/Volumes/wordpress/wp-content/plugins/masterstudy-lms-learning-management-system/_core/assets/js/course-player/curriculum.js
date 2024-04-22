"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-course-player-curriculum__mobile-close').click(function () {
      var currentUrl = window.location.href;
      var url = new URL(currentUrl);
      if (url.searchParams.has('curriculum_open')) {
        url.searchParams["delete"]('curriculum_open');
      }
      history.pushState({}, '', url.toString());
      if (localStorage.getItem('curriculum_open') === 'yes') {
        localStorage.removeItem('curriculum_open');
      }
      $('.masterstudy-course-player-curriculum').toggleClass('masterstudy-course-player-curriculum_open');
      $('body').toggleClass('masterstudy-course-player-body-hidden');
      if (!$('.masterstudy-course-player-discussions').hasClass('masterstudy-course-player-discussions_open')) {
        $('.masterstudy-course-player-content').toggleClass('masterstudy-course-player-content_open-sidebar');
      }
      $('[data-id="masterstudy-curriculum-switcher"]').toggleClass('masterstudy-switch-button_active');
    });
  });
})(jQuery);