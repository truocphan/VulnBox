"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_acc_tabs__toggle').on('click', function () {
      $('.stm_lms_acc_tabs__secondary').toggleClass('active');
    });
    $(document).click(function (event) {
      var $target = $(event.target);
      if (!$target.closest('.stm_lms_acc_tabs__secondary').length) {
        $('.stm_lms_acc_tabs__secondary').removeClass('active');
      }
    });
  });
})(jQuery);