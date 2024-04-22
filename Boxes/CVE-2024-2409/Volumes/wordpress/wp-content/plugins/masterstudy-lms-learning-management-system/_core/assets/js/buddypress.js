"use strict";

(function ($) {
  $(document).ready(function () {
    var $tr = $('body.settings.notifications #buddypress .standard-form .notification-settings tbody tr');
    $tr.each(function () {
      notifications_toggle($(this), false);
    });
    $tr.on('click', function () {
      notifications_toggle($(this), true);
    });
  });
  function notifications_toggle($item, justCheck) {
    console.log(justCheck);
    var $yes = $item.find('.yes input[type="radio"]');
    var $no = $item.find('.no input[type="radio"]');
    var is_yes = $yes.is(':checked');
    var is_no = $no.is(':checked');
    if (is_yes) {
      if (justCheck) {
        $item.closest('tr').addClass('checked-no').removeClass('checked-yes');
        $no.prop('checked', true);
      } else {
        $item.closest('tr').addClass('checked-yes');
      }
    }
    if (is_no) {
      if (justCheck) {
        $item.closest('tr').addClass('checked-yes').removeClass('checked-no');
        $yes.prop('checked', true);
      } else {
        $item.closest('tr').addClass('checked-no');
      }
    }
    return;
  }
})(jQuery);