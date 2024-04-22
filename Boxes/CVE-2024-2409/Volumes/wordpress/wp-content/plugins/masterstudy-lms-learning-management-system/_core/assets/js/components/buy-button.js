"use strict";

(function ($) {
  $(document).ready(function () {
    /* Show Dropdown Payments */
    var animationActive = false;
    $('.masterstudy-buy-button').on('click', function () {
      toggleAnimation();
    });
    $('.masterstudy-buy-button_plans-dropdown').on('click', function (event) {
      event.stopPropagation();
    });
    function toggleAnimation() {
      animationActive = !animationActive;
      $('.masterstudy-buy-button').toggleClass('dropdown-show', animationActive);
    }
    $(document).on('click', function (event) {
      if (!$(event.target).closest('.masterstudy-buy-button').length && animationActive) {
        toggleAnimation();
      }
    });
    /* End Show Dropdown Payments */

    /* Link for LMS checkout */
    function handleButtonClick(event, attribute, ajaxAction, nonce) {
      event.preventDefault();
      var item_id = $(this).attr(attribute);
      if (typeof item_id === 'undefined') {
        window.location = $(this).attr('href');
        return false;
      }
      $.ajax({
        url: masterstudy_buy_button_data.ajax_url,
        dataType: 'json',
        context: this,
        data: {
          action: ajaxAction,
          nonce: nonce,
          item_id: masterstudy_buy_button_data.item_id
        },
        beforeSend: function beforeSend() {
          $(this).find('.masterstudy-buy-button__title').addClass('masterstudy-buy-button__loading');
        },
        complete: function complete(data) {
          var responseJSON = data['responseJSON'];
          $(this).find('.masterstudy-buy-button__title').removeClass('masterstudy-buy-button__loading');
          $(this).find('.masterstudy-buy-button__title').text(responseJSON['text']);
          if (responseJSON['cart_url']) {
            if (responseJSON['redirect']) window.location = responseJSON['cart_url'];
            $(this).attr('href', responseJSON['cart_url']).removeAttr(attribute);
          }
        }
      });
    }
    $('[data-purchased-course]').on('click', function (event) {
      handleButtonClick.call(this, event, 'data-purchased-course', 'stm_lms_add_to_cart', masterstudy_buy_button_data.get_nonce);
    });
    $('[data-guest]').on('click', function (event) {
      handleButtonClick.call(this, event, 'data-guest', 'stm_lms_add_to_cart_guest', masterstudy_buy_button_data.get_guest_nonce);
      var item_id = $(this).attr('data-guest');
      var currentCart = getCookie('stm_lms_notauth_cart');
      currentCart = currentCart === undefined || currentCart === null ? [] : JSON.parse(decodeURIComponent(currentCart));
      var item_id_str = item_id.toString();
      currentCart = currentCart.map(String);
      if (!currentCart.includes(item_id_str)) {
        currentCart.push(item_id_str);
      }

      // Update cookies
      setCookie('stm_lms_notauth_cart', JSON.stringify(currentCart).replace(/"/g, ''), {
        path: '/'
      });

      // Get cookies
      function getCookie(name) {
        var value = "; ".concat(document.cookie);
        var parts = value.split("; ".concat(name, "="));
        if (parts.length === 2) return parts.pop().split(';').shift();
      }

      // Install cookies
      function setCookie(name, value) {
        var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
        document.cookie = "".concat(name, "=").concat(value, "; path=").concat(options.path);
      }
    });
    /* End Link for LMS checkout */
  });
})(jQuery);