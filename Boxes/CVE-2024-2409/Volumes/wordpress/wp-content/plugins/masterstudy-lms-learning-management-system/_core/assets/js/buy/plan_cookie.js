"use strict";

(function ($) {
  $(document).ready(function () {
    $('.btn-save-checkpoint').on('click', function (e) {
      e.preventDefault();
      var course_id = $(this).attr('data-course-id');
      $.cookie('stm_lms_course_buy', course_id, {
        path: '/'
      });
      window.location.href = $(this).attr('href');
    });
  });
})(jQuery);