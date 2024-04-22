"use strict";

(function ($) {
  var copyTimeout;
  $(document).ready(function () {
    $('.affiliate_points').on('click', function () {
      var selector = $(this).data('copy');
      selector = typeof selector !== 'undefined' ? '#' + selector : '#text_to_copy';

      /*Copy text*/
      copyToClipboard(selector);

      /*Visual*/
      var $this = $(this);
      $this.addClass('url_copied');
      clearTimeout(copyTimeout);
      copyTimeout = setTimeout(function () {
        $this.removeClass('url_copied');
      }, 3000);
    });
  });
  function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
  }
})(jQuery);