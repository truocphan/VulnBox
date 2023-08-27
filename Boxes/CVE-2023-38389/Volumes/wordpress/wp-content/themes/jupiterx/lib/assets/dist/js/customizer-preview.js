'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

(function ($) {
  var api = wp.customize;

  api('jupiterx_footer_widgets_divider', function (value) {
    value.bind(function (to, from) {
      if ((typeof to === 'undefined' ? 'undefined' : _typeof(to)) !== 'object' || typeof to.width === 'undefined') {
        return;
      }

      var widgetsDivider = $('.jupiterx-footer-widgets .jupiterx-widget-divider');

      if (to.width === '') {
        widgetsDivider.remove();
      }

      if (to.width === from.width) {
        return;
      }

      if (!widgetsDivider.length) {
        $('.jupiterx-footer-widgets .jupiterx-widget').after('<span class="jupiterx-widget-divider"></span>');
      }
    });
  });

  api('jupiterx_product_page_image_main_border', function (value) {
    value.bind(function (to) {
      // $('.woocommerce-product-gallery').flexslider()
    });
  });

  api('jupiterx_product_page_image_max_height', function (value) {
    value.bind(function (to) {
      var slider = $('.woocommerce-product-gallery').data('flexslider');
      setTimeout(function () {
        slider.resize();
      }, 10);
    });
  });

  api('jupiterx_sidebar_divider_widgets', function (value) {
    value.bind(function (to, from) {
      if ((typeof to === 'undefined' ? 'undefined' : _typeof(to)) !== 'object' || typeof to.width === 'undefined') {
        return;
      }

      var widgetsDivider = $('.jupiterx-sidebar .jupiterx-widget-divider');

      if (to.width === '') {
        widgetsDivider.remove();
      }

      if (to.width === from.width) {
        return;
      }

      if (!widgetsDivider.length) {
        $('.jupiterx-sidebar .jupiterx-widget').after('<span class="jupiterx-widget-divider"></span>');
      }
    });
  });
})(jQuery);