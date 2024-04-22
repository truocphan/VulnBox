"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-curriculum-accordion__section').click(function () {
      var content = $(this).next(),
        isOpened = content.is(':visible');
      if (isOpened) {
        content.animate({
          height: 0
        }, 100, function () {
          setTimeout(function () {
            content.css('display', 'none');
            content.css('height', '');
          }, 300);
        });
        $(this).parent().removeClass('masterstudy-curriculum-accordion__wrapper_opened');
      } else {
        content.css('display', 'block');
        var autoHeight = content.height('auto').height();
        content.height(0).animate({
          height: autoHeight
        }, 100, function () {
          setTimeout(function () {
            content.css('height', '');
          }, 300);
        });
        $(this).parent().addClass('masterstudy-curriculum-accordion__wrapper_opened');
      }
    });
    $('.masterstudy-curriculum-accordion__link_disabled').click(function (event) {
      event.preventDefault();
    });
    $('.masterstudy-hint').hover(function () {
      $(this).closest('.masterstudy-curriculum-accordion__list').css('overflow', 'visible');
    }, function () {
      $(this).closest('.masterstudy-curriculum-accordion__list').css('overflow', 'hidden');
    });
  });
})(jQuery);