"use strict";

(function ($) {
  var currentScrollPosition = 0;
  $(document).ready(function () {
    curriculum_modal_position();
    courses_archive_class();
  });
  function curriculum_modal_position() {
    var $body = $('body');
    $body.on('click', '.stm_lms_edit_item_action', function () {
      currentScrollPosition = $(document).scrollTop();
      window.scrollTo(0, 0);
    });
    $body.on('click', '.stm_lms_item_modal__backdrop, .btn-cancel', function () {
      window.scrollTo(0, currentScrollPosition);
    });
  }
  function courses_archive_class() {
    if ($('.stm_lms_courses_wrapper').length) {
      $('body').addClass('courses_archive');
    }
  }
})(jQuery);