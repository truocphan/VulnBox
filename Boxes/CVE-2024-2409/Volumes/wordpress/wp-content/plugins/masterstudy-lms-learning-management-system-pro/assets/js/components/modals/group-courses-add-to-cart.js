(function ($) {
  let $body = $('body');
  let group_ids = [];

  /*Go to cart*/
  let $price_btn = $('.masterstudy-group-courses__actions-button-cart');

  $body.on('click', '.masterstudy-group-courses__list-item', function () {
    $price_btn = $('.masterstudy-group-courses__actions-button-cart');
    $(this).toggleClass('active');

    calculatePrice();
  });

  $body.on('click', '.masterstudy-group-courses__actions-button-cart', function (e) {
    e.preventDefault();

    if (!group_ids.length) {
      return false;
    }

    $.ajax({
      url: stm_lms_ajaxurl,
      dataType: 'json',
      context: this,
      data: {
        action: 'stm_lms_add_to_cart_enterprise',
        groups: group_ids,
        course_id: $(this).data('course-id'),
        nonce: stm_lms_nonces['stm_lms_add_to_cart_enterprise'],
      },
      complete: function (data) {
        data = data['responseJSON'];

        if (data.redirect) {
          window.location.replace(data.cart_url);
        } else {
          $(this).html(data.text).attr('href', data.cart_url).removeClass('masterstudy-group-courses__actions-button-cart');
        }
      }
    });
  });
  /*End go to cart*/

  function calculatePrice() {
    group_ids = [];
    let total = 0;

    $('.masterstudy-group-courses__list-item').each(function () {
      if ($(this).hasClass('active')) {
        total++;
        group_ids.push($(this).attr('data-masterstudy-group-courses-group-id'));
      }
    });

    let price = $price_btn.data('masterstudy-group-courses-price');
    $price_btn.find('span').html(stm_lms_price_format(price * total));

    if (group_ids.length) {
      $price_btn.removeClass('disable');
    } else {
      $price_btn.addClass('disable');
    }
  }
})(jQuery);
