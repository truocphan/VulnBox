"use strict";

(function ($) {
  $(document).ready(function () {
    $('body').on('click', '.stm_lms_courses_widget_overlay_button_close', function (event) {
      event.preventDefault();
      $(this).closest('.stm_lms_courses_widget_overlay_wrapper').remove();
    });
    $('body').on('click', '.stm_lms_courses_widget_overlay_button_wrapper a', function (event) {
      event.stopPropagation();
    });
  });
})(jQuery);