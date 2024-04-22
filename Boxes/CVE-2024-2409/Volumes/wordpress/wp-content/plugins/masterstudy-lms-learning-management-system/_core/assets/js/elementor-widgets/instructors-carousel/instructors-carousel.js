"use strict";

document.addEventListener('DOMContentLoaded', function () {
  var widgets = document.querySelectorAll('.elementor-widget-ms_lms_instructors_carousel');
  widgets.forEach(function (widget) {
    var widgetData = JSON.parse(widget.getAttribute('data-settings')),
      sliderContainer = widget.querySelector('.ms_lms_instructors_carousel__content'),
      sliderButtonNext = widget.querySelector('.ms_lms_instructors_carousel__navigation_next'),
      sliderButtonPrev = widget.querySelector('.ms_lms_instructors_carousel__navigation_prev'),
      slidesOptions = {
        '100%': 1,
        '50%': 2,
        '33.333333%': 3,
        '25%': 4,
        '20%': 5,
        '16.666666%': 6
      },
      sliderData = {
        'slides_per_view': 4,
        'slides_per_view_tablet': 3,
        'slides_per_view_mobile': 1,
        'autoplay': false,
        'loop': false
      };
    if (widgetData) {
      sliderData = {
        'slides_per_view': widgetData['slides_per_view'],
        'slides_per_view_tablet': widgetData['slides_per_view_tablet'],
        'slides_per_view_mobile': widgetData['slides_per_view_mobile'],
        'autoplay': widgetData['autoplay'],
        'loop': widgetData['loop']
      };
    }
    if (sliderContainer.length !== 0) {
      var mySwiper = new Swiper(sliderContainer, {
        slidesPerView: slidesOptions[sliderData['slides_per_view']],
        watchSlidesProgress: true,
        breakpoints: {
          360: {
            slidesPerView: slidesOptions[sliderData['slides_per_view_mobile']]
          },
          768: {
            slidesPerView: slidesOptions[sliderData['slides_per_view_tablet']]
          },
          1025: {
            slidesPerView: slidesOptions[sliderData['slides_per_view']]
          }
        },
        loop: sliderData['loop'],
        autoplay: sliderData['autoplay'] ? {
          delay: 2000
        } : false,
        navigation: {
          nextEl: sliderButtonNext,
          prevEl: sliderButtonPrev
        }
      });
    }
  });
});