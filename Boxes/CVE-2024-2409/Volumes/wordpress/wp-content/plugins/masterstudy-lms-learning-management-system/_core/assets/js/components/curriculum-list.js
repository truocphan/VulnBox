"use strict";

(function ($) {
  $(document).ready(function () {
    $('.masterstudy-curriculum-list__toggler').click(function (event) {
      event.preventDefault();
      toggleContainer.call(this, true);
    });
    $('.masterstudy-curriculum-list__excerpt-toggler').click(function (event) {
      event.preventDefault();
      toggleContainer.call(this, false);
    });
    $('.masterstudy-curriculum-list__link_disabled').click(function (event) {
      event.preventDefault();
    });
    $('.masterstudy-hint').hover(function () {
      $(this).closest('.masterstudy-curriculum-list__materials').css('overflow', 'visible');
    }, function () {
      $(this).closest('.masterstudy-curriculum-list__materials').css('overflow', 'hidden');
    });
    function toggleContainer(main) {
      var content = main ? $(this).parent().next() : $(this).parent().parent().next(),
        isOpened = content.is(':visible'),
        openedClass = main ? 'masterstudy-curriculum-list__wrapper_opened' : 'masterstudy-curriculum-list__container-wrapper_opened';
      if (isOpened) {
        content.animate({
          height: 0
        }, 100, function () {
          setTimeout(function () {
            content.css('display', 'none');
            content.css('height', '');
          }, 300);
        });
        $(this).parent().parent().removeClass(openedClass);
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
        $(this).parent().parent().addClass(openedClass);
      }
    }
  });
})(jQuery);