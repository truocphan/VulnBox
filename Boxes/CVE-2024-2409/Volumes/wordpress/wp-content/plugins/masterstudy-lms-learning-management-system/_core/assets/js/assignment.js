"use strict";

(function ($) {
  $(document).ready(function () {
    $('.all_requirements').on('click', function (e) {
      e.preventDefault();
      $(this).toggleClass('active');
      $('.assignment-task').slideToggle();
    });
    $('.assignment-comment .lnricons-chevron-down').on('click', function (e) {
      $('.assignment-comment-content .teacher_review').slideToggle();
    });
  });
})(jQuery);