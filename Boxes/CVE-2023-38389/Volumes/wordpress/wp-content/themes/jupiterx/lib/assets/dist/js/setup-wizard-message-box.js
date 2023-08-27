'use strict';

(function ($) {

  $('.jupiterx-setup-wizard-message-box').on('click', '.discard', function (event) {
    event.preventDefault();

    // Disable button.
    $(this).attr('disabled', 'disabled');

    // Hide message box UI.
    $(event.delegateTarget).fadeOut(400);

    // Request hide notice.
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'jupiterx_setup_wizard_hide_notice'
      }
    });
  });
})(jQuery);