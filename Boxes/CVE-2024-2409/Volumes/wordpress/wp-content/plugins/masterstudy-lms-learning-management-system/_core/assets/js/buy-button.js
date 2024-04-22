"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_mixed_button.subscription_enabled > .btn').on('click', function () {
      var height_list = $('.stm_lms_mixed_button__list')[0].clientHeight + 40;
      var sticky_buttons = $('.stm-lms-buy-buttons-sticky');
      var sticky_buy_buttons = $('.stm-lms-buy-buttons-mixed');
      if (sticky_buttons.length === 0) {
        sticky_buy_buttons.addClass('stm-lms-buy-buttons-sticky');
        sticky_buy_buttons.css('margin-bottom', height_list + 'px');
        $('.stm-lms-buy-buttons-enterprise').css('margin-bottom', '-165px');
      } else {
        sticky_buy_buttons.css('margin-bottom', '15px');
        $('.stm-lms-buy-buttons-enterprise').css('margin-bottom', '15px');
        sticky_buy_buttons.removeClass('stm-lms-buy-buttons-sticky');
      }
      $('.stm_lms_mixed_button').toggleClass('active');
    });
    var $body = $('body');
    var buy_buttons = '.stm-lms-buy-buttons';
    $body.click(function (e) {
      if (!$(buy_buttons).is(e.target) && $(buy_buttons).has(e.target).length === 0 && !$('.stm_lms_course_sticky_panel__button').is(e.target) && $('.stm_lms_course_sticky_panel__button').has(e.target).length === 0) {
        // if div is not target nor its descendant
        $('.stm-lms-buy-buttons-sticky').css('margin-bottom', '15px');
        $(buy_buttons).removeClass('stm-lms-buy-buttons-sticky');
        $('.stm_lms_mixed_button').removeClass('active');
      }
    });

    /*Guest checkout*/
    $body.on('click', '[data-guest]', function (e) {
      e.preventDefault();
      var item_id = $(this).data('guest');
      var currentCart = $.cookie('stm_lms_notauth_cart');
      currentCart = typeof currentCart === 'undefined' ? [] : JSON.parse(currentCart);
      if (!currentCart.includes(item_id)) currentCart.push(item_id);
      $.cookie('stm_lms_notauth_cart', JSON.stringify(currentCart), {
        path: '/'
      });
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          item_id: item_id,
          action: 'stm_lms_add_to_cart_guest',
          nonce: stm_lms_nonces['stm_lms_add_to_cart_guest']
        },
        beforeSend: function beforeSend() {
          $(this).addClass('loading');
        },
        complete: function complete(data) {
          data = data['responseJSON'];
          $(this).removeClass('loading');
          $(this).find('span').text(data['text']);
          if (data['cart_url']) {
            if (data['redirect']) window.location = data['cart_url'];
            $(this).attr('href', data['cart_url']).removeAttr('data-guest').addClass('goToCartUrl');
          }
        }
      });
    });
    $body.on('click', '.goToCartUrl', function () {
      window.location.href = $(this).attr('href');
    });
  });
})(jQuery);