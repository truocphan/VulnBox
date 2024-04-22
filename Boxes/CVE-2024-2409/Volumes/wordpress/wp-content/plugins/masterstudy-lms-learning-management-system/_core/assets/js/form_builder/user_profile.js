"use strict";

(function ($) {
  $(document).ready(function () {
    add_value();
    $('.checkbox_for_choices').click(function () {
      if (!$(this).attr('checked')) {
        $(this).attr('checked', 'checked');
      } else {
        $(this).removeAttr('checked');
      }
      add_value();
    });
    function add_value() {
      $('.checkbox_for_value').parent().each(function () {
        var values = [];
        $(this).find('.checkbox_for_choices').each(function () {
          if ($(this).attr('checked')) {
            values.push($(this).val());
          }
        });
        $(this).find('.checkbox_for_value').val(values);
      });
    }
  });
})(jQuery);