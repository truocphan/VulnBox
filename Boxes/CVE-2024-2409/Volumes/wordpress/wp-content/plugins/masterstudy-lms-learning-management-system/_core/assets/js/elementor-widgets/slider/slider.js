"use strict";

document.addEventListener('DOMContentLoaded', function () {
  var widgets = document.querySelectorAll('.elementor-widget-ms_lms_slider');
  widgets.forEach(function (widget) {
    var widgetData = JSON.parse(widget.getAttribute('data-settings')),
      sliderContainer = widget.querySelector('.ms_lms_slider_custom'),
      sliderButtonNext = widget.querySelector('.ms_lms_slider_custom__navigation_next'),
      sliderButtonPrev = widget.querySelector('.ms_lms_slider_custom__navigation_prev'),
      sliderData = {
        'autoplay': false,
        'loop': false
      };
    if (widgetData) {
      sliderData = {
        'autoplay': widgetData['autoplay'],
        'delay': widgetData['slide_animation_speed'],
        'loop': widgetData['loop'],
        'effect': widgetData['slide_animation_effect']
      };
    }
    if (sliderContainer.length !== 0) {
      var mySwiper = new Swiper(sliderContainer, {
        slidesPerView: 1,
        allowTouchMove: true,
        loop: sliderData['loop'],
        autoplay: sliderData['autoplay'] && sliderData['delay'] ? {
          delay: sliderData['delay']
        } : false,
        effect: sliderData['effect'],
        navigation: {
          nextEl: sliderButtonNext,
          prevEl: sliderButtonPrev
        }
      });
      if (mySwiper.slides.length > 1) {
        if (mySwiper.navigation.nextEl && mySwiper.navigation.prevEl) {
          mySwiper.navigation.nextEl.classList.add('lms-show-navi');
          mySwiper.navigation.prevEl.classList.add('lms-show-navi');
        }
      }
    }
  });
});