"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-course-player-discussions__mobile-close').click(function () {
      var container = $('.masterstudy-course-player-header__discussions');
      if (container.hasClass('masterstudy-course-player-header__discussions_open')) {
        container.css('transition', '0.3s');
      } else {
        container.css('transition', '1.25s');
      }
      if (window.matchMedia('(max-width: 1024px)').matches) {
        $('.masterstudy-course-player-discussions').find('.masterstudy-course-player-quiz__navigation-tabs').toggleClass('masterstudy-course-player-quiz__navigation-tabs_show');
      }
      var currentUrl = window.location.href;
      var url = new URL(currentUrl);
      if (url.searchParams.has('discussions_open')) {
        url.searchParams["delete"]('discussions_open');
      }
      history.pushState({}, '', url.toString());
      if (localStorage.getItem('discussions_open') === 'yes') {
        localStorage.removeItem('discussions_open');
      }
      container.toggleClass('masterstudy-course-player-header__discussions_open');
      $('body').toggleClass('masterstudy-course-player-body-hidden');
      $('.masterstudy-course-player-discussions').toggleClass('masterstudy-course-player-discussions_open');
      if (!$('.masterstudy-course-player-curriculum').hasClass('masterstudy-course-player-curriculum_open')) {
        $('.masterstudy-course-player-content').toggleClass('masterstudy-course-player-content_open-sidebar');
      }
    });
  });
})(jQuery);