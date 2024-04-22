"use strict";

(function ($) {
  $(document).ready(function () {
    $.each($('.masterstudy-radio-buttons').find('label'), function (i, radioLabel) {
      var isChecked = $(radioLabel).find('input').attr('checked');
      if (isChecked) {
        $(radioLabel).addClass('masterstudy-radio__label_checked');
      }
      $(radioLabel).on('click', function (event) {
        $('.masterstudy-radio-buttons').find('input').not(this).attr('checked', false);
        $('.masterstudy-radio-buttons').find('label').not(this).removeClass('masterstudy-radio__label_checked');
        $(this).find('input').attr('checked', true);
        $(radioLabel).addClass('masterstudy-radio__label_checked');
      });
    });
  });
})(jQuery);