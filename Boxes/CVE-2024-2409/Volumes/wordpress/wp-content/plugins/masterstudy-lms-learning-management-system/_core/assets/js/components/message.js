"use strict";

(function ($) {
  $(document).ready(function () {
    $.each($('.masterstudy-message'), function (i, container) {
      $(container).find('.masterstudy-message__close').on('click', function (e) {
        $(container).addClass('masterstudy-message_hidden');
      });
    });
  });
})(jQuery);