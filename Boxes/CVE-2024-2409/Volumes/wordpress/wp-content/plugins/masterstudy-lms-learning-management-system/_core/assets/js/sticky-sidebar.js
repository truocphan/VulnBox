"use strict";

(function ($) {
  $(window).on('load', function () {
    $('.udemy-sidebar-holder').imagesLoaded(function () {
      var sticky = new StickySidebar('.stm-lms-course__sidebar-holder', {
        top: 0,
        bottom: 0,
        resizeSensor: true,
        containerSelector: '.udemy-sidebar-holder',
        innerWrapperSelector: '.stm-lms-course__sidebar'
      });
    });
  });
})(jQuery);