"use strict";

(function ($) {
  "use strict";

  $(document).ready(function () {
    courses_carousel();
  });
  function courses_carousel() {
    var owlRtl = false;
    if ($('body').hasClass('rtl')) {
      owlRtl = true;
    }
    $('.stm_lms_instructors_carousel').each(function () {
      var $this = $(this).find('.stm_lms_instructors__grid');
      var per_row = $(this).attr('data-items');
      var per_row_md = $(this).attr('data-items-md');
      var per_row_sm = $(this).attr('data-items-sm');
      var per_row_xs = $(this).attr('data-items-xs');
      var dots = $(this).attr('data-pagination') === 'enable';
      $(this).on('initialized.owl.carousel', function (event) {
        var totalItems = event.item.count;
        var visibleItems = event.page.size;
        var $buttons = $(this).closest('.stm_lms_instructors_carousel').find('.stm_lms_courses_carousel__buttons');
        if (totalItems > visibleItems) {
          $buttons.removeClass('hidden');
        } else {
          $buttons.addClass('hidden');
        }
      });
      $this.imagesLoaded(function () {
        $this.owlCarousel({
          rtl: owlRtl,
          dots: dots,
          items: per_row,
          autoplay: false,
          loop: false,
          slideBy: 1,
          mouseDrag: false,
          smartSpeed: 400,
          responsive: {
            0: {
              items: per_row_xs
            },
            500: {
              items: per_row_sm
            },
            1024: {
              items: per_row_md
            },
            1500: {
              items: per_row
            }
          }
        });
      });
      $this.closest('.stm_lms_instructors_carousel_wrapper').find('.stm_lms_courses_carousel__button_prev').on('click', function (e) {
        e.preventDefault();
        $this.trigger('prev.owl.carousel');
      });
      $this.closest('.stm_lms_instructors_carousel_wrapper').find('.stm_lms_courses_carousel__button_next').on('click', function (e) {
        e.preventDefault();
        $this.trigger('next.owl.carousel');
      });
    });
  }
})(jQuery);