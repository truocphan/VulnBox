"use strict";

(function ($) {
  $(document).ready(function () {
    var $prereq = $('.stm_lms_prerequisite_courses');
    var $prereq_button = $prereq.find('.btn');
    $prereq_button.on('click', function (e) {
      e.preventDefault();
      $prereq.toggleClass('active');
      $prereq.find('ul').slideToggle('fast');
    });
  });
})(jQuery);