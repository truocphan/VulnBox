"use strict";

(function ($) {
  var $watcher;
  var $sticky;
  $(document).ready(function () {
    $watcher = $('.single_product_after_title');
    $sticky = $('.stm_lms_course_sticky_panel');
    if (!$watcher.length || !$sticky.length) return false;
    $(window).on('scroll', function () {
      checkSticky();
    });
    $(window).on('load', function () {
      checkSticky();
    });
    buyClick();
  });
  function checkSticky() {
    var scrollTop = $(document).scrollTop();
    var topDistance = $watcher.offset().top;
    if (topDistance + 100 < scrollTop) {
      if (!$sticky.hasClass('is-visible')) $sticky.addClass('is-visible');
    } else {
      if ($sticky.hasClass('is-visible')) $sticky.removeClass('is-visible');
    }
  }
  function buyClick() {
    $(".stm_lms_course_sticky_panel__button .btn").click(function (e) {
      e.preventDefault();
      var $mixed = $(".stm_lms_mixed_button");
      if ($mixed.length < 1) {
        $mixed = $(".stm-lms-buy-buttons-mixed");
      }
      if ($mixed.length < 1) {
        $mixed = $(".stm-lms-buy-buttons");
      }
      if ($mixed.length < 1) {
        $mixed = $(".btn.start-course");
      }
      $([document.documentElement, document.body]).animate({
        scrollTop: $mixed.offset().top - 150
      }, 800, function () {
        if (!$mixed.hasClass('active')) {
          $mixed.addClass('active');
        }
      });
    });
  }
})(jQuery);