"use strict";

(function ($) {
  $(document).ready(function () {
    // launch timers
    $('.masterstudy-countdown').each(function () {
      $(this).countdown({
        timestamp: $(this).data('timer')
      });
    });
  });
})(jQuery);