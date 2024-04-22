"use strict";

(function ($) {
  $(document).ready(function (e) {
    $('.masterstudy-membership-modal__header-title-close, .masterstudy-membership-modal__close').on('click', function () {
      $(this).parents().removeClass('active');
      $(this).closest('.masterstudy-membership-modal').removeClass('active');
      $(this).closest('.masterstudy-membership-modal').find('.masterstudy-membership-modal__wrapper').removeClass('active');
    });
    $('[data-masterstudy-modal]').on('click', function (e) {
      e.preventDefault();
      var modalName = $(this).data('masterstudy-modal');
      $('.masterstudy-buy-button_plans-dropdown').css('transform', 'none');
      $(".".concat(modalName)).addClass('active');
      setTimeout(function () {
        $(".".concat(modalName, " .masterstudy-membership-modal__wrapper")).addClass('active');
      }, 30);
    });
  });
})(jQuery);