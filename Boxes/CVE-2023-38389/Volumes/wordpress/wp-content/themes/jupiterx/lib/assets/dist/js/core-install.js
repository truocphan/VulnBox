'use strict';

(function ($, wp) {

  var $notice = $('.jupiterx-core-install-notice');

  /**
   * Hide admin notice after dismissed by user.
   */
  $(document).on('click', $notice.selector + ' .notice-dismiss', function () {
    var data = {
      _wpnonce: $('#jupiterx-core-installer-notice-nonce').val(),
      state: 'disabled'
    };

    wp.ajax.post('jupiterx_core_install_plugin_notice', data);

    $notice.slideUp();
  });
})(jQuery, wp);