"use strict";

(function ($) {
  $(document).ready(function () {
    $('body').on('click', '.stm-lms-wishlist', function () {
      var post_id = $(this).attr('data-id');
      if ($('body').hasClass('logged-in')) {
        $.ajax({
          url: stm_lms_ajaxurl,
          dataType: 'json',
          context: this,
          data: {
            action: 'stm_lms_wishlist',
            nonce: stm_lms_nonces['stm_lms_wishlist'],
            post_id: post_id
          },
          beforeSend: function beforeSend() {
            $(this).addClass('loading');
          },
          complete: function complete(data) {
            var data = data['responseJSON'];
            $(this).removeClass('loading');
            $(this).find('i').attr('class', data.icon);
            $(this).find('span').text(data.text);
          }
        });
      } else {
        /*Get cookie*/
        var cookie_name = 'stm_lms_wishlist';
        var wishlist = $.cookie(cookie_name);
        wishlist = typeof wishlist === 'undefined' ? [] : wishlist.split(',');
        if (wishlist.indexOf(post_id) >= 0) {
          /*Remove from cookie*/
          var index = wishlist.indexOf(post_id);
          wishlist.splice(index, 1);
          var icon = $(this).attr('data-add-icon');
          var text = $(this).attr('data-add');
        } else {
          /*Set cookie*/
          wishlist.push(post_id);
          var icon = $(this).attr('data-remove-icon');
          var text = $(this).attr('data-remove');
        }
        $.cookie(cookie_name, wishlist.join(','), {
          path: '/'
        });
        $(this).find('i').attr('class', icon);
        $(this).find('span').text(text);
      }
    });
  });
})(jQuery);