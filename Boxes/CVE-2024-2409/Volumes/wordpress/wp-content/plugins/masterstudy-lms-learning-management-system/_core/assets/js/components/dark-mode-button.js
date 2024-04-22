"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-dark-mode-button').click(function () {
      $(this).toggleClass('masterstudy-dark-mode-button_style-dark');
      $.ajax({
        url: mode_data.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'masterstudy_lms_dark_mode',
          nonce: mode_data.nonce,
          mode: !mode_data.dark_mode
        }
      });
    });
  });
})(jQuery);