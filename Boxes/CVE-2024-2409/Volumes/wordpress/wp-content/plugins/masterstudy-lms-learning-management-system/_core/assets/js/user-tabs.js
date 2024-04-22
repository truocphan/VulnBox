"use strict";

(function ($) {
  var paypalOrderId = stmGetParameterByName('stm_lms_paypal_order');
  if (paypalOrderId) {
    var data = {
      event_type: 'stm_lms_paypal_order',
      order_id: paypalOrderId
    };
    stm_lms_print_message(data);
  }
  $(window).on('load', function () {
    var hash = window.location.hash;
    if (hash === '#settings') {
      $('.stm-lms-user_edit_profile_btn').click();
    }
    stmLmsGoToHash();
  });
})(jQuery);
function stmLmsGoToHash() {
  var $ = jQuery;
  var hash = window.location.hash;
  if (hash) {
    var $selector = $('.nav-tabs a[href="' + hash + '"]');
    if (!$selector.length) return false;
    $selector.click();
    $([document.documentElement, document.body]).animate({
      scrollTop: $selector.offset().top
    }, 500);
  }
}
function stmGetParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}