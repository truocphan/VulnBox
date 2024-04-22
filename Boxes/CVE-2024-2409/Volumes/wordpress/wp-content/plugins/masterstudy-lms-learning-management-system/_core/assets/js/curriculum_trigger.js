"use strict";

(function ($) {
  $(document).ready(function () {
    var trigger = $('.stm-lms-curriculum-trigger');
    var url = window.location.href;
    var hashIndex = url.indexOf('#');
    var hash = hashIndex !== -1 ? url.slice(hashIndex) : '';
    trigger.on('click', function () {
      $('body').toggleClass('curriculum-opened');
    });
    if ($('.lesson_style_classic').length > 0 && $(window).width() > 767 && !$('html').hasClass('stm_lms_type_stream') && !$('html').hasClass('stm_lms_type_zoom_conference')) {
      $('body').addClass('curriculum-opened');
    }
    $('.stm-curriculum__close, .stm-lms-course__overlay').on('click', function () {
      $('body').removeClass('curriculum-opened');
    });
    if (hash === '#curriculum_trigger' && trigger.length) {
      trigger.click();
      $('.stm-curriculum-section:not(.opened)').each(function () {
        $(this).closest('.stm-curriculum-section').find('.stm-curriculum-section__lessons').slideToggle();
        $(this).find('.stm-curriculum-item__section').addClass('opened');
      });
    }
  });
})(jQuery);