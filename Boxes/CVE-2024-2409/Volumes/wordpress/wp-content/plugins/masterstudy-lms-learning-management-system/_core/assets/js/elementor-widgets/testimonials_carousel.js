"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
document.addEventListener('DOMContentLoaded', function () {
  var widgets = document.querySelectorAll('.elementor-widget-stm_lms_pro_testimonials');
  widgets.forEach(function (widget) {
    var widgetData = JSON.parse(widget.getAttribute('data-settings')),
      bullets = widget.querySelector('.ms-lms-elementor-testimonials-swiper-pagination'),
      sliderWrapper = widget.querySelector('.elementor-testimonials-carousel'),
      sliderContainer = widget.querySelector('.stm-testimonials-carousel-wrapper');
    if (sliderContainer.length !== 0) {
      var mySwiper = new Swiper(sliderContainer, {
        slidesPerView: 1,
        allowTouchMove: true,
        loop: widgetData && widgetData.loop ? true : false,
        autoplay: widgetData && widgetData.autoplay ? {
          delay: 2000
        } : false,
        pagination: {
          el: bullets,
          clickable: true,
          renderBullet: function renderBullet(index, className) {
            var userThumbnail = '',
              testimonialItem = sliderWrapper.children[index];
            if (testimonialItem !== null && _typeof(testimonialItem) === 'object') {
              userThumbnail = testimonialItem.getAttribute('data-thumbnail');
            }
            var span = document.createElement('span');
            span.classList.add(className);
            span.style.backgroundImage = 'url(' + userThumbnail + ')';
            return span.outerHTML;
          }
        }
      });
    }
  });
});