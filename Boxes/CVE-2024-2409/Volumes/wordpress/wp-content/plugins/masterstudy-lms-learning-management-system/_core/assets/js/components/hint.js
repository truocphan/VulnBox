"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-hint').on('mouseenter', function () {
      var $this = $(this);
      var $tooltip = $this.find('.masterstudy-hint__popup');
      var tooltipWidth = $tooltip.width();
      var tooltipHeight = $tooltip.height();
      var tooltipOffset = $tooltip.offset();
      var windowWidth = $(window).width();
      var windowHeight = $(window).height();
      if (tooltipOffset.left < 0) {
        $this.addClass('masterstudy-hint_side-left');
        $this.removeClass('masterstudy-hint_side-right');
      } else if (tooltipOffset.left + tooltipWidth > windowWidth) {
        $this.addClass('masterstudy-hint_side-right');
        $this.removeClass('masterstudy-hint_side-left');
      }
      if (tooltipOffset.top < 0) {
        $this.addClass('masterstudy-hint_side-bottom');
      } else if (tooltipOffset.top + tooltipHeight > windowHeight) {
        $this.removeClass('masterstudy-hint_side-bottom');
      }
      $('.masterstudy-course-player-answer__hint').css('z-index', '7');
      $this.parent('.masterstudy-course-player-answer__hint').css('z-index', '10');
    });
  });
})(jQuery);