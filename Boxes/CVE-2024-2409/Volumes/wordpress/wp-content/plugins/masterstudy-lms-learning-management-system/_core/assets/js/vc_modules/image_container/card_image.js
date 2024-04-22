"use strict";

(function ($) {
  $(document).ready(function () {
    /** Card Image container height **/
    $('.stm_lms_courses__single--image__container').each(function () {
      var cardImgCont = $(this);
      if (cardImgCont.data('height')) {
        cardImgCont.css('height', cardImgCont.data('height'));
      }
    });
  });
})(jQuery);