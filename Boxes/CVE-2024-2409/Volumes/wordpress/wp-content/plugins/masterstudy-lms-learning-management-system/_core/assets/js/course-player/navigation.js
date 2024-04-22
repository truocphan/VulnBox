"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-course-player-navigation__next .masterstudy-nav-button').click(function (e) {
      e.preventDefault();
      if ($(this).attr('disabled')) {
        return;
      }
      var hrefValue = $(this).attr('href'),
        jsonData = JSON.parse($(this).attr('data-query'));
      if ($.isEmptyObject(jsonData)) {
        e.preventDefault();
        if (hrefValue || hrefValue.trim() !== '') {
          window.location.href = $(this).attr('href');
        }
        return;
      } else if (!$.isEmptyObject(jsonData)) {
        e.preventDefault();
        $.ajax({
          url: stm_lms_ajaxurl,
          dataType: 'json',
          context: this,
          data: {
            course: jsonData.course,
            lesson: jsonData.lesson,
            action: 'stm_lms_complete_lesson',
            nonce: stm_lms_nonces['stm_lms_complete_lesson']
          },
          beforeSend: function beforeSend() {
            $(this).addClass('masterstudy-nav-button_loading');
          },
          complete: function complete(data) {
            if (hrefValue || hrefValue.trim() !== '') {
              window.location.href = $(this).attr('href');
            } else {
              location.reload();
            }
          }
        });
      }
    });
  });
})(jQuery);