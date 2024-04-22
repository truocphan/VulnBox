"use strict";

(function ($) {
  "use strict";

  var is_inited = false;
  window.addEventListener('load', function () {
    var coming_soon_containers = document.querySelectorAll('.coming-soon-card-countdown-container');
    coming_soon_containers.forEach(function (container) {
      if (container.clientWidth < 220) {
        container.classList.add('smaller-container');
      }
    });
  });
  $(document).ready(function () {
    $('.stm_lms_courses_carousel__term').on('click', function (e) {
      e.preventDefault();
      var $wrapper = $(this).closest('.stm_lms_courses_carousel');
      var $courses = $wrapper.find('.stm_lms_courses__grid');
      if ($courses.hasClass('loading')) return false;
      $(this).closest('.stm_lms_courses_carousel__terms').find('.stm_lms_courses_carousel__term').removeClass('active');
      $(this).addClass('active');
      var term = $(this).attr('data-term');
      if (typeof term !== 'undefined') {
        var args = $wrapper.attr('data-args').replace('}', ', "term":' + term + '}');
      } else {
        var args = $wrapper.attr('data-args');
      }
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          action: 'stm_lms_load_content',
          args: args,
          offset: 0,
          template: $wrapper.attr('data-template'),
          nonce: stm_lms_nonces['load_content']
        },
        beforeSend: function beforeSend() {
          $courses.addClass('loading');
        },
        complete: function complete(data) {
          var data = data['responseJSON'];

          /*Remove OWL Carousel*/
          $courses.trigger('destroy.owl.carousel').removeClass('owl-carousel stm_owl-theme owl-loaded');
          $courses.find('.owl-stage-outer').children().unwrap();

          /*Insert new items*/
          $courses.html(data.content.replace(/stm_lms_courses__single stm_lms_courses__single_animation/g, 'stm_lms_courses__single stm_lms_courses__single_animation stm_carousel_glitch'));

          /*RE INIT CAROUSEL*/
          courses_carousel();
          setTimeout(function () {
            $courses.removeClass('loading');
          }, 300);
        }
      });
    });
    if (!is_inited) courses_carousel();
    $(window).on('elementor/frontend/init', function () {
      elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
        if (!is_inited) courses_carousel();
      });
    });
  });
  function courses_carousel() {
    var owlRtl = false;
    if ($('body').hasClass('rtl')) {
      owlRtl = true;
    }
    $(document).on({
      mouseenter: function mouseenter() {
        $(this).closest('.stm_lms_courses_carousel').addClass('active');
      },
      mouseleave: function mouseleave() {
        $(this).closest('.stm_lms_courses_carousel').removeClass('active');
      }
    }, '.stm_lms_courses__single');
    var $carousels = $('.stm_lms_courses_carousel');
    if (!$carousels.length) is_inited = false;
    $carousels.each(function () {
      var $this = $(this).find('.stm_lms_courses__grid');
      var per_row = $(this).attr('data-items');
      var mouseDrag = $(this).attr('data-mouse_drag') === 'enable';
      var dots = $(this).attr('data-pagination') === 'enable';
      var loop = $(this).attr('data-loop') === 'enable';
      $(this).on('initialized.owl.carousel', function (event) {
        var totalItems = event.item.count;
        var visibleItems = event.page.size;
        var $buttons = $(this).closest('.stm_lms_courses_carousel').find('.stm_lms_courses_carousel__buttons');
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
          loop: loop,
          slideBy: 1,
          mouseDrag: mouseDrag,
          smartSpeed: 400,
          responsive: {
            0: {
              items: 1
            },
            500: {
              items: 2
            },
            1024: {
              items: 4
            },
            1500: {
              items: per_row
            }
          }
        });
      });
      $this.closest('.stm_lms_courses_carousel_wrapper').find('.stm_lms_courses_carousel__button_prev').on('click', function (e) {
        e.preventDefault();
        $this.trigger('prev.owl.carousel');
      });
      $this.closest('.stm_lms_courses_carousel_wrapper').find('.stm_lms_courses_carousel__button_next').on('click', function (e) {
        e.preventDefault();
        $this.trigger('next.owl.carousel');
      });
    });
  }
})(jQuery);