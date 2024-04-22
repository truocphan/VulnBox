"use strict";

(function ($) {
  $(document).ready(function () {
    $(document).on('click', '.ms-lms-table-update', function (e) {
      e.preventDefault();
      var button = $('.ms-lms-table-update');
      $.ajax({
        url: stm_lms_table_data.ajax_url + '?action=stm_lms_tables_update&nonce=' + stm_lms_table_data.nonce,
        type: 'POST',
        processData: false,
        contentType: false,
        beforeSend: function beforeSend() {
          button.text(stm_lms_table_data.loading);
        },
        success: function success() {
          button.text(stm_lms_table_data.success);
        }
      });
    });
  });
})(jQuery);